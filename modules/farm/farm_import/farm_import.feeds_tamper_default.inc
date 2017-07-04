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

  // Make asset name field required.
  foreach ($asset_types as $bundle => $type) {
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
  }

  // Explode parent IDs into an array.
  foreach ($asset_types as $bundle => $type) {
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

  return $export;
}
