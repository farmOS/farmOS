<?php
/**
 * @file
 * farm_trait.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function farm_trait_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}
