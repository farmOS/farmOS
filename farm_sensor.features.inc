<?php
/**
 * @file
 * farm_sensor.features.inc
 */

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
