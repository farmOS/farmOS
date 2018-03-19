<?php
/**
 * @file
 * farm_flags.features.field_base.inc
 */

/**
 * Implements hook_field_default_field_bases().
 */
function farm_flags_field_default_field_bases() {
  $field_bases = array();

  // Exported field_base: 'field_farm_flags'.
  $field_bases['field_farm_flags'] = array(
    'active' => 1,
    'cardinality' => -1,
    'deleted' => 0,
    'entity_types' => array(),
    'field_name' => 'field_farm_flags',
    'indexes' => array(
      'value' => array(
        0 => 'value',
      ),
    ),
    'locked' => 0,
    'module' => 'list',
    'settings' => array(
      'allowed_values' => array(),
      'allowed_values_function' => '',
    ),
    'translatable' => 0,
    'type' => 'list_text',
  );

  return $field_bases;
}
