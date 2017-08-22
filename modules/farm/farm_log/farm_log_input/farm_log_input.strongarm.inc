<?php
/**
 * @file
 * farm_log_input.strongarm.inc
 */

/**
 * Implements hook_strongarm().
 */
function farm_log_input_strongarm() {
  $export = array();

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'pathauto_taxonomy_term_farm_materials_pattern';
  $strongarm->value = 'farm/material/[term:name]';
  $export['pathauto_taxonomy_term_farm_materials_pattern'] = $strongarm;

  return $export;
}
