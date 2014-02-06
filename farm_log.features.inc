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
  $items['farm_issue'] = entity_import('log_type', '{ "type" : "farm_issue", "label" : "Issue", "weight" : 0 }');
  return $items;
}
