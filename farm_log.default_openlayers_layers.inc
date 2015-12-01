<?php
/**
 * @file
 * farm_log.default_openlayers_layers.inc
 */

/**
 * Implements hook_default_openlayers_layers().
 */
function farm_log_default_openlayers_layers() {
  $export = array();

  // Load all available asset types.
  $asset_types = farm_asset_types();

  // Iterate through the available types, and add two layers for each one:
  // a full geometry layer, and a cluster layer.
  foreach ($asset_types as $key => $type) {

    // Generate a machine name for the full geometry layer.
    $full_layer_name = 'farm_asset_full_layer_' . $key;

    // Define the full geometry layer.
    $ol_layer = new stdClass();
    $ol_layer->disabled = FALSE; /* Edit this to true to make a default ol_layer disabled initially */
    $ol_layer->api_version = 1;
    $ol_layer->machine_name = $full_layer_name;
    $ol_layer->name = 'Farm Assets: ' . $type->label . ' (full geometry)';
    $ol_layer->description = '';
    $ol_layer->factory_service = 'openlayers.Layer:Vector';
    $ol_layer->options = array(
      'source' => 'farm_asset_full_source_' . $key,
      'visible' => 1,
      'zoomActivity' => '',
      'style' => 'farm_map_style_yellow',
    );
    $export[$full_layer_name] = $ol_layer;

    // Generate a machine name for the cluster layer.
    $cluster_layer_name = 'farm_asset_cluster_layer_' . $key;

    // Define the cluster layer.
    $ol_layer = new stdClass();
    $ol_layer->disabled = FALSE; /* Edit this to true to make a default ol_layer disabled initially */
    $ol_layer->api_version = 1;
    $ol_layer->machine_name = $cluster_layer_name;
    $ol_layer->name = 'Farm Assets: ' . $type->label . ' (cluster)';
    $ol_layer->description = '';
    $ol_layer->factory_service = 'openlayers.Layer:Vector';
    $ol_layer->options = array(
      'source' => 'farm_asset_cluster_source_' . $key,
      'style' => 'farm_map_style_cluster',
    );
    $export[$cluster_layer_name] = $ol_layer;
  }

  return $export;
}
