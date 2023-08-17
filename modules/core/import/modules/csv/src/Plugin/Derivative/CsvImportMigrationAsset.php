<?php

namespace Drupal\farm_import_csv\Plugin\Derivative;

/**
 * Asset CSV import migration derivatives.
 */
class CsvImportMigrationAsset extends CsvImportMigrationBase {

  /**
   * {@inheritdoc}
   */
  protected string $entityType = 'asset';

  /**
   * {@inheritdoc}
   */
  protected function alterProcessMapping(array &$mapping, string $bundle): void {
    parent::alterProcessMapping($mapping, $bundle);

    // Set the asset type.
    $mapping['type'] = [
      'plugin' => 'default_value',
      'default_value' => $bundle,
    ];
  }

}
