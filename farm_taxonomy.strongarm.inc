<?php
/**
 * @file
 * farm_taxonomy.strongarm.inc
 */

/**
 * Implements hook_strongarm().
 */
function farm_taxonomy_strongarm() {
  $export = array();

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'pathauto_taxonomy_term_farm_crops_pattern';
  $strongarm->value = 'farm/crop/[term:name]';
  $export['pathauto_taxonomy_term_farm_crops_pattern'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'pathauto_taxonomy_term_farm_inputs_pattern';
  $strongarm->value = 'farm/input/[term:name]';
  $export['pathauto_taxonomy_term_farm_inputs_pattern'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'pathauto_taxonomy_term_farm_priority_pattern';
  $strongarm->value = 'farm/priority/[term:name]';
  $export['pathauto_taxonomy_term_farm_priority_pattern'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'pathauto_taxonomy_term_farm_quantity_units_pattern';
  $strongarm->value = 'farm/unit/[term:name]';
  $export['pathauto_taxonomy_term_farm_quantity_units_pattern'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'pathauto_taxonomy_term_farm_season_pattern';
  $strongarm->value = 'farm/season/[term:name]';
  $export['pathauto_taxonomy_term_farm_season_pattern'] = $strongarm;

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'pathauto_taxonomy_term_farm_threats_pattern';
  $strongarm->value = 'farm/threat/[term:name]';
  $export['pathauto_taxonomy_term_farm_threats_pattern'] = $strongarm;

  return $export;
}
