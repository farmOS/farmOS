<?php
/**
 * @file
 * farm_map.default_openlayers_layers.inc
 */

/**
 * Implements hook_default_openlayers_layers().
 */
function farm_map_default_openlayers_layers() {
  $export = array();

  $ol_layer = new stdClass();
  $ol_layer->disabled = FALSE; /* Edit this to true to make a default ol_layer disabled initially */
  $ol_layer->api_version = 1;
  $ol_layer->machine_name = 'farm_map_layer_google_hybrid';
  $ol_layer->name = 'Farm Map: Google Hybrid';
  $ol_layer->description = '';
  $ol_layer->factory_service = 'openlayers.Layer:Tile';
  $ol_layer->options = array(
    'source' => 'farm_map_source_google_hybrid',
    'visible' => 1,
    'opacity' => 1,
  );
  $export['farm_map_layer_google_hybrid'] = $ol_layer;

  return $export;
}
