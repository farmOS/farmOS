<?php

namespace Drupal\farm_group\Form;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Drupal\Core\Url;
use Drupal\farm_group\GroupMembershipInterface;
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
   * The group membership service.
   *
   * @var \Drupal\farm_group\GroupMembershipInterface
   */
  protected $groupMembership;

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
   * @param \Drupal\farm_group\GroupMembershipInterface $group_membership
   *   The group membership service.
   * @param \Drupal\Core\Session\AccountInterface $user
   *   The current user.
   */
  public function __construct(PrivateTempStoreFactory $temp_store_factory, EntityTypeManagerInterface $entity_type_manager, GroupMembershipInterface $group_membership, AccountInterface $user) {
    $this->tempStore = $temp_store_factory->get('asset_group_confirm');
    $this->entityTypeManager = $entity_type_manager;
    $this->groupMembership = $group_membership;
    $this->user = $user;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('tempstore.private'),
      $container->get('entity_type.manager'),
      $container->get('group.membership'),
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
      '#type' => 'datelist',
      '#title' => $this->t('Date'),
      '#default_value' => new DrupalDateTime(),
      '#date_part_order' => ['month', 'day', 'year'],
      '#required' => TRUE,
      '#date_year_range' => '1902:2037',
    ];

    $form['group'] = [
      '#type' => 'select',
      '#title' => $this->t('Group'),
      '#description' => $this->t('The groups to assign the asset to. Leave blank to un-assign an asset from groups.'),
      '#options' => $this->groupMembership->groupOptions(),
      '#multiple' => TRUE,
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
      $group_ids = $form_state->getValue('group');
      if (!empty($group_ids)) {
        $groups = $this->entityTypeManager->getStorage('asset')->loadMultiple($group_ids);
      }

      /** @var \Drupal\Core\Datetime\DrupalDateTime $date */
      $date = $form_state->getValue('date');
      $done = (bool) $form_state->getValue('done', FALSE);

      // Create the log.
      // @todo Populate the log name with a summary helper function.
      $log = Log::create([
        'type' => 'observation',
        'timestamp' => $date->getTimestamp(),
        'asset' => $accessible_entities,
        'is_group_assignment' => TRUE,
        'group' => $groups,
      ]);

      // Mark as done.
      if ($done !== FALSE) {
        $log->get('status')->first()->applyTransitionById('done');
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
