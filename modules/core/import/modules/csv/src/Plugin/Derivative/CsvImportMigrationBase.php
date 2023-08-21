<?php

namespace Drupal\farm_import_csv\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base class for CSV import migration derivatives.
 */
abstract class CsvImportMigrationBase extends DeriverBase implements ContainerDeriverInterface {

  /**
   * Set this in child classes.
   *
   * @var string
   */
  protected string $entityType;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * CsvImportMigration constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static(
      $container->get('entity_type.manager'),
    );
  }

  /**
   * Alter migration process mapping for a given bundle.
   *
   * @param array &$mapping
   *   The migration process mapping.
   * @param string $bundle
   *   The entity bundle.
   */
  protected function alterProcessMapping(array &$mapping, string $bundle): void {
    // Do nothing.
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition): array {
    $definitions = [];

    // If the entity type is not defined, return nothing.
    if (empty($this->entityType)) {
      return $definitions;
    }

    // Load all bundles for this entity type.
    $entity_type = $this->entityTypeManager->getDefinition($this->entityType);
    $bundles = $this->entityTypeManager->getStorage($entity_type->getBundleEntityType())->loadMultiple();

    // Generate a migration for each bundle.
    foreach ($bundles as $bundle) {
      $definition = $base_plugin_definition;

      // Set the migration ID and label.
      $definition['id'] .= ':' . $bundle->id();
      $definition['label'] = $entity_type->getLabel() . ': ' . $bundle->label();

      // Alter migration process mapping for this bundle.
      $this->alterProcessMapping($definition['process'], $bundle->id());

      $definitions[$bundle->id()] = $definition;
    }

    // Return migration definitions.
    return $definitions;
  }

}
