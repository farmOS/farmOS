<?php
/**
 * @file
 * farm_season.strongarm.inc
 */

/**
 * Implements hook_strongarm().
 */
function farm_season_strongarm() {
  $export = array();

  $strongarm = new stdClass();
  $strongarm->disabled = FALSE; /* Edit this to true to make a default strongarm disabled initially */
  $strongarm->api_version = 1;
  $strongarm->name = 'pathauto_taxonomy_term_farm_season_pattern';
  $strongarm->value = 'farm/season/[term:name]';
  $export['pathauto_taxonomy_term_farm_season_pattern'] = $strongarm;

  return $export;
}
