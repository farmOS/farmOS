<?php
/**
 * @file
 * farm_log.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function farm_log_ctools_plugin_api() {
  list($module, $api) = func_get_args();
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_views_api().
 */
function farm_log_views_api() {
  return array("version" => "3.0");
}

/**
 * Implements hook_default_log_type().
 */
function farm_log_default_log_type() {
  $items = array();
  $items['farm_activity'] = entity_import('log_type', '{
    "type" : "farm_activity",
    "label" : "Activity",
    "weight" : 0,
    "name_pattern" : "[log:name]",
    "name_edit" : "1",
    "done" : "1",
    "rdf_mapping" : []
  }');
  $items['farm_movement'] = entity_import('log_type', '{
    "type" : "farm_movement",
    "label" : "Movement",
    "weight" : 0,
    "name_pattern" : "Move [log:field-farm-asset] to [log:field-farm-move-to]",
    "name_edit" : "0",
    "done" : "1",
    "rdf_mapping" : []
  }');
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
