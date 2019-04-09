<?php
/**
 * @file
 * farm_equipment.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function farm_equipment_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "field_group" && $api == "field_group") {
    return array("version" => "1");
  }
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_views_api().
 */
function farm_equipment_views_api($module = NULL, $api = NULL) {
  return array("api" => "3.0");
}

/**
 * Implements hook_default_farm_asset_type().
 */
function farm_equipment_default_farm_asset_type() {
  $items = array();
  $items['equipment'] = entity_import('farm_asset_type', '{
    "type" : "equipment",
    "label" : "Equipment",
    "weight" : 0,
    "rdf_mapping" : []
  }');
  return $items;
}

/**
 * Implements hook_default_log_type().
 */
function farm_equipment_default_log_type() {
  $items = array();
  $items['farm_maintenance'] = entity_import('log_type', '{
    "type" : "farm_maintenance",
    "label" : "Maintenance",
    "weight" : 0,
    "name_pattern" : "Maintenance [log:timestamp:short] [log:field-farm-asset]",
    "name_edit" : "1",
    "done" : "1",
    "rdf_mapping" : []
  }');
  return $items;
}
