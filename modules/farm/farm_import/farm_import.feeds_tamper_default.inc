<?php
/**
 * @file
 * Feeds tamper plugins provided by the farm import module.
 */

/**
 * Implements hook_feeds_tamper_default().
 */
function farm_import_feeds_tamper_default() {
  $export = array();

  // Load asset types.
  $asset_types = farm_asset_types();

  // Add asset tamper plugins.
  foreach ($asset_types as $bundle => $type) {

    // Make asset name field required.
    $feeds_tamper = new stdClass();
    $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
    $feeds_tamper->api_version = 2;
    $feeds_tamper->id = 'farm_asset_' . $bundle . '-name-required';
    $feeds_tamper->importer = 'farm_asset_' . $bundle;
    $feeds_tamper->source = 'Name';
    $feeds_tamper->plugin_id = 'required';
    $feeds_tamper->settings = array(
      'invert' => 0,
      'log' => 0,
    );
    $feeds_tamper->weight = 0;
    $feeds_tamper->description = 'Required field';
    $export['farm_asset_' . $bundle . '-name-required'] = $feeds_tamper;

    // If a "Parent IDs" field exists, explode it into an array of IDs.
    if (!empty(field_info_instance('farm_asset', 'field_farm_parent', $bundle))) {
      $feeds_tamper = new stdClass();
      $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
      $feeds_tamper->api_version = 2;
      $feeds_tamper->id = 'farm_asset_' . $bundle . '-parent_ids-explode';
      $feeds_tamper->importer = 'farm_asset_' . $bundle;
      $feeds_tamper->source = 'Parent IDs';
      $feeds_tamper->plugin_id = 'explode';
      $feeds_tamper->settings = array(
        'separator' => ',',
        'limit' => '',
        'real_separator' => ',',
      );
      $feeds_tamper->weight = 0;
      $feeds_tamper->description = 'Explode';
      $export['farm_asset_' . $bundle . '-parent_ids-explode'] = $feeds_tamper;
    }
  }

  // Load log types.
  $log_types = log_types();

  // Add log tamper plugins.
  foreach ($log_types as $bundle => $type) {

    // Make date field required.
    $feeds_tamper = new stdClass();
    $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
    $feeds_tamper->api_version = 2;
    $feeds_tamper->id = 'log_' . $bundle . '-date-required';
    $feeds_tamper->importer = 'log_' . $bundle;
    $feeds_tamper->source = 'Date';
    $feeds_tamper->plugin_id = 'required';
    $feeds_tamper->settings = array(
      'invert' => 0,
      'log' => 0,
    );
    $feeds_tamper->weight = 0;
    $feeds_tamper->description = 'Required field';
    $export['log_' . $bundle . '-date-required'] = $feeds_tamper;

    // If an "Asset IDs" field exists, explode it into an array of IDs.
    if (!empty(field_info_instance('log', 'field_farm_asset', $bundle))) {
      $feeds_tamper = new stdClass();
      $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
      $feeds_tamper->api_version = 2;
      $feeds_tamper->id = 'log_' . $bundle . '-asset_ids-explode';
      $feeds_tamper->importer = 'log_' . $bundle;
      $feeds_tamper->source = 'Asset IDs';
      $feeds_tamper->plugin_id = 'explode';
      $feeds_tamper->settings = array(
        'separator' => ',',
        'limit' => '',
        'real_separator' => ',',
      );
      $feeds_tamper->weight = 0;
      $feeds_tamper->description = 'Explode';
      $export['log_' . $bundle . '-asset_ids-explode'] = $feeds_tamper;
    }

    // If an "Area names" field exists, explode it into an array of area names.
    if (!empty(field_info_instance('log', 'field_farm_area', $bundle))) {
      $feeds_tamper = new stdClass();
      $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
      $feeds_tamper->api_version = 2;
      $feeds_tamper->id = 'log_' . $bundle . '-area_names-explode';
      $feeds_tamper->importer = 'log_' . $bundle;
      $feeds_tamper->source = 'Area names';
      $feeds_tamper->plugin_id = 'explode';
      $feeds_tamper->settings = array(
        'separator' => ',',
        'limit' => '',
        'real_separator' => ',',
      );
      $feeds_tamper->weight = 0;
      $feeds_tamper->description = 'Explode';
      $export['log_' . $bundle . '-area_names-explode'] = $feeds_tamper;
    }

    // If a "Category names" field exists, explode it into an array of category
    // names.
    if (!empty(field_info_instance('log', 'field_farm_log_category', $bundle))) {
      $feeds_tamper = new stdClass();
      $feeds_tamper->disabled = FALSE; /* Edit this to true to make a default feeds_tamper disabled initially */
      $feeds_tamper->api_version = 2;
      $feeds_tamper->id = 'log_' . $bundle . '-category_names-explode';
      $feeds_tamper->importer = 'log_' . $bundle;
      $feeds_tamper->source = 'Category names';
      $feeds_tamper->plugin_id = 'explode';
      $feeds_tamper->settings = array(
        'separator' => ',',
        'limit' => '',
        'real_separator' => ',',
      );
      $feeds_tamper->weight = 0;
      $feeds_tamper->description = 'Explode';
      $export['log_' . $bundle . '-category_names-explode'] = $feeds_tamper;
    }
  }

  return $export;
}
