<?php
/**
 * @file
 * farm_equipment.strongarm.inc
 */

/**
 * Implements hook_strongarm().
 */
function farm_equipment_strongarm() {
  $export = array();

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'field_bundle_settings_log__farm_maintenance';
  $strongarm->value = array(
    'view_modes' => array(
      'full' => array(
        'custom_settings' => FALSE,
      ),
    ),
    'extra_fields' => array(
      'form' => array(
        'name' => array(
          'weight' => '1',
        ),
      ),
      'display' => array(),
    ),
  );
  $export['field_bundle_settings_log__farm_maintenance'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'pathauto_farm_asset_equipment_pattern';
  $strongarm->value = 'farm/equipment/[farm_asset:name]';
  $export['pathauto_farm_asset_equipment_pattern'] = $strongarm;

  return $export;
}
