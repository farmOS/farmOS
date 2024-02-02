<?php

namespace Drupal\farm_quick_inventory\Plugin\QuickForm;

use Drupal\asset\Entity\AssetInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Render\Markup;
use Drupal\Core\Session\AccountInterface;
use Drupal\farm_inventory\AssetInventoryInterface;
use Drupal\farm_quick\Plugin\QuickForm\ConfigurableQuickFormInterface;
use Drupal\farm_quick\Plugin\QuickForm\QuickFormBase;
use Drupal\farm_quick\Traits\ConfigurableQuickFormTrait;
use Drupal\farm_quick\Traits\QuickFormElementsTrait;
use Drupal\farm_quick\Traits\QuickLogTrait;
use Drupal\farm_quick\Traits\QuickTermTrait;
use Drupal\log\Entity\Log;
use Drupal\taxonomy\TermInterface;
use Psr\Container\ContainerInterface;

/**
 * Inventory quick form.
 *
 * @QuickForm(
 *   id = "inventory",
 *   label = @Translation("Inventory"),
 *   description = @Translation("Record asset inventory adjustments."),
 *   helpText = @Translation("Use this form to increment, decrement, or reset the inventory of an asset. A new log will be created to record the adjustment."),
 *   permissions = {}
 * )
 */
class Inventory extends QuickFormBase implements ConfigurableQuickFormInterface {

  use ConfigurableQuickFormTrait;
  use QuickLogTrait;
  use QuickFormElementsTrait;
  use QuickTermTrait;

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Asset inventory service.
   *
   * @var \Drupal\farm_inventory\AssetInventoryInterface
   */
  protected $assetInventory;

  /**
   * Current user object.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * Constructs a QuickFormBase object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger service.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   * @param \Drupal\farm_inventory\AssetInventoryInterface $asset_inventory
   *   Asset inventory service.
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   Current user object.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, MessengerInterface $messenger, EntityTypeManagerInterface $entity_type_manager, AssetInventoryInterface $asset_inventory, AccountInterface $current_user) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $messenger);
    $this->messenger = $messenger;
    $this->entityTypeManager = $entity_type_manager;
    $this->assetInventory = $asset_inventory;
    $this->currentUser = $current_user;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('messenger'),
      $container->get('entity_type.manager'),
      $container->get('asset.inventory'),
      $container->get('current_user'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function access(AccountInterface $account) {

    // Check to ensure the user has permission to create the configured log type
    // and view the configured asset.
    $result = AccessResult::allowedIf($this->entityTypeManager->getAccessControlHandler('log')->createAccess($this->configuration['log_type'], $account));
    if (!empty($this->configuration['asset'])) {
      $asset = $this->entityTypeManager->getStorage('asset')->load($this->configuration['asset']);
      $result = $result->andIf(AccessResult::allowedIf($asset->access('view')));
    }
    return $result;
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'asset' => NULL,
      'measure' => NULL,
      'units' => NULL,
      'inventory_adjustment' => 'reset',
      'log_type' => 'observation',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, string $id = NULL) {

    // Date.
    $form['date'] = [
      '#type' => 'datetime',
      '#title' => $this->t('Date'),
      '#default_value' => new DrupalDateTime('midnight', $this->currentUser->getTimeZone()),
      '#required' => TRUE,
    ];

    // Asset.
    $form['asset'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Asset'),
      '#description' => $this->t("Which asset's inventory is being adjusted?"),
      '#target_type' => 'asset',
      '#selection_settings' => [
        'sort' => [
          'field' => 'status',
          'direction' => 'ASC',
        ],
      ],
      '#maxlength' => 1024,
      '#required' => TRUE,
    ];
    if (!empty($this->configuration['asset'])) {
      $form['asset']['#default_value'] = $this->entityTypeManager->getStorage('asset')->load($this->configuration['asset']);
    }

    // Quantity.
    $form['quantity'] = $this->buildInlineContainer();
    $form['quantity']['#tree'] = TRUE;
    $form['quantity']['value'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Quantity'),
      '#size' => 16,
      '#required' => TRUE,
    ];
    $form['quantity']['units'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Units'),
      '#target_type' => 'taxonomy_term',
      '#selection_settings' => [
        'target_bundles' => ['unit'],
      ],
      '#autocreate' => [
        'bundle' => 'unit',
      ],
      '#size' => 16,
    ];
    if (!empty($this->configuration['units'])) {
      $form['quantity']['units']['#default_value'] = $this->createOrLoadTerm($this->configuration['units'], 'unit');
    }
    $form['quantity']['measure'] = [
      '#type' => 'select',
      '#title' => $this->t('Measure'),
      '#options' => array_merge(['' => ''], quantity_measure_options()),
      '#default_value' => $this->configuration['measure'],
    ];

    // Inventory adjustment.
    $form['inventory_adjustment'] = [
      '#type' => 'select',
      '#title' => $this->t('Adjustment type'),
      '#description' => $this->t('What type of inventory adjustment is this?'),
      '#options' => [
        'increment' => $this->t('Increment'),
        'decrement' => $this->t('Decrement'),
        'reset' => $this->t('Reset'),
      ],
      '#required' => TRUE,
      '#default_value' => $this->configuration['inventory_adjustment'],
    ];

    // Notes.
    $form['notes'] = [
      '#type' => 'details',
      '#title' => $this->t('Notes'),
    ];
    $form['notes']['notes'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Notes'),
      '#title_display' => 'invisible',
      '#format' => 'default',
    ];

    // Advanced.
    $form['advanced'] = [
      '#type' => 'details',
      '#title' => $this->t('Advanced'),
    ];

    // Log type.
    $form['advanced']['log_type'] = [
      '#type' => 'select',
      '#title' => $this->t('Log type'),
      '#description' => $this->t('Select the type of log to create.'),
      '#options' => $this->logTypeOptions(),
      '#required' => TRUE,
      '#default_value' => $this->configuration['log_type'],
    ];

    // Log name.
    // Provide a checkbox to allow customizing this. Otherwise, it will be
    // automatically generated on submission.
    $form['advanced']['custom_name'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Customize log name'),
      '#description' => $this->t('This allows the log name to be customized. Otherwise, a default name will be generated.'),
      '#default_value' => FALSE,
      '#ajax' => [
        'callback' => [$this, 'logNameCallback'],
        'wrapper' => 'log-name',
      ],
    ];
    $form['advanced']['name_wrapper'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'log-name'],
    ];
    if ($form_state->getValue('custom_name', FALSE)) {
      $form['advanced']['name_wrapper']['name'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Log name'),
        '#maxlength' => 255,
        '#default_value' => $this->generateLogName($form_state),
        '#required' => TRUE,
      ];
    }

    // Done.
    $form['done'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Completed'),
      '#default_value' => TRUE,
    ];

    return $form;
  }

  /**
   * Build a list of log type options.
   *
   * @return array
   *   Returns an array of log type labels, keyed by machine name.
   *   Only log types that the user has access to create will be included.
   */
  protected function logTypeOptions() {
    $log_access_control_handler = $this->entityTypeManager->getAccessControlHandler('log');
    $log_types = array_filter($this->entityTypeManager->getStorage('log_type')->loadMultiple(), function ($log_type) use ($log_access_control_handler) {
      return $log_access_control_handler->createAccess($log_type->id(), $this->currentUser);
    });
    return array_map(function ($log_type) {
      return $log_type->label();
    }, $log_types);
  }

