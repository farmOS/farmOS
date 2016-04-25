<?php
/**
 * @file
 * farm_log.features.inc
 */

/**
 * Implements hook_views_api().
 */
function farm_log_views_api($module = NULL, $api = NULL) {
  return array("api" => "3.0");
}

/**
 * Implements hook_default_log_type().
 */
function farm_log_default_log_type() {
  $items = array();
  $items['farm_observation'] = entity_import('log_type', '{
    "type" : "farm_observation",
    "label" : "Observation",
    "weight" : 0,
    "name_pattern" : "[log:field-farm-observation-type]",
    "name_edit" : "1",
    "done" : "1",
    "rdf_mapping" : []
  }');
  return $items;
}
