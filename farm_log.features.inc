<?php
/**
 * @file
 * farm_log.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function farm_log_ctools_plugin_api() {
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
function farm_log_views_api() {
  return array("version" => "3.0");
}

/**
 * Implements hook_default_log_type().
 */
function farm_log_default_log_type() {
  $items = array();
  $items['farm_issue'] = entity_import('log_type', '{
    "type" : "farm_issue",
    "label" : "Issue",
    "weight" : 0,
    "name_pattern" : "Issue [log:id]",
    "name_edit" : "1",
    "rdf_mapping" : []
  }');
  return $items;
}
