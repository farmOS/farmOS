<?php

namespace Drupal\farm_migrate\Plugin\migrate\source\d7;

use Drupal\migrate_drupal\Plugin\migrate\source\d7\FieldableEntity;

/**
 * Log quantity source from database.
 *
 * @MigrateSource(
 *   id = "d7_farm_quantity",
 *   source_module = "farm_quantity"
 * )
 */
class Quantity extends FieldableEntity {

  /**
   * {@inheritdoc}
   */
  public function query() {
    $query = $this->select('field_collection_item', 'fci');
    $query->addField('fci', 'item_id', 'id');

    // Join in the measure value.
    $query->leftJoin('field_data_field_farm_quantity_measure', 'fdffqm',
      "fci.item_id = fdffqm.entity_id AND fdffqm.entity_type = 'field_collection_item' AND fdffqm.bundle = 'field_farm_quantity' AND fdffqm.deleted = '0'");
    $query->addField('fdffqm', 'field_farm_quantity_measure_value', 'measure');

    // Join in the numerator and denominator values.
    $query->leftJoin('field_data_field_farm_quantity_value', 'fdffqv',
      "fci.item_id = fdffqv.entity_id AND fdffqv.entity_type = 'field_collection_item' AND fdffqv.bundle = 'field_farm_quantity' AND fdffqv.deleted = '0'");
    $query->addField('fdffqv', 'field_farm_quantity_value_numerator', 'value_numerator');
    $query->addField('fdffqv', 'field_farm_quantity_value_denominator', 'value_denominator');

    // Join in the units value.
    $query->leftJoin('field_data_field_farm_quantity_units', 'fdffqu',
      "fci.item_id = fdffqu.entity_id AND fdffqu.entity_type = 'field_collection_item' AND fdffqu.bundle = 'field_farm_quantity' AND fdffqu.deleted = '0'");
    $query->addField('fdffqu', 'field_farm_quantity_units_tid', 'units');

    // Join in the label value.
    $query->leftJoin('field_data_field_farm_quantity_label', 'fdffql',
      "fci.item_id = fdffql.entity_id AND fdffql.entity_type = 'field_collection_item' AND fdffql.bundle = 'field_farm_quantity' AND fdffql.deleted = '0'");
    $query->addField('fdffql', 'field_farm_quantity_label_value', 'label');

    // Join in the log table to get the uid.
    $query->leftJoin('field_data_field_farm_quantity', 'fdffq', 'fci.item_id = fdffq.field_farm_quantity_value');
    $query->leftJoin('log', 'l', "fdffq.entity_type = 'log' AND fdffq.entity_id = l.id");
    $query->addField('l', 'uid');

    // Ensure we don't include archived/deleted fields.
    $query->condition('fci.archived', '0');
    $query->condition('fdffq.deleted', '0');

    // Distinct items only.
    $query->distinct();

    // Order by item_id.
    $query->orderBy('fci.item_id');

    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [
      'id' => $this->t('The quantity item ID.'),
      'measure' => $this->t('The quantity measure.'),
      'value_numerator' => $this->t('The quantity value numerator.'),
      'value_denominator' => $this->t('The quantity value denominator.'),
      'units' => $this->t('The quantity units.'),
      'label' => $this->t('The quantity label.'),
      'uid' => $this->t('The user ID that created the quantity.'),
    ];
    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    $ids['id']['type'] = 'integer';
    return $ids;
  }

}
