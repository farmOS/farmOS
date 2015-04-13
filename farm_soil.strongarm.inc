<?php
/**
 * @file
 * Strongarm variables for farm_soil.
 */

/**
 * Implements hook_strongarm().
 */
function farm_soil_strongarm() {
  $export = array();

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'pathauto_taxonomy_term_farm_soil_types_pattern';
  $strongarm->value = 'farm/soil/type/[term:name]';
  $export['pathauto_taxonomy_term_farm_soil_types_pattern'] = $strongarm;

  return $export;
}
