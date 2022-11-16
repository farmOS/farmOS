<?php

namespace Drupal\farm_log_category\Form;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element\Checkboxes;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Provides a categorize log confirmation form.
 */
class LogCategorizeActionForm extends ConfirmFormBase {

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
   * Constructs a LogCategorizeActionForm form object.
   *
   * @param \Drupal\Core\TempStore\PrivateTempStoreFactory $temp_store_factory
   *   The tempstore factory.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Session\AccountInterface $user
   *   The current user.
   */
  public function __construct(PrivateTempStoreFactory $temp_store_factory, EntityTypeManagerInterface $entity_type_manager, AccountInterface $user) {
    $this->tempStore = $temp_store_factory->get('log_categorize_confirm');
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
    return 'log_categorize_action_confirm_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->formatPlural(count($this->entities), 'Are you sure you want to categorize this @item?', 'Are you sure you want to categorize these @items?', [
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
    return $this->t('Categorize');
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $this->entityType = $this->entityTypeManager->getDefinition('log');
    $this->entities = $this->tempStore->get($this->user->id());
    if (empty($this->entityType) || empty($this->entities)) {
      return new RedirectResponse($this->getCancelUrl()
        ->setAbsolute()
        ->toString());
    }

    // Load terms.
    /** @var \Drupal\taxonomy\TermStorageInterface $term_storage */
    $term_storage = $this->entityTypeManager->getSTorage('taxonomy_term');
    $terms = $term_storage->loadTree('log_category', 0, NULL, TRUE);

    // Filter to active terms.
    $active_terms = array_filter($terms, function ($term) {
      return (int) $term->get('status')->value;
    });

    // Build options with -- to represent hierarchies.
    $options = [];
    foreach ($active_terms as $term) {
      // This approach taken from core TaxonomyIndexTid views filter plugin.
      $label = str_repeat('-', $term->depth) . $term->label();
      $options[$term->id()] = $label;
    }

    $form['category'] = [
      '#type' => 'select',
      '#title' => $this->t('Log category'),
      '#description' => $this->t('Use this to organize your logs into categories for easier searching and filtering later.'),
      '#options' => $options,
      '#multiple' => TRUE,
    ];

    $form['operation'] = [
      '#type' => 'radios',
      '#title' => $this->t('Append or replace'),
      '#description' => $this->t('Select "Append" if you want to add a category, but keep the existing log categories. Select "Replace" if you want to replace the existing log categories with the ones specified above.'),
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

    // Get submitted category IDs.
    $submitted_category_ids = Checkboxes::getCheckedCheckboxes($form_state->getValue('category', []));

    // Update categories on accessible entities.
    $total_count = 0;
    foreach ($accessible_entities as $entity) {
      /** @var \Drupal\Core\Field\EntityReferenceFieldItemList $category_field */
      if ($category_field = $entity->get('category')) {

        // Save existing values if appending.
        $existing_values = [];
        if ($form_state->getValue('operation') === 'append') {
          $existing_values = array_column($category_field->getValue(), 'target_id');
        }

        // Empty the field.
        $category_field->setValue([]);

        $new_values = array_unique(array_merge($existing_values, $submitted_category_ids));
        foreach ($new_values as $parent_id) {
          $category_field->appendItem($parent_id);
        }

        $entity->save();
        $total_count++;
      }
    }

    // Add warning message for inaccessible entities.
    if (!empty($inaccessible_entities)) {
      $inaccessible_count = count($inaccessible_entities);
      $this->messenger()->addWarning($this->formatPlural($inaccessible_count, 'Could not categorize @count @item because you do not have the necessary permissions.', 'Could not categorize @count @items because you do not have the necessary permissions.', [
        '@item' => $this->entityType->getSingularLabel(),
        '@items' => $this->entityType->getPluralLabel(),
      ]));
    }

    // Add confirmation message.
    if (!empty($total_count)) {
      $this->messenger()->addStatus($this->formatPlural($total_count, 'Categorized @count @item.', 'Categorized @count @items.', [
        '@item' => $this->entityType->getSingularLabel(),
        '@items' => $this->entityType->getPluralLabel(),
      ]));
    }

    $this->tempStore->delete($this->currentUser()->id() . ':' . $this->entityType->id());
    $form_state->setRedirectUrl($this->getCancelUrl());
  }

}
