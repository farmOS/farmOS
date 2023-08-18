<?php

namespace Drupal\farm_import_csv\Plugin\Derivative;

/**
 * Log CSV import migration derivatives.
 */
class CsvImportMigrationLog extends CsvImportMigrationBase {

  /**
   * {@inheritdoc}
   */
  protected string $entityType = 'log';

  /**
   * {@inheritdoc}
   */
  protected function getCreatePermission(string $bundle): string {
    return 'create ' . $bundle . ' ' . $this->entityType;
  }

  /**
   * {@inheritdoc}
   */
  protected function alterProcessMapping(array &$mapping, string $bundle): void {
    parent::alterProcessMapping($mapping, $bundle);

    // Set the log type.
    $mapping['type'] = [
      'plugin' => 'default_value',
      'default_value' => $bundle,
    ];
  }

}
