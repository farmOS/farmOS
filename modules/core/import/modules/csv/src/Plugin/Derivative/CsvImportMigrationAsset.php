<?php

namespace Drupal\farm_import_csv\Plugin\Derivative;

/**
 * Asset CSV import migration derivatives.
 *
 * @internal
 */
class CsvImportMigrationAsset extends CsvImportMigrationBase {

  /**
   * {@inheritdoc}
   */
  protected string $entityType = 'asset';

  /**
   * {@inheritdoc}
   */
  protected function getCreatePermission(string $bundle): string {
    return 'create ' . $bundle . ' asset';
  }

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

    // ID tags.
    $mapping['id_tag/0/id'] = [
      'plugin' => 'get',
      'source' => 'id tag',
    ];
    $mapping['id_tag/0/type'] = [
      'plugin' => 'get',
      'source' => 'id tag type',
    ];
    $mapping['id_tag/0/location'] = [
      'plugin' => 'get',
      'source' => 'id tag location',
    ];
  }

  /**
   * {@inheritdoc}
   */
  protected function alterColumnDescriptions(array &$columns, string $bundle): void {
    parent::alterColumnDescriptions($columns, $bundle);

    // Describe the ID tag columns.
    $columns[] = [
      'name' => 'id tag',
      'description' => $this->t('ID tag.'),
    ];
    $tag_type_description = $this->t('The type of ID tag.');
    $tag_type_allowed_values = $this->t('Allowed values: @values.', ['@values' => implode(', ', array_keys(farm_id_tag_type_options($bundle)))]);
    $columns[] = [
      'name' => 'id tag type',
      'description' => $tag_type_description . ' ' . $tag_type_allowed_values,
    ];
    $columns[] = [
      'name' => 'id tag location',
      'description' => $this->t('Location of the ID tag.'),
    ];
  }

}
