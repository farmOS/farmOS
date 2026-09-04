<?php

declare(strict_types=1);

namespace Drupal\farm_quick_group\Plugin\QuickForm;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Markup;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\asset\Entity\AssetInterface;
use Drupal\farm_form\Traits\FarmFormInlineContainerTrait;
use Drupal\farm_group\GroupMembershipInterface;
use Drupal\farm_quick\Attribute\QuickForm;
use Drupal\farm_quick\Plugin\QuickForm\QuickFormBase;
use Drupal\farm_quick\Plugin\QuickForm\QuickFormInterface;
use Drupal\farm_quick\Traits\QuickLogTrait;
use Drupal\farm_quick\Traits\QuickPrepopulateTrait;
use Drupal\farm_quick\Traits\QuickStringTrait;

/**
 * Group quick form.
 */
#[QuickForm(
  id: 'group',
  label: new TranslatableMarkup('Group membership'),
  description: new TranslatableMarkup('Record asset group membership changes.'),
  helpText: new TranslatableMarkup('Use this form to assign assets to a group. A new observation log will be created to record the group membership change.'),
  permissions: [
    'create observation log',
  ],
)]
class Group extends QuickFormBase implements QuickFormInterface {

  use FarmFormInlineContainerTrait;
  use QuickLogTrait;
  use QuickPrepopulateTrait;
  use QuickStringTrait;

  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    EntityTypeManagerInterface $entity_type_manager,
    AccountInterface $current_user,
    protected GroupMembershipInterface $groupMembership,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $entity_type_manager, $current_user);
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, ?string $id = NULL) {

    // Date.
    $form['date'] = [
      '#type' => 'datetime',
      '#title' => $this->t('Date'),
      '#default_value' => new DrupalDateTime('midnight', $this->currentUser->getTimeZone()),
      '#required' => TRUE,
    ];

    // Assets.
    $prepopulated_assets = $this->getPrepopulatedEntities('asset', $form_state);
    $form['asset'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Assets'),
      '#description' => $this->t('Which assets are changing group membership?'),
      '#target_type' => 'asset',
      '#selection_settings' => [
        'sort' => [
          'field' => 'archived',
          'direction' => 'DESC',
        ],
      ],
      '#maxlength' => 1024,
      '#tags' => TRUE,
      '#required' => TRUE,
      '#default_value' => $prepopulated_assets,
    ];

    // Groups.
    $form['group'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Groups'),
      '#description' => $this->t('The groups to assign the assets to. Leave blank to un-assign assets from all groups.'),
      '#target_type' => 'asset',
      '#selection_handler' => 'views',
      '#selection_settings' => [
        'view' => [
          'view_name' => 'farm_group_reference',
          'display_name' => 'entity_reference',
          'arguments' => [],
        ],
        'match_operator' => 'CONTAINS',
      ],
      '#maxlength' => 1024,
      '#tags' => TRUE,
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

    // Draft a group membership observation log from the user-submitted data.
    $timestamp = $form_state->getValue('date')->getTimestamp();
    $status = $form_state->getValue('done') ? 'done' : 'pending';
    $log = [
      'type' => 'observation',
      'timestamp' => $timestamp,
      'asset' => $form_state->getValue('asset'),
      'group' => $form_state->getValue('group'),
      'notes' => $form_state->getValue('notes'),
      'status' => $status,
      'is_group_assignment' => TRUE,
    ];

    // Load assets and groups.
    $assets = $this->loadEntityAutocompleteAssets($form_state->getValue('asset'));
    $groups = $this->loadEntityAutocompleteAssets($form_state->getValue('group'));

    // Generate a name for the log.
    $asset_names = $this->entityLabelsSummary($assets);
    $group_names = $this->entityLabelsSummary($groups);
    $log['name'] = $this->t('Clear group membership of @assets', ['@assets' => Markup::create($asset_names)]);
    if (!empty($group_names)) {
      $log['name'] = $this->t('Group @assets into @groups', ['@assets' => Markup::create($asset_names), '@groups' => Markup::create($group_names)]);
    }

    // Create the log.
    $this->createLog($log);
  }

  /**
   * Load assets from entity_autocomplete values.
   *
   * @param array|null $values
   *   The value from $form_state->getValue().
   *
   * @return \Drupal\asset\Entity\AssetInterface[]
   *   Returns an array of assets.
   */
  protected function loadEntityAutocompleteAssets($values) {
    $entities = [];
    if (empty($values)) {
      return $entities;
    }
    foreach ($values as $value) {
      if (is_array($value) && !empty($value['target_id'])) {
        $value = $this->entityTypeManager->getStorage('asset')->load($value['target_id']);
      }
      if ($value instanceof AssetInterface) {
        $entities[] = $value;
      }
    }
    return $entities;
  }

}
