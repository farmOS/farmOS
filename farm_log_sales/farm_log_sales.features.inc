<?php
/**
 * @file
 * farm_log_sales.features.inc
 */

/**
 * Implements hook_views_api().
 */
function farm_log_sales_views_api() {
  return array("version" => "3.0");
}

/**
 * Implements hook_default_log_type().
 */
function farm_log_sales_default_log_type() {
  $items = array();
  $items['farm_loss'] = entity_import('log_type', '{ "type" : "farm_loss", "label" : "Loss", "weight" : 0 }');
  $items['farm_purchase'] = entity_import('log_type', '{ "type" : "farm_purchase", "label" : "Purchase", "weight" : 0 }');
  $items['farm_sale'] = entity_import('log_type', '{ "type" : "farm_sale", "label" : "Sale", "weight" : 0 }');
  return $items;
}
