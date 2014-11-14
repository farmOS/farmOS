<?php
/**
 * @file
 * farm_crop.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function farm_crop_ctools_plugin_api() {
  list($module, $api) = func_get_args();
  if ($module == "field_group" && $api == "field_group") {
    return array("version" => "1");
  }
  list($module, $api) = func_get_args();
  if ($module == "page_manager" && $api == "pages_default") {
    return array("version" => "1");
  }
  list($module, $api) = func_get_args();
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_views_api().
 */
function farm_crop_views_api() {
  return array("version" => "3.0");
}

/**
 * Implements hook_default_farm_asset_type().
 */
function farm_crop_default_farm_asset_type() {
  $items = array();
  $items['planting'] = entity_import('farm_asset_type', '{
    "type" : "planting",
    "label" : "Planting",
    "weight" : 0,
    "rdf_mapping" : []
  }');
  return $items;
}
