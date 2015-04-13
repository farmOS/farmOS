<?php
/**
 * @file
 * Features include file for farm_taxonomy.
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function farm_taxonomy_ctools_plugin_api() {
  list($module, $api) = func_get_args();
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}
