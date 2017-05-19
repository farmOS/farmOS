<?php
/**
 * @file
 * farm_log_observation.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function farm_log_observation_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "field_group" && $api == "field_group") {
    return array("version" => "1");
  }
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_views_api().
 */
function farm_log_observation_views_api($module = NULL, $api = NULL) {
  return array("api" => "3.0");
}

/**
 * Implements hook_default_log_type().
 */
function farm_log_observation_default_log_type() {
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
