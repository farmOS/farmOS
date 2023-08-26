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
   * Get the entity create permission string for a given bundle.
   *
   * @param string $bundle
   *   The entity bundle.
   *
   * @return string
   *   Returns a permission string for creating entities of this bundle.
   */
  abstract protected function getCreatePermission(string $bundle): string;

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
   * Alter column descriptions for a given bundle.
   *
   * @param array &$columns
   *   The column descriptions from third-party settings.
   * @param string $bundle
   *   The entity bundle.
   */
  protected function alterColumnDescriptions(array &$columns, string $bundle): void {
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

      // Alter column descriptions for this bundle.
      $this->alterColumnDescriptions($definition['third_party_settings']['farm_import_csv']['columns'], $bundle->id());

      // Add access control permissions to third party settings.
      $definition['third_party_settings']['farm_import_csv']['access']['permissions'][] = $this->getCreatePermission($bundle->id());

      $definitions[$bundle->id()] = $definition;
    }

    // Return migration definitions.
    return $definitions;
  }

}
