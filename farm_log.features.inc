<?php
/**
 * @file
 * farm_log.features.inc
 */

/**
 * Implements hook_views_api().
 */
function farm_log_views_api() {
  return array("version" => "3.0");
}

/**
 * Implements hook_default_log_type().
 */
function farm_log_default_log_type() {
  $items = array();
  $items['farm_failure'] = entity_import('log_type', '{ "type" : "farm_failure", "label" : "Failure", "weight" : 0 }');
  $items['farm_fertilization'] = entity_import('log_type', '{ "type" : "farm_fertilization", "label" : "Fertilization", "weight" : 0 }');
  $items['farm_harvest'] = entity_import('log_type', '{ "type" : "farm_harvest", "label" : "Harvest", "weight" : 0 }');
  $items['farm_issue'] = entity_import('log_type', '{ "type" : "farm_issue", "label" : "Issue", "weight" : 0 }');
  $items['farm_seeding'] = entity_import('log_type', '{ "type" : "farm_seeding", "label" : "Seeding", "weight" : 0 }');
  $items['farm_threat'] = entity_import('log_type', '{ "type" : "farm_threat", "label" : "Threat", "weight" : 0 }');
  $items['farm_transplanting'] = entity_import('log_type', '{ "type" : "farm_transplanting", "label" : "Transplanting", "weight" : 0 }');
  return $items;
}
