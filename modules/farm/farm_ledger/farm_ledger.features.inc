<?php
/**
 * @file
 * farm_ledger.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function farm_ledger_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "field_group" && $api == "field_group") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_views_api().
 */
function farm_ledger_views_api($module = NULL, $api = NULL) {
  return array("api" => "3.0");
}

/**
 * Implements hook_default_log_type().
 */
function farm_ledger_default_log_type() {
  $items = array();
  $items['farm_purchase'] = entity_import('log_type', '{
    "type" : "farm_purchase",
    "label" : "Purchase",
    "weight" : 0,
    "name_pattern" : "[log:field-farm-asset] [log:field-farm-invoice-number]",
    "name_edit" : "1",
    "done" : "1",
    "rdf_mapping" : []
  }');
  $items['farm_sale'] = entity_import('log_type', '{
    "type" : "farm_sale",
    "label" : "Sale",
    "weight" : 0,
    "name_pattern" : "[log:field-farm-customer] [log:field-farm-invoice-number] [log:field-farm-asset]",
    "name_edit" : "1",
    "done" : "1",
    "rdf_mapping" : []
  }');
  return $items;
}
