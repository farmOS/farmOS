<?php
/**
 * @file
 * farm_livestock.strongarm.inc
 */

/**
 * Implements hook_strongarm().
 */
function farm_livestock_strongarm() {
  $export = array();

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'pathauto_farm_asset_animal_pattern';
  $strongarm->value = 'farm/animal/[farm_asset:name]';
  $export['pathauto_farm_asset_animal_pattern'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'pathauto_taxonomy_term_farm_animal_groups_pattern';
  $strongarm->value = 'farm/animal/group/[term:name]';
  $export['pathauto_taxonomy_term_farm_animal_groups_pattern'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'pathauto_taxonomy_term_farm_animal_types_pattern';
  $strongarm->value = 'farm/animal/type/[term:name]';
  $export['pathauto_taxonomy_term_farm_animal_types_pattern'] = $strongarm;

  return $export;
}
