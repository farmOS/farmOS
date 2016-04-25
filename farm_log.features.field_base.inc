<?php
/**
 * @file
 * farm_log.features.field_base.inc
 */

/**
 * Implements hook_field_default_field_bases().
 */
function farm_log_field_default_field_bases() {
  $field_bases = array();

  // Exported field_base: 'field_farm_observation_type'.
  $field_bases['field_farm_observation_type'] = array(
    'active' => 1,
    'cardinality' => 1,
    'deleted' => 0,
    'entity_types' => array(),
    'field_name' => 'field_farm_observation_type',
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
          'vocabulary' => 'farm_observation_types',
          'parent' => 0,
        ),
      ),
    ),
    'translatable' => 0,
    'type' => 'taxonomy_term_reference',
  );

  return $field_bases;
}
