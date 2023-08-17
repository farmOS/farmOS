<?php

namespace Drupal\farm_import_csv\Plugin\Derivative;

/**
 * Term CSV import migration derivatives.
 */
class CsvImportMigrationTerm extends CsvImportMigrationBase {

  /**
   * {@inheritdoc}
   */
  protected string $entityType = 'taxonomy_term';

  /**
   * {@inheritdoc}
   */
  protected function alterProcessMapping(array &$mapping, string $bundle): void {
    parent::alterProcessMapping($mapping, $bundle);

    // Set the vocabulary.
    $mapping['vid'] = [
      'plugin' => 'default_value',
      'default_value' => $bundle,
    ];
  }

}
