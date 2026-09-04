<?php

declare(strict_types=1);

namespace Drupal\farm_import_csv\Plugin\Derivative;

/**
 * Organization CSV import migration derivatives.
 *
 * @internal
 */
class CsvImportMigrationOrganization extends CsvImportMigrationBase {

  /**
   * {@inheritdoc}
   */
  protected string $entityType = 'organization';

  /**
   * {@inheritdoc}
   */
  protected function getCreatePermission(string $bundle): string {
    return 'create ' . $bundle . ' organization';
  }

  /**
   * {@inheritdoc}
   */
  protected function alterProcessMapping(array &$mapping, string $bundle): void {
    parent::alterProcessMapping($mapping, $bundle);

    // Set the organization type.
    $mapping['type'] = [
      'plugin' => 'default_value',
      'default_value' => $bundle,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition): array {

    // The `organization` entity type is provided by the optional farmOS
    // `organization` module. If that module is not installed, the entity
    // type isn't registered and the derivative should be inert rather
    // than throwing during plugin discovery.
    if (!$this->entityTypeManager->hasDefinition($this->entityType)) {
      return [];
    }
    return parent::getDerivativeDefinitions($base_plugin_definition);
  }

}
