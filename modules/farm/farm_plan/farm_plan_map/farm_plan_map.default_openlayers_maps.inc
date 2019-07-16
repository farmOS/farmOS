<?php
/**
 * @file
 * farm_plan_map.default_openlayers_maps.inc
 */

/**
 * Implements hook_default_openlayers_maps().
 */
function farm_plan_map_default_openlayers_maps() {
  $export = array();

  // Create a plan map.
  $machine_name = 'farm_plan_map';
  $ol_map = new stdClass();
  $ol_map->disabled = FALSE; /* Edit this to true to make a default ol_map disabled initially */
  $ol_map->api_version = 1;
  $ol_map->machine_name = $machine_name;
  $ol_map->name = 'Farm Plan Map';
  $ol_map->description = '';
  $ol_map->factory_service = 'openlayers.Map:OLMap';
  $ol_map->options = array(
    'width' => 'auto',
    'height' => '500px',
    'provideBlockLayerSwitcher' => 0,
    'view' => array(
      'center' => array(
        'lat' => 0,
        'lon' => 0,
      ),
      'rotation' => 0,
      'zoom' => 2,
      'minZoom' => 0,
      'maxZoom' => 24,
    ),
    'renderer' => 'canvas',
    'layers' => array(),
    'controls' => array(
      0 => 'openlayers_control_zoom',
      1 => 'openlayers_control_scaleline',
      2 => 'farm_map_control_layer_switcher',
      3 => 'farm_map_control_geolocate',
      4 => 'farm_map_control_geocoder',
    ),
    'interactions' => array(
      0 => 'openlayers_interaction_doubleclickzoom',
      1 => 'openlayers_interaction_dragpan',
      2 => 'openlayers_interaction_mousewheelzoom',
      3 => 'openlayers_interaction_pinchzoom',
    ),
    'components' => array(
      0 => 'farm_plan_map_layers',
      1 => 'farm_map_component_progress_bar',
    ),
  );
  $export[$machine_name] = $ol_map;

  return $export;
}
