<?php
/**
 * @file
 * farm_soil.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function farm_soil_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "openlayers" && $api == "default_openlayers_layers") {
    return array("version" => "1");
  }
  if ($module == "openlayers" && $api == "default_openlayers_maps") {
    return array("version" => "1");
  }
  if ($module == "openlayers" && $api == "default_openlayers_sources") {
    return array("version" => "1");
  }
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}
