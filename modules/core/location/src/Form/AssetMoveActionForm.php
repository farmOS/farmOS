<?php

namespace Drupal\farm_location\Form;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Drupal\Core\Url;
use Drupal\log\Entity\Log;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Provides an asset move confirmation form.
 */
class AssetMoveActionForm extends ConfirmFormBase {

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
   * Constructs an AssetMoveActionForm form object.
   *
   * @param \Drupal\Core\TempStore\PrivateTempStoreFactory $temp_store_factory
   *   The tempstore factory.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Session\AccountInterface $user
   *   The current user.
   */
  public function __construct(PrivateTempStoreFactory $temp_store_factory, EntityTypeManagerInterface $entity_type_manager, AccountInterface $user) {
    $this->tempStore = $temp_store_factory->get('asset_move_confirm');
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
    return 'asset_move_action_confirm_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->formatPlural(count($this->entities), 'Are you sure you want to move this @item?', 'Are you sure you want to move these @items?', [
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
    return $this->t('Move');
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

    $form['location'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Location'),
      '#target_type' => 'asset',
      '#selection_handler' => 'views',
      '#selection_settings' => [
        'view' => [
          'view_name' => 'farm_location_reference',
          'display_name' => 'entity_reference',
        ],
        'match_operator' => 'CONTAINS',
        'match_limit' => 10,
      ],
      '#tags' => TRUE,
      '#validate_reference' => FALSE,
      '#maxlength' => 1024,
      '#required' => TRUE,
    ];

    $form['done'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('This movement has taken place (mark the log as done)'),
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

    // Create an activity log to move the assets.
    if ($form_state->getValue('confirm') && !empty($accessible_entities)) {

      // Load location assets.
      $locations = [];
      $location_ids = array_column($form_state->getValue('location', []), 'target_id');
      if (!empty($location_ids)) {
        $locations = $this->entityTypeManager->getStorage('asset')->loadMultiple($location_ids);
      }

      /** @var \Drupal\Core\Datetime\DrupalDateTime $date */
      $date = $form_state->getValue('date');
      $done = (bool) $form_state->getValue('done', FALSE);

      // Create the log.
      // @todo Populate the log name with a summary helper function.
      $log = Log::create([
        'type' => 'activity',
        'timestamp' => $date->getTimestamp(),
        'asset' => $accessible_entities,
        'is_movement' => TRUE,
        'location' => $locations,
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
      $this->messenger()->addWarning($this->formatPlural($inaccessible_count, 'Could not move @count @item because you do not have the necessary permissions.', 'Could not move @count @items because you do not have the necessary permissions.', [
        '@item' => $this->entityType->getSingularLabel(),
        '@items' => $this->entityType->getPluralLabel(),
      ]));
    }

    $this->tempStore->delete($this->currentUser()->id());
    $form_state->setRedirectUrl($this->getCancelUrl());
  }

}
