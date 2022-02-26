<?php

namespace Drupal\farm_quick_weight\Plugin\QuickForm;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\State\StateInterface;
use Drupal\farm_quick\Plugin\QuickForm\QuickFormBase;
use Drupal\farm_quick\Traits\QuickAssetTrait;
use Drupal\farm_quick\Traits\QuickLogTrait;
use Drupal\farm_quick\Traits\QuickQuantityTrait;
use Drupal\farm_quick\Traits\QuickStringTrait;
use Drupal\taxonomy\TermInterface;
use Psr\Container\ContainerInterface;

/**
 * Weight quick form.
 *
 * @QuickForm(
 *   id = "weight",
 *   label = @Translation("Weights"),
 *   description = @Translation("Record animal weights."),
 *   helpText = @Translation("This form will create animal weight logs"),
 *   permissions = {
 *     "create observation log",
 *   }
 * )
 */

class Weight extends QuickFormBase{

    use QuickAssetTrait;
    use QuickLogTrait;
    use QuickQuantityTrait;
    use QuickStringTrait;

    /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  

  /**
   * The state service.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $state;
  /**
   * The module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * The time service.
   *
   * @var \Drupal\Component\Datetime\TimeInterface
   */
  protected $time;

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
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   * @param \Drupal\Component\Datetime\TimeInterface $time
   *   The time service.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   * @param \Drupal\Core\State\StateInterface $state
   *   The state service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, MessengerInterface $messenger, EntityTypeManagerInterface $entity_type_manager, ModuleHandlerInterface $module_handler,  StateInterface $state, TimeInterface $time) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $messenger);
    $this->moduleHandler = $module_handler;
    $this->entityTypeManager = $entity_type_manager;
    $this->state = $state;
    $this->time = $time;
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
      $container->get('module_handler'),
      $container->get('state'),
      $container->get('datetime.time'),

    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    //Get the units from storage
    $unit_id = $this->state->get('farm_quick_weight.unit');
    $unit = $this->entityTypeManager->getStorage('taxonomy_term')->load($unit_id);

    // Date of weighing.
    $form['date'] = [
      '#type' => 'date',
      '#title' => $this->t('Date'),
      '#date_year_range' => '-10:+3',
      '#default_value' => date('Y-m-d', $this->time->getRequestTime()),
      '#required' => TRUE,
    ];

    // Animal being weighed.
    $form['animal'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Animal'),
      '#target_type' => 'asset',
      '#target_bundle' => 'animal',
      '#required' => TRUE,
    ];

    // Weight.
    $form['weight'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Weight'),
      '#size' => 10,
      '#required' => TRUE,
    ];

    // Weight unit.
    $form['unit'] = [
        '#type' => 'entity_autocomplete',
        '#title' => $this->t('Units'),
        '#target_type' => 'taxonomy_term',
        '#selection_settings' => [
            'target_bundles' => ['unit'],
        ],
        '#autocreate' => [
          'bundle' => 'unit',
        ],
        '#default_value' => $unit,

    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {


    $log_type = "observation";  // Log type.

    // Get the form values.
    $date = $form_state->getValue('date');
    
    $weight = $form_state->getValue('weight');
    $unit = $form_state->getValue('unit');
    $animal = $form_state->getValue('animal');

    // Get the entities that belong to the form values.
    $unit_entity = $this->entityTypeManager->getStorage('taxonomy_term')->load($unit);
    $this->state->set('farm_quick_weight.unit', $unit_entity->id());
    $asset = $this->entityTypeManager->getStorage('asset')->load($animal);

    //Create the log name from the animal asset, weight and unit
    $log_name = $this->t("Weight of @asset is @weight @unit", [
      '@asset' => $asset->label(),
      '@weight' => $weight,
      '@unit' => $unit_entity->label(),
    ]);

    // Create the log.
    $this ->createLog([
      'type' => $log_type,
      'name' => $log_name,
      'timestamp' => strtotime($date),
      'asset' => $asset,
      'quantity' => [
          [
              'measure' => 'weight',
              'value' => $weight,
              'unit' => $unit_entity,
          ],
      ],
      'status' => 'done',
  ]);
  }

}