<?php
/**
 * @file
 * farm_soil_nrcs.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function farm_soil_nrcs_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "openlayers" && $api == "default_openlayers_layers") {
    return array("version" => "1");
  }
  if ($module == "openlayers" && $api == "default_openlayers_maps") {
    return array("version" => "1");
  }
  if ($module == "openlayers" && $api == "default_openlayers_sources") {
    return array("version" => "1");
  }
}
