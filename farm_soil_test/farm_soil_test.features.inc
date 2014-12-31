<?php
/**
 * @file
 * farm_soil_test.features.inc
 */

/**
 * Implements hook_views_api().
 */
function farm_soil_test_views_api() {
  return array("version" => "3.0");
}

/**
 * Implements hook_default_log_type().
 */
function farm_soil_test_default_log_type() {
  $items = array();
  $items['farm_soil_test'] = entity_import('log_type', '{
    "type" : "farm_soil_test",
    "label" : "Soil Test",
    "weight" : 0,
    "name_pattern" : "Soil test [log:id]",
    "name_edit" : "1",
    "rdf_mapping" : []
  }');
  return $items;
}
