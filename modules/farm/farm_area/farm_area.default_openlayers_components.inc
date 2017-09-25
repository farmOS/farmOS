<?php
/**
 * @file
 * farm_area.default_openlayers_components.inc
 */

/**
 * Implements hook_default_openlayers_components().
 */
function farm_area_default_openlayers_components() {
  $export = array();

  // Get farm area types.
  $area_types = farm_area_types();

  // Create an array of area type source and layer names.
  $area_type_sources = array();
  $area_type_layers = array();
  foreach ($area_types as $type => $info) {
    $layer = 'farm_area_layer_' . $type;
    $area_type_layers[$layer] = $layer;
    $source = 'farm_area_source_' . $type;
    $area_type_sources[$source] = $source;
  }

  // Define an area details popup component that applies to all area type layers.
  $ol_component = new stdClass();
  $ol_component->disabled = FALSE; /* Edit this to true to make a default ol_component disabled initially */
  $ol_component->api_version = 1;
  $ol_component->machine_name = 'farm_area_component_area_details_popup';
  $ol_component->name = 'Farm Area: Area Details Popup';
  $ol_component->description = '';
  $ol_component->factory_service = 'openlayers.Component:Popup';
  $ol_component->options = array(
    'frontend_layers' => $area_type_layers,
    'positioning' => 'top-center',
    'autoPan' => 1,
    'autoPanAnimation' => '0',
    'autoPanMargin' => '20',
    'closer' => 1,
  );
  $export['farm_area_component_area_details_popup'] = $ol_component;

  // Define a zoom-to-source component that applies to all area sources.
  $ol_component = new stdClass();
  $ol_component->disabled = FALSE; /* Edit this to true to make a default ol_component disabled initially */
  $ol_component->api_version = 1;
  $ol_component->machine_name = 'farm_area_component_areas_zoom_to_source';
  $ol_component->name = 'Farm Area: Areas Zoom to Source';
  $ol_component->description = 'Automatically zoom to the areas source when the map is first built.';
  $ol_component->factory_service = 'openlayers.Component:ZoomToSource';
  $ol_component->options = array(
    'source' => $area_type_sources,
    'duration' => 500,
    'zoom' => 'auto',
    'max_zoom' => 20,
    'process_once' => 0,
  );
  $export['farm_area_component_areas_zoom_to_source'] = $ol_component;

  return $export;
}

/**
 * Implements hook_default_openlayers_components_alter().
 */
function farm_area_default_openlayers_components_alter(&$components) {

  // Add the Farm Areas source to the Geofield Zoom-To-Source component.
  if (!empty($components['farm_map_component_geofield_zoom_to_source'])) {
    $components['farm_map_component_geofield_zoom_to_source']->options['source']['views_geojson_farm_areas_geojson_feed'] = 'views_geojson_farm_areas_geojson_feed';
  }
}
