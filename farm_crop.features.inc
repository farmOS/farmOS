<?php
/**
 * @file
 * farm_crop.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function farm_crop_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_views_api().
 */
function farm_crop_views_api($module = NULL, $api = NULL) {
  return array("api" => "3.0");
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

/**
 * Implements hook_default_log_type().
 */
function farm_crop_default_log_type() {
  $items = array();
  $items['farm_harvest'] = entity_import('log_type', '{
    "type" : "farm_harvest",
    "label" : "Harvest",
    "weight" : 0,
    "name_pattern" : "Harvest [log:field-farm-asset]",
    "name_edit" : "1",
    "done" : "1",
    "rdf_mapping" : []
  }');
  $items['farm_input'] = entity_import('log_type', '{
    "type" : "farm_input",
    "label" : "Input",
    "weight" : 0,
    "name_pattern" : "[log:field-farm-material] [log:field-farm-area] [log:field-farm-asset]",
    "name_edit" : "1",
    "done" : "1",
    "rdf_mapping" : []
  }');
  $items['farm_seeding'] = entity_import('log_type', '{
    "type" : "farm_seeding",
    "label" : "Seeding",
    "weight" : 0,
    "name_edit" : "0",
    "name_pattern" : "Seed [log:field-farm-planting]",
    "done" : "1",
    "rdf_mapping" : []
  }');
  $items['farm_transplanting'] = entity_import('log_type', '{
    "type" : "farm_transplanting",
    "label" : "Transplanting",
    "weight" : 0,
    "name_pattern" : "Transplant [log:field-farm-planting]",
    "name_edit" : "0",
    "done" : "1",
    "rdf_mapping" : []
  }');
  return $items;
}
