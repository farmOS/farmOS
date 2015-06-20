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

  // Exported field_base: 'field_farm_area'
  $field_bases['field_farm_area'] = array(
    'active' => 1,
    'cardinality' => 1,
    'deleted' => 0,
    'entity_types' => array(),
    'field_name' => 'field_farm_area',
    'indexes' => array(
      'tid' => array(
        0 => 'tid',
      ),
    ),
    'locked' => 0,
    'module' => 'taxonomy',
    'settings' => array(
      'allowed_values' => array(
        0 => array(
          'vocabulary' => 'farm_areas',
          'parent' => 0,
        ),
      ),
    ),
    'translatable' => 0,
    'type' => 'taxonomy_term_reference',
  );

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
      'allowed_values' => array(),
      'allowed_values_function' => 'farm_area_type_options',
    ),
    'translatable' => 0,
    'type' => 'list_text',
  );

  return $field_bases;
}
