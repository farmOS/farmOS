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
  $ol_layer->machine_name = 'farm_area_layer_group';
  $ol_layer->name = 'Farm Area Layer Group';
  $ol_layer->description = '';
  $ol_layer->factory_service = 'openlayers.Layer:Group';
  $ol_layer->options = array(
    'source' => '',
    'visible' => 1,
    'grouptitle' => 'Farm Areas',
  );
  $export['farm_area_layer_group'] = $ol_layer;

  $ol_layer = new stdClass();
  $ol_layer->disabled = FALSE; /* Edit this to true to make a default ol_layer disabled initially */
  $ol_layer->api_version = 1;
  $ol_layer->machine_name = 'farm_area_layer_areas';
  $ol_layer->name = 'Farm Areas';
  $ol_layer->description = '';
  $ol_layer->factory_service = 'openlayers.Layer:Vector';
  $ol_layer->options = array(
    'source' => 'views_geojson_farm_areas_geojson_feed',
    'visible' => 1,
    'zoomActivity' => '',
  );
  $export['farm_area_layer_areas'] = $ol_layer;

  return $export;
}

/**
 * Implements hook_default_openlayers_layers_alter().
 */
function farm_area_default_openlayers_layers_alter(&$layers) {

  // Load all available area types.
  $area_types = farm_area_types();

  // Start an array of layer machine names.
  $machine_names = array();

  // Iterate through the available types, and add a layer for each one.
  foreach ($area_types as $key => $type) {

    // Generate a machine name.
    $machine_name = 'farm_area_layer_' . $key;

    // Add the machine name to the array for later.
    $machine_names[] = $machine_name;

    // Define the layer.
    $ol_layer = new stdClass();
    $ol_layer->disabled = FALSE; /* Edit this to true to make a default ol_layer disabled initially */
    $ol_layer->api_version = 1;
    $ol_layer->machine_name = $machine_name;
    $ol_layer->name = 'Farm Area: ' . $type['label'];
    $ol_layer->description = '';
    $ol_layer->factory_service = 'openlayers.Layer:Vector';
    $ol_layer->options = array(
      'source' => 'farm_area_source_' . $key,
      'visible' => 1,
      'zoomActivity' => '',
      'style' => $type['style'],
    );

    // Add the source to the export.
    $layers[$machine_name] = $ol_layer;
  }

  // If "farm_area_layer_group" is available, add the layers to it.
  if (!empty($layers['farm_area_layer_group'])) {
    $layers['farm_area_layer_group']->options['grouplayers'] = $machine_names;
  }
}
