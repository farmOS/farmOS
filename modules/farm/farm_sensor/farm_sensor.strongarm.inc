<?php
/**
 * @file
 * farm_sensor.strongarm.inc
 */

/**
 * Implements hook_strongarm().
 */
function farm_sensor_strongarm() {
  $export = array();

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'pathauto_farm_asset_sensor_pattern';
  $strongarm->value = 'farm/sensor/[farm_asset:name]';
  $export['pathauto_farm_asset_sensor_pattern'] = $strongarm;

  return $export;
}
