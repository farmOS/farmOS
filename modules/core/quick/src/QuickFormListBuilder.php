<?php

namespace Drupal\farm_quick;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a listing of template entities.
 */
class QuickFormListBuilder extends ConfigEntityListBuilder {

  /**
   * The quick form instance manager.
   *
   * @var \Drupal\farm_quick\QuickFormInstanceManagerInterface
   */
  protected $quickFormInstanceManager;

  /**
   * Constructs a new QuickFormListBuilder object.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type definition.
   * @param \Drupal\Core\Entity\EntityStorageInterface $storage
   *   The entity storage class.
   * @param \Drupal\farm_quick\QuickFormInstanceManagerInterface $quick_form_instance_manager
   *   The quick form instance manager.
   */
  public function __construct(EntityTypeInterface $entity_type, EntityStorageInterface $storage, QuickFormInstanceManagerInterface $quick_form_instance_manager) {
    parent::__construct($entity_type, $storage);
    $this->quickFormInstanceManager = $quick_form_instance_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    return new static(
      $entity_type,
      $container->get('entity_type.manager')->getStorage($entity_type->id()),
      $container->get('quick_form.instance_manager'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function load() {
    return $this->quickFormInstanceManager->getInstances();
  }

  /**
   * {@inheritdoc}
   */
  public function render() {

    $render['table'] = [
      '#type' => 'table',
      '#header' => $this->buildHeader(),
      '#caption' => $this->t('Configured quick forms'),
      '#rows' => [],
      '#empty' => $this->t('There are no configured @label.', ['@label' => $this->entityType->getPluralLabel()]),
      '#cache' => [
        'contexts' => $this->entityType->getListCacheContexts(),
        'tags' => $this->entityType->getListCacheTags(),
      ],
    ];

    $render['default'] = [
      '#type' => 'table',
      '#header' => $this->buildHeader(),
      '#caption' => $this->t('Default quick forms'),
      '#rows' => [],
      '#empty' => $this->t('There are no default @label.', ['@label' => $this->entityType->getPluralLabel()]),
    ];

    // Load all quick form instances into proper table.
    $quick_form_instances = $this->load();
    foreach ($quick_form_instances as $entity) {
      $target = $entity->isNew() ? 'default' : 'table';
      if ($row = $this->buildRow($entity)) {
        $render[$target][$entity->id()] = $row;
      }
    }

    return $render;
  }

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['enabled'] = $this->t('Enabled');
    $header['type'] = $this->t('Plugin');
    $header['label'] = $this->t('Label');
    $header['id'] = $this->t('ID');
    $header['description'] = $this->t('Description');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /** @var \Drupal\farm_quick\Entity\QuickFormInstanceInterface $quick_form */
    $quick_form = $entity;
    $row['enabled'] = [
      '#type' => 'checkbox',
      '#checked' => $quick_form->status(),
      '#attributes' => [
        'disabled' => 'disabled',
      ],
    ];
    $row['type'] = [
      '#plain_text' => $quick_form->getPlugin()->getLabel(),
    ];
    $row['label'] = [
      '#plain_text' => $quick_form->getLabel(),
    ];
    $row['id'] = [
      '#plain_text' => $quick_form->id(),
    ];
    $row['description'] = [
      '#plain_text' => $quick_form->getDescription(),
    ];
    return $row + parent::buildRow($entity);
  }

  /**
   * {@inheritdoc}
   */
  public function getDefaultOperations(EntityInterface $entity) {
    $operations = parent::getDefaultOperations($entity);

    // Override operations for default quick form instances.
    if ($entity->isNew()) {

      // Remove edit operation.
      unset($operations['edit']);

      // Add override operation.
      $operations['override'] = [
        'title' => $this->t('Override'),
        'weight' => 0,
        'url' => $this->ensureDestination(Url::fromRoute('farm_quick.add_form', ['plugin' => $entity->getPluginId()], ['query' => ['override' => TRUE]])),
      ];
    }

    return $operations;
  }

}
