<?php
/**
 * @file
 * farm_asset_children.features.field_base.inc
 */

/**
 * Implements hook_field_default_field_bases().
 */
function farm_asset_children_field_default_field_bases() {
  $field_bases = array();

  // Exported field_base: 'field_farm_parent'.
  $field_bases['field_farm_parent'] = array(
    'active' => 1,
    'cardinality' => -1,
    'deleted' => 0,
    'entity_types' => array(),
    'field_name' => 'field_farm_parent',
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

  return $field_bases;
}
