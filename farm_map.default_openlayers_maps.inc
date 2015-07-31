<?php
/**
 * @file
 * farm_map.default_openlayers_maps.inc
 */

/**
 * Implements hook_default_openlayers_maps().
 */
function farm_map_default_openlayers_maps() {
  $export = array();

  $ol_map = new stdClass();
  $ol_map->disabled = FALSE; /* Edit this to true to make a default ol_map disabled initially */
  $ol_map->api_version = 1;
  $ol_map->machine_name = 'farm_map_geofield';
  $ol_map->name = 'Farm Map: Geofield';
  $ol_map->description = 'Farm map of Geofield data.';
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
    'layers' => array(
      0 => 'farm_map_layer_group',
    ),
    'controls' => array(
      0 => 'openlayers_control_zoom',
      1 => 'openlayers_control_scaleline',
      2 => 'farm_map_control_layer_switcher',
      3 => 'farm_map_control_geolocate',
      /**
       * @todo
       * https://www.drupal.org/node/2543186
       */
//      4 => 'openlayers_control_fullscreen',
    ),
    'interactions' => array(
      0 => 'openlayers_interaction_doubleclickzoom',
      1 => 'openlayers_interaction_dragpan',
      2 => 'openlayers_interaction_mousewheelzoom',
      3 => 'openlayers_interaction_pinchzoom',
    ),
    'components' => array(
      0 => 'openlayers_geofield_component_geofield',
      1 => 'farm_map_component_geofield_zoom_to_source',
      2 => 'farm_map_component_progress_bar',
    ),
    'renderer' => 'canvas',
  );
  $export['farm_map_geofield'] = $ol_map;

  $ol_map = new stdClass();
  $ol_map->disabled = FALSE; /* Edit this to true to make a default ol_map disabled initially */
  $ol_map->api_version = 1;
  $ol_map->machine_name = 'farm_map_geofield_formatter';
  $ol_map->name = 'Farm Map: Geofield Formatter';
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
    'layers' => array(
      0 => 'farm_map_layer_group',
      1 => 'geofield_field_formatter_layer',
    ),
    'controls' => array(
      0 => 'openlayers_control_zoom',
      1 => 'openlayers_control_scaleline',
      2 => 'farm_map_control_layer_switcher',
      /**
       * @todo
       * https://www.drupal.org/node/2543186
       */
//      3 => 'openlayers_control_fullscreen',
    ),
    'interactions' => array(
      0 => 'openlayers_interaction_doubleclickzoom',
      1 => 'openlayers_interaction_dragpan',
      2 => 'openlayers_interaction_mousewheelzoom',
      3 => 'openlayers_interaction_pinchzoom',
    ),
    'components' => array(
      0 => 'farm_map_component_geofield_zoom_to_source',
      1 => 'farm_map_component_progress_bar',
    ),
  );
  $export['farm_map_geofield_formatter'] = $ol_map;

  return $export;
}
