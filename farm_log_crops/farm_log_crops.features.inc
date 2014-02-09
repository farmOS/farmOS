<?php
/**
 * @file
 * farm_log_crops.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function farm_log_crops_ctools_plugin_api() {
  list($module, $api) = func_get_args();
  if ($module == "field_group" && $api == "field_group") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_views_api().
 */
function farm_log_crops_views_api() {
  return array("version" => "3.0");
}

/**
 * Implements hook_default_log_type().
 */
function farm_log_crops_default_log_type() {
  $items = array();
  $items['farm_fertilize'] = entity_import('log_type', '{ "type" : "farm_fertilize", "label" : "Fertilizing", "weight" : 0 }');
  $items['farm_harvest'] = entity_import('log_type', '{ "type" : "farm_harvest", "label" : "Harvest", "weight" : 0 }');
  $items['farm_planting'] = entity_import('log_type', '{ "type" : "farm_planting", "label" : "Seeding\\/Planting", "weight" : 0 }');
  return $items;
}
