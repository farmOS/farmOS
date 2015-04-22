<?php
/**
 * @file
 * farm_map.strongarm.inc
 */

/**
 * Implements hook_strongarm().
 */
function farm_map_strongarm() {
  $export = array();

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'openlayers_source_type';
  $strongarm->value = 'internal';
  $export['openlayers_source_type'] = $strongarm;

  return $export;
}
