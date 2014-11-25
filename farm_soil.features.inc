<?php
/**
 * @file
 * farm_soil.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function farm_soil_ctools_plugin_api() {
  list($module, $api) = func_get_args();
  if ($module == "openlayers" && $api == "openlayers_layers") {
    return array("version" => "1");
  }
  list($module, $api) = func_get_args();
  if ($module == "openlayers" && $api == "openlayers_maps") {
    return array("version" => "1");
  }
  list($module, $api) = func_get_args();
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_default_log_type().
 */
function farm_soil_default_log_type() {
  $items = array();
  $items['farm_soil_sample'] = entity_import('log_type', '{
    "type" : "farm_soil_sample",
    "label" : "Soil Sample",
    "weight" : 0,
    "name_pattern" : "Sample [log:id]",
    "name_edit" : "0",
    "rdf_mapping" : []
  }');
  return $items;
}
