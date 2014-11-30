<?php
/**
 * @file
 * farm_soil.strongarm.inc
 */

/**
 * Implements hook_strongarm().
 */
function farm_soil_strongarm() {
  $export = array();

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'field_bundle_settings_log__farm_soil_sample';
  $strongarm->value = array(
    'view_modes' => array(
      'full' => array(
        'custom_settings' => FALSE,
      ),
      'token' => array(
        'custom_settings' => FALSE,
      ),
    ),
    'extra_fields' => array(
      'form' => array(
        'name' => array(
          'weight' => '-10',
        ),
      ),
      'display' => array(),
    ),
  );
  $export['field_bundle_settings_log__farm_soil_sample'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'pathauto_taxonomy_term_farm_soil_types_pattern';
  $strongarm->value = 'farm/soil/type/[term:name]';
  $export['pathauto_taxonomy_term_farm_soil_types_pattern'] = $strongarm;

  return $export;
}
