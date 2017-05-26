<?php
/**
 * @file
 * farm_livestock_eggs.features.field_base.inc
 */

/**
 * Implements hook_field_default_field_bases().
 */
function farm_livestock_eggs_field_default_field_bases() {
  $field_bases = array();

  // Exported field_base: 'field_farm_egg_qty'.
  $field_bases['field_farm_egg_qty'] = array(
    'active' => 1,
    'cardinality' => 1,
    'deleted' => 0,
    'entity_types' => array(),
    'field_name' => 'field_farm_egg_qty',
    'indexes' => array(),
    'locked' => 0,
    'module' => 'number',
    'settings' => array(),
    'translatable' => 0,
    'type' => 'number_integer',
  );

  return $field_bases;
}
