<?php

namespace Drupal\farm_group\Form;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Markup;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Drupal\Core\Url;
use Drupal\log\Entity\Log;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Provides an asset group confirmation form.
 */
class AssetGroupActionForm extends ConfirmFormBase {

  /**
   * The tempstore factory.
   *
   * @var \Drupal\Core\TempStore\SharedTempStore
   */
  protected $tempStore;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $user;

  /**
   * The entity type.
   *
   * @var \Drupal\Core\Entity\EntityTypeInterface
   */
  protected $entityType;

  /**
   * The assets to group.
   *
   * @var \Drupal\Core\Entity\EntityInterface[]
   */
  protected $entities;

  /**
   * Constructs an AssetGroupActionForm form object.
   *
   * @param \Drupal\Core\TempStore\PrivateTempStoreFactory $temp_store_factory
   *   The tempstore factory.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Session\AccountInterface $user
   *   The current user.
   */
  public function __construct(PrivateTempStoreFactory $temp_store_factory, EntityTypeManagerInterface $entity_type_manager, AccountInterface $user) {
    $this->tempStore = $temp_store_factory->get('asset_group_confirm');
    $this->entityTypeManager = $entity_type_manager;
    $this->user = $user;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('tempstore.private'),
      $container->get('entity_type.manager'),
      $container->get('current_user')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'asset_group_action_confirm_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->formatPlural(count($this->entities), 'Are you sure you want to group this @item?', 'Are you sure you want to group these @items?', [
      '@item' => $this->entityType->getSingularLabel(),
      '@items' => $this->entityType->getPluralLabel(),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    if ($this->entityType->hasLinkTemplate('collection')) {
      return new Url('entity.' . $this->entityType->id() . '.collection');
    }
    else {
      return new Url('<front>');
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return '';
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Group');
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $this->entityType = $this->entityTypeManager->getDefinition('asset');
    $this->entities = $this->tempStore->get($this->user->id());
    if (empty($this->entityType) || empty($this->entities)) {
      return new RedirectResponse($this->getCancelUrl()
        ->setAbsolute()
        ->toString());
    }

    $form['date'] = [
      '#type' => 'datetime',
      '#title' => $this->t('Date'),
      '#default_value' => new DrupalDateTime('midnight', $this->user->getTimeZone()),
      '#required' => TRUE,
    ];

    $form['group'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Group'),
      '#description' => $this->t('The groups to assign the asset to. Leave blank to un-assign an asset from groups.'),
      '#target_type' => 'asset',
      '#selection_handler' => 'views',
      '#selection_settings' => [
        'view' => [
          'view_name' => 'farm_group_reference',
          'display_name' => 'entity_reference',
        ],
        'match_operator' => 'CONTAINS',
        'match_limit' => 10,
      ],
      '#tags' => TRUE,
      '#validate_reference' => FALSE,
      '#maxlength' => 1024,
    ];

    $form['done'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('This membership change has taken place (mark the log as done)'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    // Filter out entities the user doesn't have access to.
    $inaccessible_entities = [];
    $accessible_entities = [];
    foreach ($this->entities as $entity) {
      if (!$entity->access('update', $this->currentUser())) {
        $inaccessible_entities[] = $entity;
        continue;
      }
      $accessible_entities[] = $entity;
    }

    // Create an observation log to group the assets.
    if ($form_state->getValue('confirm') && !empty($accessible_entities)) {

      // Load group assets.
      $groups = [];
      $group_ids = array_column($form_state->getValue('group', []) ?? [], 'target_id');
      if (!empty($group_ids)) {
        $groups = $this->entityTypeManager->getStorage('asset')->loadMultiple($group_ids);
      }

      $done = (bool) $form_state->getValue('done', FALSE);

      // Generate a name for the log.
      $asset_names = farm_log_asset_names_summary($accessible_entities);
      $group_names = farm_log_asset_names_summary($groups);
      $log_name = $this->t('Clear group membership of @assets', ['@assets' => Markup::create($asset_names)]);
      if (!empty($group_names)) {
        $log_name = $this->t('Group @assets into @groups', ['@assets' => Markup::create($asset_names), '@groups' => Markup::create($group_names)]);
      }

      // Create the log.
      $log = Log::create([
        'name' => $log_name,
        'type' => 'observation',
        'timestamp' => $form_state->getValue('date')->getTimestamp(),
        'asset' => $accessible_entities,
        'is_group_assignment' => TRUE,
        'group' => $groups,
      ]);

      // Mark as done.
      if ($done !== FALSE) {
        $log->get('status')->first()->applyTransitionById('done');
      }

      // Validate the log before saving.
      $violations = $log->validate();
      if ($violations->count() > 0) {
        $this->messenger()->addWarning(
          $this->t('Could not group assets: @bundle @entity_type validation failed.',
            [
              '@bundle' => $log->getBundleLabel(),
              '@entity_type' => $log->getEntityType()->getSingularLabel(),
            ],
          ),
        );
        $this->tempStore->delete($this->currentUser()->id());
        $form_state->setRedirectUrl($this->getCancelUrl());
        return;
      }

      $log->save();
      $this->messenger()->addMessage($this->t('Log created: <a href=":uri">%log_label</a>', [':uri' => $log->toUrl()->toString(), '%log_label' => $log->label()]));
    }

    // Add warning message for inaccessible entities.
    if (!empty($inaccessible_entities)) {
      $inaccessible_count = count($inaccessible_entities);
      $this->messenger()->addWarning($this->formatPlural($inaccessible_count, 'Could not group @count @item because you do not have the necessary permissions.', 'Could not group @count @items because you do not have the necessary permissions.', [
        '@item' => $this->entityType->getSingularLabel(),
        '@items' => $this->entityType->getPluralLabel(),
      ]));
    }

    $this->tempStore->delete($this->currentUser()->id());
    $form_state->setRedirectUrl($this->getCancelUrl());
  }

}
