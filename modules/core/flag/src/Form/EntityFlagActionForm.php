<?php

namespace Drupal\farm_flag\Form;

use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Provides an entity flag action form.
 *
 * @see \Drupal\farm_flag\Plugin\Action\EntityFlag
 * @see \Drupal\Core\Entity\Form\DeleteMultipleForm
 */
class EntityFlagActionForm extends ConfirmFormBase {

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
   * The entity field manager.
   *
   * @var \Drupal\Core\Entity\EntityFieldManagerInterface
   */
  protected $entityFieldManager;

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
   * The entities to flag.
   *
   * @var \Drupal\Core\Entity\EntityInterface[]
   */
  protected $entities;

  /**
   * The entity flag field name.
   *
   * @var string
   */
  protected $flagFieldName = 'flag';

  /**
   * Constructs an EntityFlagActionForm form object.
   *
   * @param \Drupal\Core\TempStore\PrivateTempStoreFactory $temp_store_factory
   *   The tempstore factory.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entity_field_manager
   *   The entity field manager.
   * @param \Drupal\Core\Session\AccountInterface $user
   *   The current user.
   */
  public function __construct(PrivateTempStoreFactory $temp_store_factory, EntityTypeManagerInterface $entity_type_manager, EntityFieldManagerInterface $entity_field_manager, AccountInterface $user) {
    $this->tempStore = $temp_store_factory->get('entity_flag_confirm');
    $this->entityTypeManager = $entity_type_manager;
    $this->entityFieldManager = $entity_field_manager;
    $this->user = $user;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('tempstore.private'),
      $container->get('entity_type.manager'),
      $container->get('entity_field.manager'),
      $container->get('current_user')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    // Get entity type ID from the route because ::buildForm has not yet been
    // called.
    $entity_type_id = $this->getRouteMatch()->getParameter('entity_type_id');
    return $entity_type_id . '_flag_action_confirm_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->formatPlural(count($this->entities), 'Are you sure you want to flag this @item?', 'Are you sure you want to flag these @items?', [
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
    return $this->t('Flag');
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $entity_type_id = NULL) {
    $this->entityType = $this->entityTypeManager->getDefinition($entity_type_id);
    $this->entities = $this->tempStore->get($this->user->id() . ':' . $entity_type_id);
    if (empty($entity_type_id) || empty($this->entities)) {
      return new RedirectResponse($this->getCancelUrl()
        ->setAbsolute()
        ->toString());
    }

    // Get allowed values for the selected entities.
    // We find the intersection of all the allowed values to ensure that
    // disallowed flags cannot be assigned.
    $entity_bundles = array_unique(array_map(function ($entity) {
      return $entity->bundle();
    }, $this->entities));
    $allowed_values = farm_flag_options($entity_type_id, $entity_bundles, TRUE);

    $form['flags'] = [
      '#type' => 'select',
      '#title' => $this->t('Flags'),
      '#description' => $this->t('Add flags to enable better sorting and filtering of records.'),
      '#options' => $allowed_values,
      '#multiple' => TRUE,
    ];

    $form['operation'] = [
      '#type' => 'radios',
      '#title' => $this->t('Append or replace'),
      '#description' => $this->t('Select "Append" if you want to add flags to the records, but keep the existing flags. Select "Replace" if you want to replace existing flags with the ones specified above.'),
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

    // Update flags on accessible entities.
    $total_count = 0;
    foreach ($accessible_entities as $entity) {
      /** @var \Drupal\Core\Field\FieldItemListInterface $flag_field */
      if ($flag_field = $entity->get($this->flagFieldName)) {

        // Save existing flags if appending.
        $existing_flags = [];
        if ($form_state->getValue('operation') === 'append') {
          $existing_flags = array_column($flag_field->getValue(), 'value');
        }

        // Empty the flag field.
        $flag_field->setValue([]);

        $new_flags = array_unique(array_merge($existing_flags, $form_state->getValue('flags')));
        foreach ($new_flags as $flag) {
          $flag_field->appendItem($flag);
        }

        // Validate the entity before saving.
        $violations = $entity->validate();
        if ($violations->count() > 0) {
          $this->messenger()->addWarning(
            $this->t('Could not flag <a href=":entity_link">%entity_label</a>: validation failed.',
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
      $this->messenger()->addWarning($this->formatPlural($inaccessible_count, 'Could not flag @count @item because you do not have the necessary permissions.', 'Could not flag @count @items because you do not have the necessary permissions.', [
        '@item' => $this->entityType->getSingularLabel(),
        '@items' => $this->entityType->getPluralLabel(),
      ]));
    }

    // Add confirmation message.
    if (!empty($total_count)) {
      $this->messenger()->addStatus($this->formatPlural($total_count, 'Flagged @count @item.', 'Flagged @count @items', [
        '@item' => $this->entityType->getSingularLabel(),
        '@items' => $this->entityType->getPluralLabel(),
      ]));
    }

    $this->tempStore->delete($this->currentUser()->id() . ':' . $this->entityType->id());
    $form_state->setRedirectUrl($this->getCancelUrl());
  }

}
