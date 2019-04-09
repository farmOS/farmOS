<?php
/**
 * @file
 * farm_log_harvest.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function farm_log_harvest_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "field_group" && $api == "field_group") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_views_api().
 */
function farm_log_harvest_views_api($module = NULL, $api = NULL) {
  return array("api" => "3.0");
}

/**
 * Implements hook_default_log_type().
 */
function farm_log_harvest_default_log_type() {
  $items = array();
  $items['farm_harvest'] = entity_import('log_type', '{
    "type" : "farm_harvest",
    "label" : "Harvest",
    "weight" : 0,
    "name_pattern" : "Harvest [log:timestamp:short] [log:field-farm-asset]",
    "name_edit" : "1",
    "done" : "1",
    "rdf_mapping" : []
  }');
  return $items;
}
