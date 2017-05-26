<?php
/**
 * @file
 * farm_crop.strongarm.inc
 */

/**
 * Implements hook_strongarm().
 */
function farm_crop_strongarm() {
  $export = array();

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'pathauto_farm_asset_planting_pattern';
  $strongarm->value = 'farm/planting/[farm_asset:name]';
  $export['pathauto_farm_asset_planting_pattern'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'pathauto_taxonomy_term_farm_crops_pattern';
  $strongarm->value = 'farm/crop/[term:name]';
  $export['pathauto_taxonomy_term_farm_crops_pattern'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'pathauto_taxonomy_term_farm_crop_families_pattern';
  $strongarm->value = 'farm/crop-family/[term:name]';
  $export['pathauto_taxonomy_term_farm_crop_families_pattern'] = $strongarm;

  return $export;
}
