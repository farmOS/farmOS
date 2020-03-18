<?php

/**
 * @file
 * farm_l10n.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function farm_l10n_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}
