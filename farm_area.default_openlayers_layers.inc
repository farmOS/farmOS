<?php
/**
 * @file
 * farm_area.default_openlayers_layers.inc
 */

/**
 * Implements hook_default_openlayers_layers().
 */
function farm_area_default_openlayers_layers() {
  $export = array();

  $ol_layer = new stdClass();
  $ol_layer->disabled = FALSE; /* Edit this to true to make a default ol_layer disabled initially */
  $ol_layer->api_version = 1;
  $ol_layer->machine_name = 'farm_areas';
  $ol_layer->name = 'Farm Areas';
  $ol_layer->description = '';
  $ol_layer->factory_service = 'openlayers.Layer:Vector';
  $ol_layer->options = array(
    'source' => 'views_geojson_farm_areas_geojson_feed',
    'visible' => 1,
    'zoomActivity' => '',
  );
  $export['farm_areas'] = $ol_layer;

  return $export;
}
