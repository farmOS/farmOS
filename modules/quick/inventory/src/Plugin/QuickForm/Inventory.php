<?php

namespace Drupal\farm_quick_inventory\Plugin\QuickForm;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Render\Markup;
use Drupal\Core\Session\AccountInterface;
use Drupal\farm_inventory\AssetInventoryInterface;
use Drupal\farm_quick\Plugin\QuickForm\QuickFormBase;
use Drupal\farm_quick\Plugin\QuickForm\QuickFormInterface;
use Drupal\farm_quick\Traits\QuickFormElementsTrait;
use Drupal\farm_quick\Traits\QuickLogTrait;
use Psr\Container\ContainerInterface;

/**
 * Inventory quick form.
 *
 * @QuickForm(
 *   id = "inventory",
 *   label = @Translation("Inventory"),
 *   description = @Translation("Record asset inventory adjustments."),
 *   helpText = @Translation("Use this form to increment, decrement, or reset the inventory of an asset. A new log will be created to record the adjustment."),
 *   permissions = {
 *     "create observation log",
 *   }
 * )
 */
class Inventory extends QuickFormBase implements QuickFormInterface {

  use QuickLogTrait;
  use QuickFormElementsTrait;

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
    $form['quantity']['measure'] = [
      '#type' => 'select',
      '#title' => $this->t('Measure'),
      '#options' => array_merge(['' => ''], quantity_measure_options()),
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
      '#default_value' => 'reset',
      '#required' => TRUE,
    ];

    // Build list of log type options.
    // Limit to log types the user has access to create.
    $log_access_control_handler = $this->entityTypeManager->getAccessControlHandler('log');
    $log_types = array_filter($this->entityTypeManager->getStorage('log_type')->loadMultiple(), function ($log_type) use ($log_access_control_handler) {
      return $log_access_control_handler->createAccess($log_type->id(), $this->currentUser);
    });
    $log_type_options = array_map(function ($log_type) {
      return $log_type->label();
    }, $log_types);

    // Log type.
    $form['log_type'] = [
      '#type' => 'select',
      '#title' => $this->t('Log type'),
      '#description' => $this->t('Select the type of log to create.'),
      '#options' => $log_type_options,
      '#default_value' => 'observation',
      '#required' => TRUE,
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

    // Done.
    $form['done'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Completed'),
      '#default_value' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    // Load asset.
    $asset = $this->entityTypeManager->getStorage('asset')->load($form_state->getValue('asset'));

    // Load units term (if specified).
    $units = NULL;
    if ($form_state->getValue(['quantity', 'units'])) {
      $units = $this->entityTypeManager->getStorage('taxonomy_term')->load($form_state->getValue(['quantity', 'units']));
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
    $quantity_summary = $quantity['value'];
    if (!is_null($units)) {
      $quantity_summary .= ' ' . $units->label();
    }
    if (!empty($quantity['measure'])) {
      $quantity_summary .= ' (' . $quantity['measure'] . ')';
    }
    switch ($quantity['inventory_adjustment']) {
      case 'increment':
        $log['name'] = $this->t('Increment inventory of @asset by @quantity', ['@asset' => Markup::create($asset->label()), '@quantity' => $quantity_summary]);
        break;

      case 'decrement':
        $log['name'] = $this->t('Decrement inventory of @asset by @quantity', ['@asset' => Markup::create($asset->label()), '@quantity' => $quantity_summary]);
        break;

      case 'reset':
        $log['name'] = $this->t('Reset inventory of @asset to @quantity', ['@asset' => Markup::create($asset->label()), '@quantity' => $quantity_summary]);
        break;
    }

    // Create the log.
    $this->createLog($log);
  }

}
