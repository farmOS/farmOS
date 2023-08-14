<?php

namespace Drupal\farm_quick_birth\Plugin\QuickForm;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Render\Markup;
use Drupal\Core\Session\AccountInterface;
use Drupal\farm_group\GroupMembershipInterface;
use Drupal\farm_location\AssetLocationInterface;
use Drupal\farm_quick\Plugin\QuickForm\QuickFormBase;
use Drupal\farm_quick\Traits\QuickAssetTrait;
use Drupal\farm_quick\Traits\QuickLogTrait;
use Drupal\farm_quick\Traits\QuickStringTrait;
use Psr\Container\ContainerInterface;

/**
 * Birth quick form.
 *
 * @QuickForm(
 *   id = "birth",
 *   label = @Translation("Birth"),
 *   description = @Translation("Record an animal birth."),
 *   helpText = @Translation("Use this form to record the birth of one or more animals. A new birth log will be created, along with the new child animal asset records."),
 *   permissions = {
 *     "create animal asset",
 *     "create birth log",
 *     "create observation log",
 *   }
 * )
 */
class Birth extends QuickFormBase {

  use QuickAssetTrait;
  use QuickLogTrait;
  use QuickStringTrait;

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * The config factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Asset location service.
   *
   * @var \Drupal\farm_location\AssetLocationInterface
   */
  protected $assetLocation;

