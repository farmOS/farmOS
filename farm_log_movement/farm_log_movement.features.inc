<?php
/**
 * @file
 * farm_log_movement.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function farm_log_movement_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "openlayers" && $api == "default_openlayers_components") {
    return array("version" => "1");
  }
  if ($module == "openlayers" && $api == "default_openlayers_layers") {
    return array("version" => "1");
  }
  if ($module == "openlayers" && $api == "default_openlayers_maps") {
    return array("version" => "1");
  }
  if ($module == "openlayers" && $api == "default_openlayers_sources") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_views_api().
 */
function farm_log_movement_views_api($module = NULL, $api = NULL) {
  return array("api" => "3.0");
}

/**
 * Implements hook_default_log_type().
 */
function farm_log_movement_default_log_type() {
  $items = array();
  $items['farm_movement'] = entity_import('log_type', '{
    "type" : "farm_movement",
    "label" : "Movement",
    "weight" : 0,
    "name_pattern" : "Move [log:field-farm-asset] to [log:field-farm-move-to]",
    "name_edit" : "0",
    "done" : "1",
    "rdf_mapping" : []
  }');
  return $items;
}
