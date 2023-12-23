<?php

namespace Drupal\asset\Plugin\migrate\source\d7;

use Drupal\migrate\Row;
use Drupal\migrate_drupal\Plugin\migrate\source\d7\FieldableEntity;

/**
 * Asset source from database.
 *
 * @MigrateSource(
 *   id = "d7_asset",
 *   source_module = "farm_asset"
 * )
 *
 * @deprecated in farm:3.0.0 and is removed from farm:4.0.0. Support for farmOS
 *   v1 migrations was dropped in farmOS 3.x.
 * @see https://www.drupal.org/project/farm/issues/3410701
 * @see https://www.drupal.org/project/farm/issues/3382616
 */
class Asset extends FieldableEntity {

  /**
   * {@inheritdoc}
   */
  public function query() {
    $query = $this->select('farm_asset', 'fa')
      ->fields('fa')
      ->distinct()
      ->orderBy('id');

    if (isset($this->configuration['bundle'])) {
      $query->condition('fa.type', (array) $this->configuration['bundle'], 'IN');
    }

    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [
      'id' => $this->t('The asset ID'),
      'name' => $this->t('The asset name'),
      'type' => $this->t('The asset type'),
      'uid' => $this->t('The asset author ID'),
      'created' => $this->t('Timestamp when the asset was created'),
      'changed' => $this->t('Timestamp when the asset was last modified'),
      'archived' => $this->t('Timestamp when the asset was archived'),
    ];
    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    $id = $row->getSourceProperty('id');
    $type = $row->getSourceProperty('type');

    // Get Field API field values.
    foreach ($this->getFields('farm_asset', $type) as $field_name => $field) {
      $row->setSourceProperty($field_name, $this->getFieldValues('farm_asset', $field_name, $id));
    }

    return parent::prepareRow($row);
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    $ids['id']['type'] = 'integer';
    return $ids;
  }

}
