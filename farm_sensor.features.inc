<?php
/**
 * @file
 * farm_sensor.features.inc
 */

/**
 * Implements hook_views_api().
 */
function farm_sensor_views_api() {
  return array("version" => "3.0");
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
