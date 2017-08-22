<?php
/**
 * @file
 * farm_season.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function farm_season_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}
