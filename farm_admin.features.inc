<?php
/**
 * @file
 * Features include file for farm_admin.
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function farm_admin_ctools_plugin_api() {
  list($module, $api) = func_get_args();
  if ($module == "page_manager" && $api == "pages_default") {
    return array("version" => "1");
  }
}
