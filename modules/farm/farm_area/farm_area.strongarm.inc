<?php
/**
 * @file
 * farm_area.strongarm.inc
 */

/**
 * Implements hook_strongarm().
 */
function farm_area_strongarm() {
  $export = array();

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'pathauto_taxonomy_term_farm_areas_pattern';
  $strongarm->value = 'farm/area/[term:name]';
  $export['pathauto_taxonomy_term_farm_areas_pattern'] = $strongarm;

  return $export;
}
