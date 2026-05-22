<?php

declare(strict_types=1);

namespace Drupal\farm_category\Form;

use Drupal\Component\Plugin\Exception\PluginException;
use Drupal\Core\DependencyInjection\AutowireTrait;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element\Checkboxes;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Provides a confirmation form for categorizing entities.
 */
class CategorizeActionForm extends ConfirmFormBase {

  use AutowireTrait;

  /**
   * The entity type.
   *
   * @var \Drupal\Core\Entity\EntityTypeInterface|null
   */
  protected $entityType;

  /**
   * The entities to categorize.
   *
   * @var \Drupal\Core\Entity\ContentEntityInterface[]
   */
  protected $entities;

  public function __construct(
    protected PrivateTempStoreFactory $tempStoreFactory,
    protected EntityTypeManagerInterface $entityTypeManager,
    protected AccountInterface $user,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'categorize_action_confirm_form';
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
  public function getConfirmText() {
    return $this->t('Categorize');
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, ?string $entity_type = NULL, ?string $taxonomy = NULL): array|RedirectResponse {

    // Only allow asset and log entities.
    if (!in_array($entity_type, ['asset', 'log'])) {
      throw new PluginException('Unsupported entity type given when building form to categorize entity');
    }

    // Load the entity type definition.
    $this->entityType = $this->entityTypeManager->getDefinition($entity_type, FALSE);

    // Load saved entities.
    $this->entities = $this->tempStoreFactory->get('entity_categorize_confirm')->get((string) $this->user->id());

    // If there are no entities, or if the entity type definition didn't load,
    // redirect the user to the cancel URL.
    if (!$this->entityType || empty($this->entities)) {
      return new RedirectResponse($this->getCancelUrl()
        ->setAbsolute()
        ->toString());
    }

    // If a taxonomy was not defined, redirect to the cancel URL.
    if (empty($taxonomy)) {
      return new RedirectResponse($this->getCancelUrl()
        ->setAbsolute()
        ->toString());
    }

    // Load terms.
    /** @var \Drupal\taxonomy\TermStorageInterface $term_storage */
    $term_storage = $this->entityTypeManager->getSTorage('taxonomy_term');
    $terms = $term_storage->loadTree($taxonomy, 0, NULL, TRUE);

    // Filter to active terms.
    $active_terms = array_filter($terms, function ($term) {
      return (bool) $term->get('status')->value;
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
      '#description' => $this->t('Use this to organize your records into categories for easier searching and filtering later.'),
      '#options' => $options,
      '#multiple' => TRUE,
    ];

    $form['operation'] = [
      '#type' => 'radios',
      '#title' => $this->t('Append or replace'),
      '#description' => $this->t('Select "Append" if you want to add new categories alongside existing ones. Select "Replace" if you want to replace the existing categories with the ones specified.'),
      '#options' => [
        'append' => $this->t('Append'),
        'replace' => $this->t('Replace'),
      ],
      '#default_value' => 'append',
      '#required' => TRUE,
    ];

    // Delegate to the parent method.
    $form = parent::buildForm($form, $form_state);

    // Remove form description text.
    unset($form['description']);

    return $form;
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
      $category_field = $entity->get('category');

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

    $this->tempStoreFactory->get('entity_categorize_confirm')->delete($this->currentUser()->id() . ':' . $this->entityType->id());
    $form_state->setRedirectUrl($this->getCancelUrl());
  }

}
