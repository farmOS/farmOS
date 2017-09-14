<?php
/**
 * @file
 * farm_water_test.features.field_base.inc
 */

/**
 * Implements hook_field_default_field_bases().
 */
function farm_water_test_field_default_field_bases() {
  $field_bases = array();

  // Exported field_base: 'field_farm_water_lab'.
  $field_bases['field_farm_water_lab'] = array(
    'active' => 1,
    'cardinality' => 1,
    'deleted' => 0,
    'entity_types' => array(),
    'field_name' => 'field_farm_water_lab',
    'indexes' => array(
      'format' => array(
        0 => 'format',
      ),
    ),
    'locked' => 0,
    'module' => 'text',
    'settings' => array(
      'max_length' => 255,
    ),
    'translatable' => 0,
    'type' => 'text',
  );

  return $field_bases;
}
