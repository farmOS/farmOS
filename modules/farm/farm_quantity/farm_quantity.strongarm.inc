<?php
/**
 * @file
 * farm_quantity.strongarm.inc
 */

/**
 * Implements hook_strongarm().
 */
function farm_quantity_strongarm() {
  $export = array();

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'pathauto_taxonomy_term_farm_quantity_units_pattern';
  $strongarm->value = 'farm/unit/[term:name]';
  $export['pathauto_taxonomy_term_farm_quantity_units_pattern'] = $strongarm;

  return $export;
}
