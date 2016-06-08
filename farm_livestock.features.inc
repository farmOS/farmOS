<?php
/**
 * @file
 * farm_livestock.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function farm_livestock_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_views_api().
 */
function farm_livestock_views_api($module = NULL, $api = NULL) {
  return array("api" => "3.0");
}

/**
 * Implements hook_default_farm_asset_type().
 */
function farm_livestock_default_farm_asset_type() {
  $items = array();
  $items['animal'] = entity_import('farm_asset_type', '{
    "type" : "animal",
    "label" : "Animal",
    "weight" : 0,
    "rdf_mapping" : []
  }');
  return $items;
}

/**
 * Implements hook_default_log_type().
 */
function farm_livestock_default_log_type() {
  $items = array();
  $items['farm_medical'] = entity_import('log_type', '{
    "type" : "farm_medical",
    "label" : "Medical",
    "weight" : 0,
    "name_pattern" : "[log:name]",
    "name_edit" : "1",
    "done" : "1",
    "rdf_mapping" : []
  }');
  return $items;
}
