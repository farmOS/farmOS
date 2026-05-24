<?php

declare(strict_types=1);

namespace Drupal\farm_quick_planting\Plugin\QuickForm;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Markup;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\State\StateInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\farm_form\Traits\FarmFormInlineContainerTrait;
use Drupal\farm_quick\Attribute\QuickForm;
use Drupal\farm_quick\Plugin\QuickForm\QuickFormBase;
use Drupal\farm_quick\Traits\QuickAssetTrait;
use Drupal\farm_quick\Traits\QuickLogTrait;
use Drupal\farm_quick\Traits\QuickStringTrait;
use Drupal\quantity\QuantityHelper;
use Drupal\taxonomy\TermInterface;

/**
 * Planting quick form.
 *
 * @internal
 */
#[QuickForm(
  id: 'planting',
  label: new TranslatableMarkup('Planting'),
  description: new TranslatableMarkup('Record a planting'),
  helpText: new TranslatableMarkup('This form will create a plant asset, along with optional logs to represent seeding date, harvest date, etc.'),
  permissions: [
    'create plant asset',
  ],
)]
class Planting extends QuickFormBase {

  use FarmFormInlineContainerTrait;
  use QuickAssetTrait;
  use QuickLogTrait;
  use QuickStringTrait;

  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    EntityTypeManagerInterface $entity_type_manager,
    AccountInterface $current_user,
    protected ModuleHandlerInterface $moduleHandler,
    protected StateInterface $state,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $entity_type_manager, $current_user);
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    // Load the seasons that were used last time.
    $season_ids = $this->state->get('farm.quick.planting.seasons', []);
    $seasons = $this->entityTypeManager->getStorage('taxonomy_term')->loadMultiple($season_ids);

    // Seasons.
    $form['seasons'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Season'),
      '#description' => $this->t('What season(s) will this be part of? This is used for organizing assets for future reference, and can be something like "@year" or "@year Summer". This will be prepended to the plant asset name.', ['@year' => date('Y')]),
      '#target_type' => 'taxonomy_term',
      '#selection_settings' => [
        'target_bundles' => ['season'],
      ],
      '#autocreate' => [
        'bundle' => 'season',
      ],
      '#tags' => TRUE,
      '#default_value' => $seasons,
      '#required' => TRUE,
    ];

    // Create a container for crops/varieties.
    $form['crops'] = [
      '#type' => 'container',
      '#tree' => TRUE,
      '#attributes' => ['id' => 'plant-crops'],
    ];

    // Create a field for each crop/variety.
    $crop_count = $form_state->getValue('crop_count', 1);
    for ($i = 0; $i < $crop_count; $i++) {
      $counter = $crop_count > 1 ? ' ' . ($i + 1) : '';
      $form['crops'][$i] = [
        '#type' => 'entity_autocomplete',
        '#title' => $this->t('Crop/variety') . $counter,
        '#description' => $this->t("Enter the crop/variety that this is a planting of. As you type, you will have the option of selecting from crops/varieties that you've entered in the past."),
        '#target_type' => 'taxonomy_term',
        '#selection_settings' => [
          'target_bundles' => ['plant_type'],
        ],
        '#autocreate' => [
          'bundle' => 'plant_type',
        ],
        '#required' => TRUE,
      ];
    }

    // Number of crops/varieties.
    $range = range(1, 10);
    $form['crop_count'] = [
      '#type' => 'select',
      '#title' => $this->t('If this is a mix, how many crops/varieties are included?'),
      '#options' => array_combine($range, $range),
      '#default_value' => 1,
      '#ajax' => [
        'callback' => [$this, 'plantCropsCallback'],
        'wrapper' => 'plant-crops',
      ],
    ];

    // Create a set of checkboxes to enable log types, based on enabled modules,
    // and permission to create them.
    $log_type_modules = [
      'farm_seeding' => [
        'log_type' => 'seeding',
        'label' => $this->t('Seeding'),
        'default' => TRUE,
      ],
      'farm_transplanting' => [
        'log_type' => 'transplanting',
        'label' => $this->t('Transplanting'),
      ],
      'farm_harvest' => [
        'log_type' => 'harvest',
        'label' => $this->t('Harvest'),
      ],
    ];
    $log_type_options = [];
    $log_type_defaults = [];
    foreach ($log_type_modules as $module => $option) {
      if ($this->moduleHandler->moduleExists($module) && $this->currentUser->hasPermission('create ' . $option['log_type'] . ' log')) {
        $log_type_options[$option['log_type']] = $option['label'];
        if (!empty($option['default'])) {
          $log_type_defaults[$option['log_type']] = $option['log_type'];
        }
      }
    }
    if (!empty($log_type_options)) {
      $form['log_types'] = [
        '#type' => 'checkboxes',
        '#title' => $this->t('What events would you like to record?'),
        '#options' => $log_type_options,
        '#default_value' => $log_type_defaults,
        '#ajax' => [
          'callback' => [$this, 'plantLogsCallback'],
          'wrapper' => 'plant-logs',
        ],
      ];
    }

    // Create a wrapper for logs.
    $form['logs_wrapper'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'plant-logs'],
    ];

    // Create vertical tabs for logs.
    $form['logs_wrapper']['logs'] = [
      '#type' => 'vertical_tabs',
    ];

    // Add log forms that can be created for this plant asset.
    $enabled_logs = array_filter($form_state->getValue('log_types', $log_type_defaults));
    if (in_array('seeding', $enabled_logs)) {
      $form['seeding'] = [
        '#type' => 'details',
        '#title' => $this->t('Seeding'),
        '#group' => 'logs',
        '#tree' => TRUE,
      ];
      $include_fields = ['date', 'location', 'quantity', 'notes', 'done'];
      $quantity_measures = ['count', 'length', 'weight', 'area', 'volume', 'ratio'];
      $form['seeding'] += $this->buildLogForm('seeding', $include_fields, $quantity_measures);
    }
    if (in_array('transplanting', $enabled_logs)) {
      $form['transplanting'] = [
        '#type' => 'details',
        '#title' => $this->t('Transplanting'),
        '#group' => 'logs',
        '#tree' => TRUE,
      ];
      $include_fields = ['date', 'location', 'quantity', 'notes', 'done'];
      $quantity_measures = ['count', 'length', 'weight', 'area', 'volume', 'ratio'];
      $form['transplanting'] += $this->buildLogForm('transplanting', $include_fields, $quantity_measures);
    }
    if (in_array('harvest', $enabled_logs)) {
      $form['harvest'] = [
        '#type' => 'details',
        '#title' => $this->t('Harvest'),
        '#group' => 'logs',
        '#tree' => TRUE,
      ];
      $include_fields = ['date', 'quantity', 'notes', 'done'];
      $form['harvest'] += $this->buildLogForm('harvest', $include_fields);
    }

    // Plant asset name.
    // Provide a checkbox to allow customizing this. Otherwise it will be
    // automatically generated on submission.
    $form['custom_name'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Customize plant asset name'),
      '#description' => $this->t('The plant asset name will default to "[Season] [Location] [Crop]" but can be customized if desired.'),
      '#default_value' => FALSE,
      '#ajax' => [
        'callback' => [$this, 'plantNameCallback'],
        'wrapper' => 'plant-name',
      ],
    ];
    $form['name_wrapper'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'plant-name'],
    ];
    if ($form_state->getValue('custom_name', FALSE)) {
      $form['name_wrapper']['name'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Plant asset name'),
        '#maxlength' => 255,
        '#default_value' => $this->generatePlantName($form_state),
        '#required' => TRUE,
      ];
    }

    return $form;
  }

  /**
   * Build a simplified log form.
   *
   * @param string $log_type
   *   The log type.
   * @param array $include_fields
   *   Array of fields to include.
   * @param array $quantity_measures
   *   Array of allowed quantity measures.
   *
   * @return array
   *   Returns a Form API array.
   */
  protected function buildLogForm(string $log_type, array $include_fields = [], array $quantity_measures = []) {
    $form = [];

    // Add a hidden value for the log type.
    $form['type'] = [
      '#type' => 'value',
      '#value' => $log_type,
    ];

    // Filter the available quantity measures, if desired.
    $quantity_measure_options = QuantityHelper::quantityMeasureOptions();
    $filtered_quantity_measure_options = $quantity_measure_options;
    if (!empty($quantity_measures)) {
      $filtered_quantity_measure_options = [];
      foreach ($quantity_measures as $measure) {
        if (!empty($quantity_measure_options[$measure])) {
          $filtered_quantity_measure_options[$measure] = $quantity_measure_options[$measure];
        }
      }
    }

    // Create log fields.
    $field_info = [];
    $field_info['date'] = [
      '#type' => 'datetime',
      '#title' => $this->t('Date'),
      '#default_value' => new DrupalDateTime('midnight', $this->currentUser->getTimeZone()),
      '#required' => TRUE,
    ];
    $field_info['done'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Completed'),
    ];
    $field_info['location'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Location'),
      '#description' => $this->t('Where does this take place?'),
      '#target_type' => 'asset',
      '#selection_handler' => 'views',
      '#selection_settings' => [
        'view' => [
          'view_name' => 'farm_location_reference',
          'display_name' => 'entity_reference',
          'arguments' => [],
        ],
        'match_operator' => 'CONTAINS',
      ],
      '#tags' => TRUE,
      '#required' => TRUE,
    ];
    $field_info['quantity'] = $this->buildInlineContainer();
    $field_info['quantity']['value'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Quantity'),
      '#size' => 16,
    ];
    $field_info['quantity']['units'] = [
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
    $field_info['quantity']['measure'] = [
      '#type' => 'select',
      '#title' => $this->t('Measure'),
      '#options' => $filtered_quantity_measure_options,
      '#default_value' => 'weight',
    ];
    $field_info['notes'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Notes'),
      '#format' => 'default',
    ];
    foreach ($include_fields as $field) {
      if (array_key_exists($field, $field_info)) {
        $form[$field] = $field_info[$field];
      }
    }

    return $form;
  }

  /**
   * Generate plant asset name.
   *
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state object.
   *
   * @return string
   *   Returns a plant asset name string.
   */
  protected function generatePlantName(FormStateInterface $form_state) {

    // Get the season names.
    /** @var \Drupal\taxonomy\TermInterface[] $seasons */
    $seasons = $form_state->getValue('seasons') ?? [];
    $season_names = [];
    foreach ($seasons as $season) {
      if (!empty($season['target_id'])) {
        $season = $this->entityTypeManager->getStorage('taxonomy_term')->load($season['target_id']);
      }
      elseif (!empty($season['entity'])) {
        $season = $season['entity'];
      }
      if ($season instanceof TermInterface) {
        $season_names[] = $season->label();
      }
    }

    // Get the crop/variety names.
    $crops = $form_state->getValue('crops') ?? [];
    $crop_names = [];
    foreach ($crops as $crop) {
      if (is_numeric($crop)) {
        $crop = $this->entityTypeManager->getStorage('taxonomy_term')->load($crop);
      }
      elseif (!empty($crop['entity'])) {
        $crop = $crop['entity'];
      }
      if ($crop instanceof TermInterface) {
        $crop_names[] = $crop->label();
      }
    }

    // Get the location name(s).
    // The "final" location of the plant is assumed to be the transplanting
    // location (if the transplanting module is enabled). If a transplanting is
    // not being created, but a seeding is, then use the seeding location.
    $location_keys = [
      ['seeding', 'location'],
      ['transplanting', 'location'],
    ];
    $location_name = '';
    foreach ($location_keys as $key) {
      if ($form_state->hasValue($key)) {
        $location_names = array_map(function ($value) {
          return $this->entityTypeManager->getStorage('asset')->load($value['target_id'])->label();
        }, $form_state->getValue($key));
        $location_name = implode(', ', $location_names);
      }
    }

    // Generate the plant name, giving priority to the seasons and crops.
    $name_parts = [
      'seasons' => implode('/', $season_names),
      'location' => $location_name,
      'crops' => implode(', ', $crop_names),
    ];
    $priority_keys = ['seasons', 'crops'];
    return $this->prioritizedString($name_parts, $priority_keys);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    // If a custom plant name was provided, use that. Otherwise generate one.
    $plant_name = $this->generatePlantName($form_state);
    if (!empty($form_state->getValue('custom_name', FALSE)) && $form_state->hasValue('name')) {
      $plant_name = $form_state->getValue('name');
    }

    // Create a new planting asset.
    $plant_asset = $this->createAsset([
      'type' => 'plant',
      'name' => $plant_name,
      'plant_type' => $form_state->getValue('crops'),
      'season' => $form_state->getValue('seasons'),
    ]);

    // Remember the selected seasons for future reference.
    $season_ids = [];
    foreach ($plant_asset->get('season')->referencedEntities() as $entity) {
      $season_ids[] = $entity->id();
    }
    if (!empty($season_ids)) {
      $this->state->set('farm.quick.planting.seasons', $season_ids);
    }

    // Generate logs.
    $log_types = [
      'seeding',
      'transplanting',
      'harvest',
    ];
    foreach ($log_types as $log_type) {

      // If there are no values for this log type, skip it.
      if (!$form_state->hasValue($log_type)) {
        continue;
      }

      // Get the log values.
      $log_values = $form_state->getValue($log_type);

      // Name the log based on the type and asset.
      switch ($log_type) {
        case 'seeding':
          $log_name = $this->t('Seed @asset', ['@asset' => Markup::create($plant_asset->label())]);
          break;

        case 'transplanting':
          $log_name = $this->t('Transplant @asset', ['@asset' => Markup::create($plant_asset->label())]);
          break;

        case 'harvest':
          $log_name = $this->t('Harvest @asset', ['@asset' => Markup::create($plant_asset->label())]);
          break;
      }

      // If the log is a seeding or transplanting, it is a movement.
      $is_movement = FALSE;
      if (in_array($log_type, ['seeding', 'transplanting'])) {
        $is_movement = TRUE;
      }

      // Set the log status.
      $status = 'pending';
      if (!empty($log_values['done'])) {
        $status = 'done';
      }

      // Create the log.
      $this->createLog([
        'type' => $log_type,
        'name' => $log_name,
        'timestamp' => $log_values['date']->getTimestamp(),
        'asset' => $plant_asset,
        'quantity' => [$this->prepareQuantity($log_values['quantity'])],
        'location' => $log_values['location'] ?? NULL,
        'is_movement' => $is_movement,
        'notes' => $log_values['notes'] ?? NULL,
        'status' => $status,
      ]);
    }
  }

  /**
   * Prepare quantity values for use with createLog() or createQuantity().
   *
   * @param array $values
   *   Quantity field values from the form.
   *
   * @return array|null
   *   Returns an array for createQuantity() or NULL if no quantity value.
   */
  protected function prepareQuantity(array $values) {

    // If there is no value, return NULL.
    if (empty($values['value'])) {
      return NULL;
    }

    // Use the standard quantity type.
    $values['type'] = 'standard';

    // If units is specified, then we need to convert it to units_id, which
    // is expected by createLog() and createQuantity().
    if (!empty($values['units'])) {

      // If units is a numeric value, assume that it is already a term ID.
      // This will be the case when the form value is set programatically
      // (eg: via automated tests).
      if (is_numeric($values['units'])) {
        $values['units_id'] = $values['units'];
        unset($values['units']);
      }

      // Or, if units is an array, and it has either a target_id or entity,
      // translate it to units_id. This will be the case when a term is selected
      // via the UI, when referencing an existing term or creating a new one,
      // respectively.
      elseif (is_array($values['units'])) {

        // If an existing term is selected, target_id will be set.
        if (!empty($values['units']['target_id'])) {
          $values['units_id'] = $values['units']['target_id'];
          unset($values['units']);
        }

        // Or, if a new term is being created, the full entity is available.
        elseif (!empty($values['units']['entity']) && $values['units']['entity'] instanceof TermInterface) {
          $values['units'] = $values['units']['entity'];
        }
      }
    }

    // Return the prepared values.
    return $values;
  }

  /**
   * Ajax callback for crop/variety fields.
   */
  public function plantCropsCallback(array $form, FormStateInterface $form_state) {
    return $form['crops'];
  }

  /**
   * Ajax callback for logs fields.
   */
  public function plantLogsCallback(array $form, FormStateInterface $form_state) {
    return $form['logs_wrapper'];
  }

  /**
   * Ajax callback for plant name field.
   */
  public function plantNameCallback(array $form, FormStateInterface $form_state) {
    return $form['name_wrapper'];
  }

}
