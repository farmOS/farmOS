<?php
/**
 * @file
 * farm_log_activity.features.inc
 */

/**
 * Implements hook_views_api().
 */
function farm_log_activity_views_api($module = NULL, $api = NULL) {
  return array("api" => "3.0");
}

/**
 * Implements hook_default_log_type().
 */
function farm_log_activity_default_log_type() {
  $items = array();
  $items['farm_activity'] = entity_import('log_type', '{
    "type" : "farm_activity",
    "label" : "Activity",
    "weight" : 0,
    "name_pattern" : "[log:name]",
    "name_edit" : "1",
    "done" : "1",
    "rdf_mapping" : []
  }');
  return $items;
}
