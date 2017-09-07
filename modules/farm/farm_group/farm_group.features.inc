<?php
/**
 * @file
 * farm_group.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function farm_group_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "field_group" && $api == "field_group") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_default_farm_asset_type().
 */
function farm_group_default_farm_asset_type() {
  $items = array();
  $items['group'] = entity_import('farm_asset_type', '{ "type" : "group", "label" : "Group", "weight" : 0, "rdf_mapping" : [] }');
  return $items;
}
