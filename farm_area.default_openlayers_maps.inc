<?php
/**
 * @file
 * farm_area.default_openlayers_maps.inc
 */

/**
 * Implements hook_default_openlayers_maps().
 */
function farm_area_default_openlayers_maps() {
  $export = array();

  $ol_map = new stdClass();
  $ol_map->disabled = FALSE; /* Edit this to true to make a default ol_map disabled initially */
  $ol_map->api_version = 1;
  $ol_map->machine_name = 'farm_areas';
  $ol_map->name = 'Farm Areas';
  $ol_map->description = '';
  $ol_map->factory_service = 'openlayers.Map:OLMap';
  $ol_map->options = array(
    'width' => 'auto',
    'height' => '500px',
    'view' => array(
      'center' => array(
        'lat' => -96,
        'lon' => 40,
      ),
      'rotation' => 0,
      'zoom' => 4,
      'minZoom' => 0,
      'maxZoom' => 20,
    ),
    'renderer' => 'canvas',
    'layers' => array(
      0 => 'farm_map_layer_group',
      1 => 'farm_area_layer_group',
    ),
    'controls' => array(
      0 => 'openlayers_control_fullscreen',
      1 => 'openlayers_control_zoom',
      2 => 'openlayers_control_scaleline',
      3 => 'farm_map_control_layer_switcher',
      4 => 'farm_map_control_geolocate',
    ),
    'components' => array(
      0 => 'farm_area_component_areas_zoom_to_source',
      1 => 'farm_area_component_area_details_popup',
    ),
    'interactions' => array(
      0 => 'openlayers_interaction_doubleclickzoom',
      1 => 'openlayers_interaction_dragpan',
      2 => 'openlayers_interaction_mousewheelzoom',
    ),
  );
  $export['farm_areas'] = $ol_map;

  return $export;
}
