<?php
/**
 * @file
 * farm_map.default_openlayers_controls.inc
 */

/**
 * Implements hook_default_openlayers_controls().
 */
function farm_map_default_openlayers_controls() {
  $export = array();

  $ol_control = new stdClass();
  $ol_control->disabled = FALSE; /* Edit this to true to make a default ol_control disabled initially */
  $ol_control->api_version = 1;
  $ol_control->machine_name = 'farm_map_control_geocoder';
  $ol_control->name = 'Farm Map: Geocoder';
  $ol_control->description = '';
  $ol_control->factory_service = 'openlayers.Control:OLGeocoder';
  $ol_control->options = array();
  $export['farm_map_control_geocoder'] = $ol_control;

  $ol_control = new stdClass();
  $ol_control->disabled = FALSE; /* Edit this to true to make a default ol_control disabled initially */
  $ol_control->api_version = 1;
  $ol_control->machine_name = 'farm_map_control_geofield';
  $ol_control->name = 'Farm Map: Geofield toolbar';
  $ol_control->description = '';
  $ol_control->factory_service = 'openlayers.Control:Geofield';
  $ol_control->options = array(
    'draw' => array(
      'Point' => 'Point',
      'LineString' => 'LineString',
      'Polygon' => 'Polygon',
      'Circle' => 'Circle',
      'MultiPoint' => 0,
      'MultiLineString' => 0,
      'MultiPolygon' => 0,
      'Triangle' => 0,
      'Square' => 0,
      'Box' => 0,
    ),
    'actions' => array(
      'Edit' => 'Edit',
      'Move' => 'Move',
      'Clear' => 'Clear',
    ),
    'options' => array(
      'Snap' => 0,
    ),
  );
  $export['farm_map_control_geofield'] = $ol_control;

  $ol_control = new stdClass();
  $ol_control->disabled = FALSE; /* Edit this to true to make a default ol_control disabled initially */
  $ol_control->api_version = 1;
  $ol_control->machine_name = 'farm_map_control_geolocate';
  $ol_control->name = 'Farm Map: Geolocate';
  $ol_control->description = '';
  $ol_control->factory_service = 'openlayers.Control:Geolocate';
  $ol_control->options = array(
    'zoom' => 18,
  );
  $export['farm_map_control_geolocate'] = $ol_control;

  $ol_control = new stdClass();
  $ol_control->disabled = FALSE; /* Edit this to true to make a default ol_control disabled initially */
  $ol_control->api_version = 1;
  $ol_control->machine_name = 'farm_map_control_layer_switcher';
  $ol_control->name = 'Farm Map: Layer Switcher';
  $ol_control->description = '';
  $ol_control->factory_service = 'openlayers.Control:OL3LayerSwitcher';
  $ol_control->options = array(
    'collapsible' => 0,
  );
  $export['farm_map_control_layer_switcher'] = $ol_control;

  return $export;
}