  /**
   * Generate log name.
   *
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state object.
   *
   * @return string
   *   Returns a log name string.
   */
  protected function generateLogName(FormStateInterface $form_state) {
    $log_name = '';

    // Get the asset name. If an asset has not been selected, bail.
    $asset = $form_state->getValue('asset');
    if (is_numeric($asset)) {
      $asset = $this->entityTypeManager->getStorage('asset')->load($asset);
    }
    if (!($asset instanceof AssetInterface)) {
      return $log_name;
    }

    // Create a summary of the quantity.
    $quantity_summary = $form_state->getValue(['quantity', 'value']);
    $units = $form_state->getValue(['quantity', 'units']);
    $measure = $form_state->getValue(['quantity', 'measure']);
    if (!empty($units)) {
      if (is_numeric($units)) {
        $units = $this->entityTypeManager->getStorage('taxonomy_term')->load($units);
      }
      elseif (is_array($units) && !empty($units['entity'])) {
        $units = $units['entity'];
      }
      if ($units instanceof TermInterface) {
        $quantity_summary .= ' ' . $units->label();
      }
    }
    if (!empty($measure)) {
      $quantity_summary .= ' (' . $measure . ')';
    }

    // Generate the log name based on the inventory adjustment type.
    switch ($form_state->getValue('inventory_adjustment')) {
      case 'increment':
        $log_name = $this->t('Increment inventory of @asset by @quantity', ['@asset' => Markup::create($asset->label()), '@quantity' => $quantity_summary]);
        break;

      case 'decrement':
        $log_name = $this->t('Decrement inventory of @asset by @quantity', ['@asset' => Markup::create($asset->label()), '@quantity' => $quantity_summary]);
        break;

      case 'reset':
        $log_name = $this->t('Reset inventory of @asset to @quantity', ['@asset' => Markup::create($asset->label()), '@quantity' => $quantity_summary]);
        break;
    }

    return $log_name;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    // Mock a minimal log of the selected type to ensure that it validates. This
    // protects against creating log types that have required fields that this
    // form is not able to populate.
    $log = Log::create([
      'type' => $form_state->getValue('log_type'),
    ]);
    $violations = $log->validate();
    if ($violations->count()) {
      $form_state->setError($form['log_type'], $this->t('The selected log type cannot be created. It may have required fields that this form is unable to populate.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    // Load asset.
    $asset = $this->entityTypeManager->getStorage('asset')->load($form_state->getValue('asset'));

    // Load units term (if specified).
    $units = $form_state->getValue(['quantity', 'units']);
    if (is_numeric($units)) {
      $units = $this->entityTypeManager->getStorage('taxonomy_term')->load($form_state->getValue(['quantity', 'units']));
    }
    elseif (is_array($units) && !empty($units['entity'])) {
      $units = $units['entity'];
    }

    // Create a quantity for the inventory adjustment.
    $quantity = [
      'measure' => $form_state->getValue(['quantity', 'measure']),
      'value' => $form_state->getValue(['quantity', 'value']),
      'units' => $units,
      'inventory_adjustment' => $form_state->getValue('inventory_adjustment'),
      'inventory_asset' => $asset,
    ];

    // Draft an inventory adjustment log from the user-submitted data.
    $timestamp = $form_state->getValue('date')->getTimestamp();
    $status = $form_state->getValue('done') ? 'done' : 'pending';
    $log = [
      'type' => $form_state->getValue('log_type'),
      'timestamp' => $timestamp,
      'quantity' => [$quantity],
      'notes' => $form_state->getValue('notes'),
      'status' => $status,
    ];

    // Generate a name for the log.
    // If a custom plant name was provided, use that. Otherwise, generate one.
    $log['name'] = $this->generateLogName($form_state);
    if (!empty($form_state->getValue('custom_name', FALSE)) && $form_state->hasValue('name')) {
      $log['name'] = $form_state->getValue('name');
    }

    // Create the log.
    $this->createLog($log);
  }

  /**
   * Ajax callback for log name field.
   */
  public function logNameCallback(array $form, FormStateInterface $form_state) {
    return $form['advanced']['name_wrapper'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {

    // Asset.
    $form['asset'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Asset'),
      '#description' => $this->t("Which asset's inventory is being adjusted?"),
      '#target_type' => 'asset',
      '#selection_settings' => [
        'sort' => [
          'field' => 'status',
          'direction' => 'ASC',
        ],
      ],
      '#maxlength' => 1024,
    ];
    if (!empty($this->configuration['asset'])) {
      $form['asset']['#default_value'] = $this->entityTypeManager->getStorage('asset')->load($this->configuration['asset']);
    }

    // Units.
    $form['units'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Units'),
      '#target_type' => 'taxonomy_term',
      '#selection_settings' => [
        'target_bundles' => ['unit'],
      ],
      '#autocreate' => [
        'bundle' => 'unit',
      ],
      '#size' => 16,
    ];
    if (!empty($this->configuration['units'])) {
      $form['units']['#default_value'] = $this->createOrLoadTerm($this->configuration['units'], 'unit');
    }

    // Measure.
    $form['measure'] = [
      '#type' => 'select',
      '#title' => $this->t('Measure'),
      '#options' => array_merge(['' => ''], quantity_measure_options()),
      '#default_value' => $this->configuration['measure'],
    ];

    // Inventory adjustment.
    $form['inventory_adjustment'] = [
      '#type' => 'select',
      '#title' => $this->t('Adjustment type'),
      '#description' => $this->t('What type of inventory adjustment is this?'),
      '#options' => [
        'increment' => $this->t('Increment'),
        'decrement' => $this->t('Decrement'),
        'reset' => $this->t('Reset'),
      ],
      '#default_value' => $this->configuration['inventory_adjustment'],
    ];

    // Log type.
    $form['log_type'] = [
      '#type' => 'select',
      '#title' => $this->t('Log type'),
      '#description' => $this->t('Select the type of log to create.'),
      '#options' => $this->logTypeOptions(),
      '#default_value' => $this->configuration['log_type'],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    $this->configuration['asset'] = $form_state->getValue('asset');
    $this->configuration['units'] = NULL;
    if (!empty($form_state->getValue('units'))) {
      $this->configuration['units'] = $form_state->getValue('units')['entity']->label();
    }
    $this->configuration['measure'] = $form_state->getValue('measure');
    $this->configuration['inventory_adjustment'] = $form_state->getValue('inventory_adjustment');
    $this->configuration['log_type'] = $form_state->getValue('log_type');
  }

}
