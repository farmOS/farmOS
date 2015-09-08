<?php
/**
 * @file
 * farm_sensor.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function farm_sensor_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_views_api().
 */
function farm_sensor_views_api($module = NULL, $api = NULL) {
  return array("api" => "3.0");
}

/**
 * Implements hook_default_farm_asset_type().
 */
function farm_sensor_default_farm_asset_type() {
  $items = array();
  $items['sensor'] = entity_import('farm_asset_type', '{
    "type" : "sensor",
    "label" : "Sensor",
    "weight" : 0,
    "rdf_mapping" : []
  }');
  return $items;
}
