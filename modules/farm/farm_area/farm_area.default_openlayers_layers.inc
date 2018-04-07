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

  // Load all available area types.
  $area_types = farm_area_types();

  // Start an array of layer names.
  $layer_names = array();

  // Iterate through the available types, and add a layer for each one.
  foreach ($area_types as $key => $type) {

    // Generate a machine name.
    $layer_name = 'farm_area_layer_' . $key;

    // Add the machine name to the array for later.
    $layer_names[] = $layer_name;

    // If zoom levels were defined, convert them to the correct format.
    $zoomActivity = '';
    if (!empty($type['zoom_levels'])) {
      $zoomActivity = implode("\n", $type['zoom_levels']);
    }

    // Define the layer.
    $ol_layer = new stdClass();
    $ol_layer->disabled = FALSE; /* Edit this to true to make a default ol_layer disabled initially */
    $ol_layer->api_version = 1;
    $ol_layer->machine_name = $layer_name;
    $ol_layer->name = $type['label'];
    $ol_layer->description = '';
    $ol_layer->factory_service = 'openlayers.Layer:Vector';
    $ol_layer->options = array(
      'source' => 'farm_area_source_' . $key,
      'visible' => 1,
      'zoomActivity' => $zoomActivity,
      'style' => $type['style'],
    );

    // Add the source to the export.
    $export[$layer_name] = $ol_layer;
  }

  // Define a layer group for individual area type layers.
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
    'grouptitle' => 'Area layers',
    'grouplayers' => $layer_names,
  );
  $export['farm_area_layer_group'] = $ol_layer;

  // Define a layer containing all farm areas, regardless of type.
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
    'style' => 'farm_map_style_grey',
  );
  $export['farm_area_layer_areas'] = $ol_layer;

  // Define a layer group for the "all areas" layer.
  $ol_layer = new stdClass();
  $ol_layer->disabled = FALSE; /* Edit this to true to make a default ol_layer disabled initially */
  $ol_layer->api_version = 1;
  $ol_layer->machine_name = 'farm_area_layer_areas_group';
  $ol_layer->name = 'Farm Area Layer Group (all areas)';
  $ol_layer->description = '';
  $ol_layer->factory_service = 'openlayers.Layer:Group';
  $ol_layer->options = array(
    'source' => '',
    'visible' => 1,
    'grouptitle' => 'Areas layer',
    'grouplayers' => array(
      'farm_area_layer_areas',
    ),
  );
  $export['farm_area_layer_areas_group'] = $ol_layer;

  return $export;
}
