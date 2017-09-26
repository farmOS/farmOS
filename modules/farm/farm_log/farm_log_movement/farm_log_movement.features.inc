<?php
/**
 * @file
 * farm_log_movement.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function farm_log_movement_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "field_group" && $api == "field_group") {
    return array("version" => "1");
  }
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
