<?php
/**
 * @file
 * Strongarm variables for farm_log.
 */

/**
 * Implements hook_strongarm().
 */
function farm_log_strongarm() {
  $export = array();

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'pathauto_taxonomy_term_farm_observation_types_pattern';
  $strongarm->value = 'farm/observation/[term:name]';
  $export['pathauto_taxonomy_term_farm_observation_types_pattern'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'pathauto_taxonomy_term_farm_quantity_units_pattern';
  $strongarm->value = 'farm/unit/[term:name]';
  $export['pathauto_taxonomy_term_farm_quantity_units_pattern'] = $strongarm;

  return $export;
}
