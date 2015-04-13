<?php
/**
 * @file
 * Strongarm variables for farm_equipment.
 */

/**
 * Implements hook_strongarm().
 */
function farm_equipment_strongarm() {
  $export = array();

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'pathauto_farm_asset_equipment_pattern';
  $strongarm->value = 'farm/equipment/[farm_asset:name]';
  $export['pathauto_farm_asset_equipment_pattern'] = $strongarm;

  return $export;
}
