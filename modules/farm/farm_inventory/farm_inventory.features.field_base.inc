<?php
/**
 * @file
 * farm_inventory.features.field_base.inc
 */

/**
 * Implements hook_field_default_field_bases().
 */
function farm_inventory_field_default_field_bases() {
  $field_bases = array();

  // Exported field_base: 'field_farm_inventory'.
  $field_bases['field_farm_inventory'] = array(
    'active' => 1,
    'cardinality' => -1,
    'deleted' => 0,
    'entity_types' => array(),
    'field_name' => 'field_farm_inventory',
    'indexes' => array(
      'revision_id' => array(
        0 => 'revision_id',
      ),
      'value' => array(
        0 => 'value',
      ),
    ),
    'locked' => 0,
    'module' => 'field_collection',
    'settings' => array(
      'hide_blank_items' => 1,
      'hide_initial_item' => 1,
      'path' => '',
    ),
    'translatable' => 0,
    'type' => 'field_collection',
  );

  // Exported field_base: 'field_farm_inventory_asset'.
  $field_bases['field_farm_inventory_asset'] = array(
    'active' => 1,
    'cardinality' => 1,
    'deleted' => 0,
    'entity_types' => array(),
    'field_name' => 'field_farm_inventory_asset',
    'indexes' => array(
      'target_id' => array(
        0 => 'target_id',
      ),
    ),
    'locked' => 0,
    'module' => 'entityreference',
    'settings' => array(
      'handler' => 'base',
      'handler_settings' => array(
        'behaviors' => array(
          'views-select-list' => array(
            'status' => 0,
          ),
        ),
        'sort' => array(
          'type' => 'none',
        ),
        'target_bundles' => array(),
      ),
      'target_type' => 'farm_asset',
    ),
    'translatable' => 0,
    'type' => 'entityreference',
  );

  // Exported field_base: 'field_farm_inventory_value'.
  $field_bases['field_farm_inventory_value'] = array(
    'active' => 1,
    'cardinality' => 1,
    'deleted' => 0,
    'entity_types' => array(),
    'field_name' => 'field_farm_inventory_value',
    'indexes' => array(),
    'locked' => 0,
    'module' => 'fraction',
    'settings' => array(),
    'translatable' => 0,
    'type' => 'fraction',
  );

  return $field_bases;
}
