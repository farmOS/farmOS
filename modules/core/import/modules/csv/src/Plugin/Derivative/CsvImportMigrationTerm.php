<?php

namespace Drupal\farm_import_csv\Plugin\Derivative;

/**
 * Term CSV import migration derivatives.
 *
 * @internal
 */
class CsvImportMigrationTerm extends CsvImportMigrationBase {

  /**
   * {@inheritdoc}
   */
  protected string $entityType = 'taxonomy_term';

  /**
   * {@inheritdoc}
   */
  protected function getCreatePermission(string $bundle): string {
    return 'create terms in ' . $bundle;
  }

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

    // Add support for assigning term parent.
    // The parent term must already exist in the same vocabulary.
    $mapping['parent'] = [
      'plugin' => 'term_lookup',
      'bundle' => $bundle,
      'source' => 'parent',
    ];
  }

  /**
   * {@inheritdoc}
   */
  protected function alterColumnDescriptions(array &$columns, string $bundle): void {
    parent::alterColumnDescriptions($columns, $bundle);

    // Describe the parent column.
    $columns[] = [
      'name' => 'parent',
      'description' => 'Parent term in the taxonomy hierarchy.',
    ];
  }

}
