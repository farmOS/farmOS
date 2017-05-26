<?php
/**
 * @file
 * farm_soil.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function farm_soil_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}
