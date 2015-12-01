<?php
/**
 * @file
 * farm_log.default_openlayers_components.inc
 */

/**
 * Implements hook_default_openlayers_components().
 */
function farm_log_default_openlayers_components() {
  $export = array();

  // Get farm asset types.
  $asset_types = farm_asset_types();

  // Create an array of sources to zoom to.
  $sources = array();
  foreach ($asset_types as $type => $info) {
    $source = 'farm_asset_full_source_' . $type;
    $sources[$source] = $source;
  }

  // Define a zoom-to-source component that applies to all asset sources.
  $ol_component = new stdClass();
  $ol_component->disabled = FALSE; /* Edit this to true to make a default ol_component disabled initially */
  $ol_component->api_version = 1;
  $ol_component->machine_name = 'farm_asset_component_assets_zoom_to_source';
  $ol_component->name = 'Farm Asset: Assets Zoom to Source';
  $ol_component->description = 'Automatically zoom to the asset source when the map is first built.';
  $ol_component->factory_service = 'openlayers.Component:ZoomToSource';
  $ol_component->options = array(
    'source' => $sources,
    'animations' => array(
      'pan' => 500,
      'zoom' => 500,
    ),
    'zoom' => 'auto',
    'enableAnimations' => 1,
    'max_zoom' => 20,
    'process_once' => 0,
  );
  $export['farm_asset_component_assets_zoom_to_source'] = $ol_component;

  return $export;
}
