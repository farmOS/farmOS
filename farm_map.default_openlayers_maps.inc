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
    'height' => '400px',
    'contextualLinks' => 1,
    'provideBlock' => 1,
    'provideIframe' => 1,
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
      0 => 'geofield_field_formatter_layer',
      1 => 'farm_map_layer_google_hybrid',
    ),
    'controls' => array(
      0 => 'openlayers_control_fullscreen',
      1 => 'openlayers_control_zoom',
      2 => 'openlayers_control_scaleline',
    ),
    'interactions' => array(
      0 => 'openlayers_interaction_doubleclickzoom',
      1 => 'openlayers_interaction_dragpan',
      2 => 'openlayers_interaction_mousewheelzoom',
    ),
    'components' => array(
      0 => 'openlayers_geofield_component_geofield',
      1 => 'farm_map_component_geofield_zoom_to_source',
    ),
    'sources' => array(
      0 => 'geofield_field_formatter_source',
      1 => 'farm_map_source_google_hybrid',
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
    'height' => '300px',
    'contextualLinks' => 1,
    'provideBlock' => 1,
    'provideIframe' => 1,
    'view' => array(
      'center' => array(
        'lat' => 0,
        'lon' => 0,
      ),
      'rotation' => 0,
      'zoom' => 2,
    ),
    'layers' => array(
      0 => 'geofield_field_formatter_layer',
      1 => 'farm_map_layer_google_hybrid',
    ),
    'controls' => array(
      0 => 'openlayers_control_fullscreen',
      1 => 'openlayers_control_zoom',
      2 => 'openlayers_control_scaleline',
    ),
    'interactions' => array(
      0 => 'openlayers_interaction_doubleclickzoom',
      1 => 'openlayers_interaction_dragpan',
      2 => 'openlayers_interaction_mousewheelzoom',
    ),
    'components' => array(
      0 => 'farm_map_component_geofield_zoom_to_source',
    ),
    'sources' => array(
      0 => 'geofield_field_formatter_source',
      1 => 'farm_map_source_google_hybrid',
    ),
  );
  $export['farm_map_geofield_formatter'] = $ol_map;

  return $export;
}
