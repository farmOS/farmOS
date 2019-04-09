<?php

/**
 * @file
 * farm_equipment_field.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function farm_equipment_field_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "field_group" && $api == "field_group") {
    return array("version" => "1");
  }
}
