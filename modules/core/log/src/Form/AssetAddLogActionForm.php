<?php

namespace Drupal\farm_log\Form;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Provides an asset add log confirmation form.
 */
class AssetAddLogActionForm extends ConfirmFormBase {

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
   * The assets to create logs for.
   *
   * @var \Drupal\Core\Entity\EntityInterface[]
   */
  protected $entities;

  /**
   * Constructs an AssetAddLogActionForm form object.
   *
   * @param \Drupal\Core\TempStore\PrivateTempStoreFactory $temp_store_factory
   *   The tempstore factory.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Session\AccountInterface $user
   *   The current user.
   */
  public function __construct(PrivateTempStoreFactory $temp_store_factory, EntityTypeManagerInterface $entity_type_manager, AccountInterface $user) {
    $this->tempStore = $temp_store_factory->get('asset_add_log_confirm');
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
    return 'asset_add_log_action_confirm_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->formatPlural(count($this->entities), 'Are you sure you want to add a log referencing this @item?', 'Are you sure you want to add a log referencing these @items?', [
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
    return $this->t('Continue');
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

    // Build list of log type options.
    // Limit to log types the user has access to create.
    $log_access_control_handler = $this->entityTypeManager->getAccessControlHandler('log');
    $log_types = array_filter($this->entityTypeManager->getStorage('log_type')->loadMultiple(), function ($log_type) use ($log_access_control_handler) {
      return $log_access_control_handler->createAccess($log_type->id(), $this->currentUser());
    });
    $log_type_options = array_map(function ($log_type) {
      return $log_type->label();
    }, $log_types);

    $form['log_type'] = [
      '#type' => 'select',
      '#title' => $this->t('Log type'),
      '#description' => $this->t('Select the type of log to create.'),
      '#options' => $log_type_options,
      '#required' => TRUE,
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
      if (!$entity->access('view', $this->currentUser())) {
        $inaccessible_entities[] = $entity;
        continue;
      }
      $accessible_entities[] = $entity;
    }

    // Default redirect url.
    $redirect_url = $this->getCancelUrl();
    if (!empty($form_state->getValue('confirm')) && !empty($accessible_entities)) {

      $log_type = $form_state->getValue('log_type');
      if (!empty($log_type)) {

        // If a destination query param is set, save it and remove it.
        // First we need to redirect to the /log/add/{log_type} form.
        $destination = $this->getCancelUrl()->setAbsolute()->toString();
        if ($this->getRequest()->query->has('destination')) {
          $destination = $this->getRequest()->query->get('destination');
          $this->getRequest()->query->remove('destination');
        }

        // Build list of asset ids.
        $asset_ids = array_map(function ($asset) {
          return $asset->id();
        }, $accessible_entities);

        // Build query params to include in the redirect.
        $query_params = [
          'destination' => $destination,
          'asset' => $asset_ids,
        ];
        $redirect_url = Url::fromRoute('entity.log.add_form', ['log_type' => $log_type], ['query' => $query_params]);
      }
    }

    $this->tempStore->delete($this->currentUser()->id());
    $form_state->setRedirectUrl($redirect_url);
  }

}
