<?php
/**
 * @file
 * farm_map.default_openlayers_components.inc
 */

/**
 * Implements hook_default_openlayers_components().
 */
function farm_map_default_openlayers_components() {
  $export = array();

  $ol_component = new stdClass();
  $ol_component->disabled = FALSE; /* Edit this to true to make a default ol_component disabled initially */
  $ol_component->api_version = 1;
  $ol_component->machine_name = 'farm_map_component_geofield_widget';
  $ol_component->name = 'Farm Map: Geofield widget component';
  $ol_component->description = '';
  $ol_component->factory_service = 'openlayers.Component:GeofieldWidget';
  $ol_component->options = array(
    'featureLimit' => 0,
    'showInputField' => 1,
    'inputFieldName' => 'geom',
    'dataProjection' => 'EPSG:4326',
    'editStyle' => 'openlayers_geofield_style_edit',
    'editLayer' => 'openlayers_geofield_layer_widget',
    'editControl' => 'farm_map_control_geofield',
    'dataType' => array(
      'WKT' => 'WKT',
      'GeoJSON' => 0,
      'KML' => 0,
      'GPX' => 0,
    ),
  );
  $export['farm_map_component_geofield_widget'] = $ol_component;

  $ol_component = new stdClass();
  $ol_component->disabled = FALSE; /* Edit this to true to make a default ol_component disabled initially */
  $ol_component->api_version = 1;
  $ol_component->machine_name = 'farm_map_component_geofield_zoom_to_source';
  $ol_component->name = 'Farm Map: Geofield Zoom to Source';
  $ol_component->description = 'Automatically zoom to a geofield source when the map is first built.';
  $ol_component->factory_service = 'openlayers.Component:ZoomToSource';
  $ol_component->options = array(
    'source' => array(
      'openlayers_geofield_source_vector' => 'openlayers_geofield_source_vector',
    ),
    'duration' => 500,
    'zoom' => 'auto',
    'max_zoom' => 20,
    'process_once' => 1,
  );
  $export['farm_map_component_geofield_zoom_to_source'] = $ol_component;

  $ol_component = new stdClass();
  $ol_component->disabled = FALSE; /* Edit this to true to make a default ol_component disabled initially */
  $ol_component->api_version = 1;
  $ol_component->machine_name = 'farm_map_component_progress_bar';
  $ol_component->name = 'Farm Map: Progress Bar';
  $ol_component->description = '';
  $ol_component->factory_service = 'openlayers.Component:ProgressBar';
  $ol_component->options = array();
  $export['farm_map_component_progress_bar'] = $ol_component;

  return $export;
}