  /**
   * Current user object.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * Group membership service.
   *
   * @var \Drupal\farm_group\GroupMembershipInterface|null
   */
  protected $groupMembership = NULL;

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
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory service.
   * @param \Drupal\farm_location\AssetLocationInterface $asset_location
   *   Asset location service.
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   Current user object.
   * @param \Drupal\farm_group\GroupMembershipInterface|null $group_membership
   *   Group membership service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, MessengerInterface $messenger, EntityTypeManagerInterface $entity_type_manager, ModuleHandlerInterface $module_handler, ConfigFactoryInterface $config_factory, AssetLocationInterface $asset_location, AccountInterface $current_user, ?GroupMembershipInterface $group_membership = NULL) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $messenger);
    $this->entityTypeManager = $entity_type_manager;
    $this->moduleHandler = $module_handler;
    $this->configFactory = $config_factory;
    $this->assetLocation = $asset_location;
    $this->currentUser = $current_user;
    $this->groupMembership = $group_membership;
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
      $container->get('config.factory'),
      $container->get('asset.location'),
      $container->get('current_user'),
      $container->has('group.membership') ? $container->get('group.membership') : NULL,
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    // Date of birth.
    $form['date'] = [
      '#type' => 'datetime',
      '#title' => $this->t('Date of birth'),
      '#default_value' => new DrupalDateTime('midnight', $this->currentUser->getTimeZone()),
      '#required' => TRUE,
    ];

    // Number of children.
    $range = range(1, 15);
    $form['child_count'] = [
      '#type' => 'select',
      '#title' => $this->t('How many children were born?'),
      '#options' => array_combine($range, $range),
      '#default_value' => 1,
      '#ajax' => [
        'callback' => [$this, 'childrenCallback'],
        'wrapper' => 'children',
      ],
    ];

    // Create a container for children.
    $form['children'] = [
      '#type' => 'container',
      '#tree' => TRUE,
      '#attributes' => ['id' => 'children'],
    ];

    // Create a fieldset for each child.
    $child_count = $form_state->getValue('child_count', 1);
    for ($i = 0; $i < $child_count; $i++) {
      $counter = ' ' . ($i + 1);
      $form['children'][$i] = [
        '#type' => 'details',
        '#title' => $this->t('Child') . $counter,
        '#open' => $i == 0,
      ];

      // Child name.
      $form['children'][$i]['name'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Name'),
        '#description' => $this->t('Give the animal a name (and/or tag ID below). If the name is left blank, then it will be copied from the tag ID.'),
      ];

      // Child ID tag.
      $form['children'][$i]['tag'] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => ['inline-container'],
        ],
      ];
      $form['children'][$i]['tag']['type'] = [
        '#type' => 'select',
        '#title' => $this->t('Tag type'),
        '#options' => [NULL => ''] + farm_id_tag_type_options('animal'),
      ];
      $form['children'][$i]['tag']['id'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Tag ID'),
        '#size' => 16,
      ];
      $form['children'][$i]['tag']['location'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Tag location'),
        '#size' => 16,
      ];

      // Male or female.
      $form['children'][$i]['sex'] = [
        '#type' => 'radios',
        '#title' => $this->t('Sex'),
        '#options' => [
          'F' => $this->t('Female'),
          'M' => $this->t('Male'),
        ],
      ];

      // Birth weight (metric: kg / us: lbs)
      $units = $this->birthWeightUnits();
      $form['children'][$i]['weight'] = [
        '#type' => 'number',
        '#title' => $this->t('Birth weight (@units)', ['@units' => $units]),
        '#description' => $this->t('This will create a birth weight observation log associated with the child.'),
        '#min' => 0,
        '#step' => 0.01,
      ];

      // Notes.
      $form['children'][$i]['notes'] = [
        '#type' => 'text_format',
        '#title' => $this->t('Notes about this child'),
        '#format' => 'default',
      ];

      // Survived.
      $form['children'][$i]['survived'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Survived birth'),
        '#description' => $this->t('Uncheck this if the child did not survive. The child animal record will still be created, but will be immediately archived.'),
        '#default_value' => TRUE,
      ];
    }

    // Create vertical tabs.
    $form['tabs'] = [
      '#type' => 'vertical_tabs',
    ];

    // Create lineage tab.
    $form['lineage'] = [
      '#type' => 'details',
      '#title' => $this->t('Lineage'),
      '#group' => 'tabs',
    ];

    // Birth mother.
    $form['lineage']['birth_mother'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Birth mother'),
      '#description' => $this->t('This is the mother giving birth. She will be referenced on the Birth log that is created.'),
      '#target_type' => 'asset',
      '#selection_settings' => [
        'target_bundles' => ['animal'],
        'sort' => [
          'field' => 'status',
          'direction' => 'ASC',
        ],
      ],
    ];

    // Genetic mother.
    $form['lineage']['genetic_mother'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Genetic mother'),
      '#description' => $this->t("If the genetic mother is different from the birth mother, she can be referenced here for lineage tracking. Otherwise, it will be assumed that the birth mother is the genetic mother. This will be referenced as the child's parent."),
      '#target_type' => 'asset',
      '#selection_settings' => [
        'target_bundles' => ['animal'],
        'sort' => [
          'field' => 'status',
          'direction' => 'ASC',
        ],
      ],
    ];

    // Genetic father.
    $form['lineage']['genetic_father'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Genetic father'),
      '#description' => $this->t("This will be referenced as the child's parent."),
      '#target_type' => 'asset',
      '#selection_settings' => [
        'target_bundles' => ['animal'],
        'sort' => [
          'field' => 'status',
          'direction' => 'ASC',
        ],
      ],
    ];

    // If the group module is enabled, add an entity autocomplete field for
    // assigning the children to a group.
    if ($this->moduleHandler->moduleExists('farm_group')) {
      $form['group'] = [
        '#type' => 'details',
        '#title' => $this->t('Group'),
        '#group' => 'tabs',
      ];
      $form['group']['group'] = [
        '#type' => 'entity_autocomplete',
        '#title' => $this->t('Assign to group'),
        '#description' => $this->t('This will make each child a member of the selected group.'),
        '#target_type' => 'asset',
        '#selection_settings' => [
          'target_bundles' => ['group'],
          'sort' => [
            'field' => 'status',
            'direction' => 'ASC',
          ],
        ],
      ];
    }

    // Birth notes.
    $form['notes'] = [
      '#type' => 'details',
      '#title' => $this->t('Notes'),
      '#group' => 'tabs',
    ];
    $form['notes']['notes'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Notes about the overall birth process'),
      '#format' => 'default',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    // Iterate over the children.
    foreach ($form_state->getValue('children') as $delta => $child) {

      // Each child must have a name or tag ID.
      if (empty($child['name']) && empty($child['tag']['id'])) {
        $form_state->setError($form['children'][$delta]['name'], $this->t('The child must have a name or tag ID.'));
      }
    }

    // A mother (either birth or genetic) must be selected.
    if (empty($form_state->getValue('birth_mother')) && empty($form_state->getValue('genetic_mother'))) {
      $form_state->setError($form['lineage']['birth_mother'], $this->t('A mother animal must be selected.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    // Get the birthdate.
    /** @var \Drupal\Core\Datetime\DrupalDateTime $birthdate */
    $birthdate = $form_state->getValue('date');

    // Load the mother and father asset(s).
    /** @var \Drupal\asset\Entity\AssetInterface|null $birth_mother */
    $birth_mother = NULL;
    if ($form_state->getValue('birth_mother')) {
      $birth_mother = $this->entityTypeManager->getStorage('asset')->load($form_state->getValue('birth_mother'));
    }
    /** @var \Drupal\asset\Entity\AssetInterface|null $genetic_mother */
    $genetic_mother = NULL;
    if ($form_state->getValue('genetic_mother')) {
      $genetic_mother = $this->entityTypeManager->getStorage('asset')->load($form_state->getValue('genetic_mother'));
    }
    /** @var \Drupal\asset\Entity\AssetInterface|null $genetic_father */
    $genetic_father = NULL;
    if ($form_state->getValue('genetic_father')) {
      $genetic_father = $this->entityTypeManager->getStorage('asset')->load($form_state->getValue('genetic_father'));
    }

    // If there is no birth mother, assume that the genetic mother is the birth
    // mother. Likewise, if there is no genetic mother, assume that the birth
    // mother is the genetic mother. We validate that one of them must exist
    // above.
    if (empty($birth_mother)) {
      $birth_mother = $genetic_mother;
    }
    if (empty($genetic_mother)) {
      $genetic_mother = $birth_mother;
    }

    // Assemble the list of genetic parents.
    $parents = [$genetic_mother];
    if (!empty($genetic_father)) {
      $parents[] = $genetic_father;
    }

    // Iterate over the children and create an asset for each.
    $children = [];
    foreach ($form_state->getValue('children') as $child) {

      // Draft a new animal asset for the child.
      $asset_values = [
        'type' => 'animal',
        'name' => !empty($child['name']) ? $child['name'] : $child['tag']['id'],
        'animal_type' => $genetic_mother->get('animal_type')->referencedEntities(),
        'parent' => $parents,
        'birthdate' => $birthdate->getTimestamp(),
        'status' => !empty($child['survived']) ? 'active' : 'archived',
      ];

      // Set the sex, if available.
      if (!empty($child['sex'])) {
        $asset_values['sex'] = $child['sex'];
      }

      // Set the ID tag, if available.
      if (!empty($child['tag']['type']) || !empty($child['tag']['id']) || !empty($child['tag']['location'])) {
        $asset_values['id_tag'] = [
          [
            'type' => $child['tag']['type'],
            'id' => $child['tag']['id'],
            'location' => $child['tag']['location'],
          ],
        ];
      }

      // Set the child notes, if available.
      if (!empty($child['notes']['value'])) {
        $asset_values['notes'] = $child['notes'];
      }

      // Create the child animal asset and add it to the list.
      $asset = $this->createAsset($asset_values);
      $children[] = $asset;

      // If a birth weight was specified, create a weight observation log.
      if (!empty($child['weight'])) {
        $this->createLog([
          'type' => 'observation',
          'timestamp' => $birthdate->getTimestamp(),
          'name' => $this->t('Weight of @asset is @weight @units', ['@asset' => Markup::create($asset->label()), '@weight' => $child['weight'], '@units' => $this->birthWeightUnits()]),
          'asset' => [$asset],
          'quantity' => [
            [
              'type' => 'standard',
              'measure' => 'weight',
              'value' => $child['weight'],
              'units' => $this->birthWeightUnits(),
            ],
          ],
          'status' => 'done',
        ]);
      }
    }

    // Draft birth log values.
    $birth_log_values = [
      'type' => 'birth',
      'timestamp' => $birthdate->getTimestamp(),
      'asset' => $children,
      'mother' => [$birth_mother],
      'notes' => $form_state->getValue('notes'),
      'status' => 'done',
    ];

    // Generate the birth log name.
    $birth_log_values['name'] = $this->t('Birth: @children', ['@children' => Markup::create($this->entityLabelsSummary($children))]);

    // If the birth mother has a location (at the time of birth), use the birth
    // log to set the location of the children.
    $location = $this->assetLocation->getLocation($birth_mother, $birthdate->getTimestamp());
    if ($location) {
      $birth_log_values['location'] = $location;
      $birth_log_values['is_movement'] = TRUE;
    }

    // If the group module is enabled, check to see if a group was selected, or
    // if the birth mother is in a group (at the time of the birth), make the
    // log into a group assignment log that references the group.
    if ($this->moduleHandler->moduleExists('farm_group')) {
      $group = $form_state->getValue('group');
      if (!empty($group)) {
        $group = [$this->entityTypeManager->getStorage('asset')->load($group)];
      }
      if (empty($group) && $this->groupMembership !== NULL) {
        $group = $this->groupMembership->getGroup($birth_mother, $birthdate->getTimestamp());
      }
      if (!empty($group)) {
        $birth_log_values['group'] = $group;
        $birth_log_values['is_group_assignment'] = TRUE;
      }
    }

    // Save the birth log.
    $this->createLog($birth_log_values);
  }

  /**
   * Ajax callback for children fields.
   */
  public function childrenCallback(array $form, FormStateInterface $form_state) {
    return $form['children'];
  }

  /**
   * Helper function for getting the birth weight units.
   *
   * @return string
   *   The units name, depending on the system of measurement.
   */
  protected function birthWeightUnits() {
    $quantity_settings = $this->configFactory->get('quantity.settings');
    if ($quantity_settings->get('system_of_measurement') == 'us') {
      return 'lbs';
    }
    return 'kg';
  }

}
