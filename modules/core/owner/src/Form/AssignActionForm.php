<?php

namespace Drupal\farm_owner\Form;

use Drupal\Component\Plugin\Exception\PluginException;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Drupal\Core\Url;
use Drupal\farm_role\ManagedRolePermissionsManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Provides an assign confirmation form.
 */
class AssignActionForm extends ConfirmFormBase {

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
   * The managed role permissions manager.
   *
   * @var \Drupal\farm_role\ManagedRolePermissionsManagerInterface
   */
  protected $managedRolePermissionsManager;

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
   * The entities to assign.
   *
   * @var \Drupal\Core\Entity\EntityInterface[]
   */
  protected $entities;

  /**
   * Constructs an AssignActionForm form object.
   *
   * @param \Drupal\Core\TempStore\PrivateTempStoreFactory $temp_store_factory
   *   The tempstore factory.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\farm_role\ManagedRolePermissionsManagerInterface $managed_role_permissions_manager
   *   The managed role permissions manager.
   * @param \Drupal\Core\Session\AccountInterface $user
   *   The current user.
   */
  public function __construct(PrivateTempStoreFactory $temp_store_factory, EntityTypeManagerInterface $entity_type_manager, ManagedRolePermissionsManagerInterface $managed_role_permissions_manager, AccountInterface $user) {
    $this->tempStore = $temp_store_factory->get('entity_assign_confirm');
    $this->entityTypeManager = $entity_type_manager;
    $this->managedRolePermissionsManager = $managed_role_permissions_manager;
    $this->user = $user;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('tempstore.private'),
      $container->get('entity_type.manager'),
      $container->get('plugin.manager.managed_role_permissions'),
      $container->get('current_user')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'assign_action_confirm_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->formatPlural(count($this->entities), 'Are you sure you want to update assignment of this @item?', 'Are you sure you want to update assignment of these @items?', [
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
    return $this->t('Assign');
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, string $entity_type = NULL) {

    // Only allow asset and log entities.
    if (!in_array($entity_type, ['asset', 'log'])) {
      throw new PluginException('Unsupported entity type given when building form to assign entity');
    }

    // Load the entity type definition.
    $this->entityType = $this->entityTypeManager->getDefinition($entity_type);

    // Load saved entities.
    $this->entities = $this->tempStore->get($this->user->id());

    // If there are no entities, or if the entity type definition didn't load,
    // redirect the user to the cancel URL.
    if (empty($this->entityType) || empty($this->entities)) {
      return new RedirectResponse($this->getCancelUrl()
        ->setAbsolute()
        ->toString());
    }

    // Load active users.
    $active_users = $this->entityTypeManager->getStorage('user')->loadByProperties([
      'status' => TRUE,
    ]);

    // Build options for form select.
    $user_options = array_map(function ($user) {
      return $user->label();
    }, $active_users);

    $form['users'] = [
      '#type' => 'select',
      '#title' => $this->t('Owners'),
      '#description' => $this->t('Assign ownership to one or more users.'),
      '#options' => $user_options,
      '#multiple' => TRUE,
    ];

    $form['operation'] = [
      '#type' => 'radios',
      '#title' => $this->t('Append or replace'),
      '#description' => $this->t('Select "Append" if you want to add owners, but keep the existing assignments. Select "Replace" if you want to replace existing assignments with the people specified above.'),
      '#options' => [
        'append' => $this->t('Append'),
        'replace' => $this->t('Replace'),
      ],
      '#default_value' => 'append',
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
      if (!$entity->access('update', $this->currentUser())) {
        $inaccessible_entities[] = $entity;
        continue;
      }
      $accessible_entities[] = $entity;
    }

    // Update user assignment on accessible entities.
    $total_count = 0;
    foreach ($accessible_entities as $entity) {
      /** @var \Drupal\Core\Field\FieldItemListInterface $owner_field */
      if ($owner_field = $entity->get('owner')) {

        // Save existing users if appending.
        $existing_owners = [];
        if ($form_state->getValue('operation') === 'append') {
          $existing_owners = array_column($owner_field->getValue(), 'target_id');
        }

        // Empty the owner field.
        $owner_field->setValue([]);

        // Build list of owners.
        $new_owners = array_unique(array_merge($existing_owners, $form_state->getValue('users')));
        foreach ($new_owners as $owner) {
          $owner_field->appendItem($owner);
        }

        // Validate the entity before saving.
        $violations = $entity->validate();
        if ($violations->count() > 0) {
          $this->messenger()->addWarning(
            $this->t('Could not assign <a href=":entity_link">%entity_label</a>: validation failed.',
              [
                ':entity_link' => $entity->toUrl()->setAbsolute()->toString(),
                '%entity_label' => $entity->label(),
              ],
            ),
          );
          continue;
        }

        $entity->save();
        $total_count++;
      }
    }

    // Add warning message for inaccessible entities.
    if (!empty($inaccessible_entities)) {
      $inaccessible_count = count($inaccessible_entities);
      $this->messenger()->addWarning($this->formatPlural($inaccessible_count, 'Could not update assignment of @count @item because you do not have the necessary permissions.', 'Could not update assignment of @count @items because you do not have the necessary permissions.', [
        '@item' => $this->entityType->getSingularLabel(),
        '@items' => $this->entityType->getPluralLabel(),
      ]));
    }

    // Add confirmation message.
    if (!empty($total_count)) {
      $this->messenger()->addStatus($this->formatPlural($total_count, 'Updated assignment of @count @item.', 'Updated assignment of @count @items', [
        '@item' => $this->entityType->getSingularLabel(),
        '@items' => $this->entityType->getPluralLabel(),
      ]));
    }

    $this->tempStore->delete($this->currentUser()->id());
    $form_state->setRedirectUrl($this->getCancelUrl());
  }

}
