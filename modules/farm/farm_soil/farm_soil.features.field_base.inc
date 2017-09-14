<?php
/**
 * @file
 * farm_soil.features.field_base.inc
 */

/**
 * Implements hook_field_default_field_bases().
 */
function farm_soil_field_default_field_bases() {
  $field_bases = array();

  // Exported field_base: 'field_farm_soil_names'.
  $field_bases['field_farm_soil_names'] = array(
    'active' => 1,
    'cardinality' => -1,
    'deleted' => 0,
    'entity_types' => array(),
    'field_name' => 'field_farm_soil_names',
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
          'vocabulary' => 'farm_soil_names',
          'parent' => 0,
        ),
      ),
    ),
    'translatable' => 0,
    'type' => 'taxonomy_term_reference',
  );

  return $field_bases;
}
