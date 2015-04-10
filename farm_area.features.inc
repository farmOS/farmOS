<?php
/**
 * @file
 * Features include file for farm_area.
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function farm_area_ctools_plugin_api() {
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
 * Implements hook_views_api().
 */
function farm_area_views_api() {
  return array("version" => "3.0");
}
