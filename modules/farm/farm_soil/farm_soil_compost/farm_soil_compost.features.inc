<?php
/**
 * @file
 * farm_soil_compost.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function farm_soil_compost_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "field_group" && $api == "field_group") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_views_api().
 */
function farm_soil_compost_views_api($module = NULL, $api = NULL) {
  return array("api" => "3.0");
}

/**
 * Implements hook_default_farm_asset_type().
 */
function farm_soil_compost_default_farm_asset_type() {
  $items = array();
  $items['compost'] = entity_import('farm_asset_type', '{
    "type" : "compost",
    "label" : "Compost",
    "weight" : 0,
    "rdf_mapping" : []
  }');
  return $items;
}
