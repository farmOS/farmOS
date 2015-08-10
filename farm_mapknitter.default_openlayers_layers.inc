<?php
/**
 * @file
 * farm_mapknitter.default_openlayers_layers.inc
 */

/**
 * Implements hook_default_openlayers_layers().
 */
function farm_mapknitter_default_openlayers_layers() {
  $export = array();

  // Load the MapKnitter title.
  $title = variable_get('farm_mapknitter_title', '');

  // If the title isn't set, bail.
  if (empty($title)) {
    return $export;
  }

  // Add a MapKnitter layer.
  $ol_layer = new stdClass();
  $ol_layer->disabled = FALSE; /* Edit this to true to make a default ol_layer disabled initially */
  $ol_layer->api_version = 1;
  $ol_layer->machine_name = 'farm_mapknitter_layer';
  $ol_layer->name = check_plain($title);
  $ol_layer->description = '';
  $ol_layer->factory_service = 'openlayers.Layer:Tile';
  $ol_layer->options = array(
    'source' => 'farm_mapknitter_source',
    'visible' => 1,
  );
  $export['farm_mapknitter_layer'] = $ol_layer;

  // Add a layer group for MapKnitter layers.
  $ol_layer = new stdClass();
  $ol_layer->disabled = FALSE; /* Edit this to true to make a default ol_layer disabled initially */
  $ol_layer->api_version = 1;
  $ol_layer->machine_name = 'farm_mapknitter_layer_group';
  $ol_layer->name = 'Farm MapKnitter Layer Group';
  $ol_layer->description = '';
  $ol_layer->factory_service = 'openlayers.Layer:Group';
  $ol_layer->options = array(
    'source' => '',
    'visible' => 1,
    'grouptitle' => 'MapKnitter layers',
    'grouplayers' => array(
      'farm_mapknitter_layer',
    ),
  );
  $export['farm_mapknitter_layer_group'] = $ol_layer;

  return $export;
}
