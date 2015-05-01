<?php
/**
 * @file
 * farm_area.features.field_base.inc
 */

/**
 * Implements hook_field_default_field_bases().
 */
function farm_area_field_default_field_bases() {
  $field_bases = array();

  // Exported field_base: 'field_farm_area_type'
  $field_bases['field_farm_area_type'] = array(
    'active' => 1,
    'cardinality' => 1,
    'deleted' => 0,
    'entity_types' => array(),
    'field_name' => 'field_farm_area_type',
    'indexes' => array(
      'value' => array(
        0 => 'value',
      ),
    ),
    'locked' => 0,
    'module' => 'list',
    'settings' => array(
      'allowed_values' => array(
        'property' => 'Property',
        'field' => 'Field',
        'paddock' => 'Paddock',
        'rowbed' => 'Row/bed',
        'building' => 'Building',
        'greenhouse' => 'Greenhouse',
        'water' => 'Water feature',
        'landmark' => 'Landmark',
      ),
      'allowed_values_function' => '',
    ),
    'translatable' => 0,
    'type' => 'list_text',
  );

  return $field_bases;
}
