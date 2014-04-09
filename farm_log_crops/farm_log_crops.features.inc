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
  list($module, $api) = func_get_args();
  if ($module == "strongarm" && $api == "strongarm") {
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
  $items['farm_cultivation'] = entity_import('log_type', '{
    "type" : "farm_cultivation",
    "label" : "Cultivation",
    "weight" : 0,
    "name_pattern" : "Cultivate [log:field_farm_quantity_value] [log:field-farm-quantity-units] [log:field-farm-areas] [log:field-farm-crops]",
    "rdf_mapping" : []
  }');
  $items['farm_harvest'] = entity_import('log_type', '{
    "type" : "farm_harvest",
    "label" : "Harvest",
    "weight" : 0,
    "name_pattern" : "Harvest [log:field_farm_quantity_value] [log:field-farm-quantity-units] [log:field-farm-crops]",
    "rdf_mapping" : []
  }');
  $items['farm_input'] = entity_import('log_type', '{
    "type" : "farm_input",
    "label" : "Input",
    "weight" : 0,
    "name_pattern" : "[log:field-farm-input-type] [log:field_farm_quantity_value] [log:field-farm-quantity-units] [log:field-farm-inputs] [log:field-farm-areas] [log:field-farm-crops]",
    "rdf_mapping" : []
  }');
  $items['farm_planting'] = entity_import('log_type', '{
    "type" : "farm_planting",
    "label" : "Planting",
    "weight" : 0,
    "name_pattern" : "[log:field-farm-crops] [log:field-farm-season]",
    "rdf_mapping" : []
  }');
  $items['farm_planting_location'] = entity_import('log_type', '{
    "type" : "farm_planting_location",
    "label" : "Planting Location",
    "weight" : 0,
    "name_pattern" : "[log:field-farm-crops] in [log:field-farm-areas]",
    "rdf_mapping" : []
  }');
  return $items;
}
