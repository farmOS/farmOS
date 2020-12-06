<?php

namespace Drupal\plan\Plugin\migrate\source\d7;

use Drupal\migrate\Row;
use Drupal\migrate_drupal\Plugin\migrate\source\d7\FieldableEntity;

/**
 * Plan source from database.
 *
 * @MigrateSource(
 *   id = "d7_plan",
 *   source_module = "farm_plan"
 * )
 */
class Plan extends FieldableEntity {

  /**
   * {@inheritdoc}
   */
  public function query() {
    $query = $this->select('farm_plan', 'fa')
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
      'id' => $this->t('The plan ID'),
      'name' => $this->t('The plan name'),
      'type' => $this->t('The plan type'),
      'uid' => $this->t('The plan author ID'),
      'created' => $this->t('Timestamp when the plan was created'),
      'changed' => $this->t('Timestamp when the plan was last modified'),
      'archived' => $this->t('Timestamp when the plan was archived'),
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
    foreach ($this->getFields('farm_plan', $type) as $field_name => $field) {
      $row->setSourceProperty($field_name, $this->getFieldValues('farm_plan', $field_name, $id));
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
