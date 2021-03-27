<?php

namespace Drupal\farm_migrate\Plugin\migrate\source\d7;

use Drupal\migrate_drupal\Plugin\migrate\source\d7\FieldableEntity;

/**
 * Log inventory source from database.
 *
 * @MigrateSource(
 *   id = "d7_farm_inventory",
 *   source_module = "farm_inventory"
 * )
 */
class Inventory extends FieldableEntity {

  /**
   * {@inheritdoc}
   */
  public function query() {
    $query = $this->select('field_collection_item', 'fci');
    $query->addField('fci', 'item_id', 'id');

    // Join in the numerator and denominator values.
    $query->leftJoin('field_data_field_farm_inventory_value', 'fdffiv',
      "fci.item_id = fdffiv.entity_id AND fdffiv.entity_type = 'field_collection_item' AND fdffiv.bundle = 'field_farm_inventory' AND fdffiv.deleted = '0'");
    $query->addField('fdffiv', 'field_farm_inventory_value_numerator', 'value_numerator');
    $query->addField('fdffiv', 'field_farm_inventory_value_denominator', 'value_denominator');
    $query->addExpression('SIGN(fdffiv.field_farm_inventory_value_numerator)', 'inventory_value_sign');

    // Join in the inventory asset reference.
    $query->leftJoin('field_data_field_farm_inventory_asset', 'fdffia',
      "fci.item_id = fdffia.entity_id AND fdffia.entity_type = 'field_collection_item' AND fdffia.bundle = 'field_farm_inventory' AND fdffia.deleted = '0'");
    $query->addField('fdffia', 'field_farm_inventory_asset_target_id', 'inventory_asset');

    // Join in the log table to get the uid.
    $query->leftJoin('field_data_field_farm_inventory', 'fdffq', 'fci.item_id = fdffq.field_farm_inventory_value');
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
      'id' => $this->t('The inventory item ID.'),
      'value_numerator' => $this->t('The inventory value numerator.'),
      'value_denominator' => $this->t('The inventory value denominator.'),
      'inventory_asset' => $this->t('The inventory asset.'),
      'inventory_value_sign' => $this->t('Thi sign of the inventory value. Used to determine increment or decrement.'),
      'uid' => $this->t('The user ID that created the inventory.'),
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
