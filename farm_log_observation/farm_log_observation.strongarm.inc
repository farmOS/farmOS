<?php
/**
 * @file
 * farm_log_observation.strongarm.inc
 */

/**
 * Implements hook_strongarm().
 */
function farm_log_observation_strongarm() {
  $export = array();

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'pathauto_taxonomy_term_farm_observation_types_pattern';
  $strongarm->value = 'farm/observation/[term:name]';
  $export['pathauto_taxonomy_term_farm_observation_types_pattern'] = $strongarm;

  return $export;
}
