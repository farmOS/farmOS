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
  $ol_map->name = 'Farm Map: Geofield Widget';
  $ol_map->description = 'Farm map for use in Geofield widgets.';
  $ol_map->factory_service = 'openlayers.Map:OLMap';
  $ol_map->options = array(
    'width' => 'auto',
    'height' => '500px',
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
    'layers' => array(
      0 => 'farm_map_geofield_widget_layer_group',
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
      0 => 'farm_map_component_geofield_widget',
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
  $ol_map->description = 'Farm map for use in Geofield formatters.';
  $ol_map->factory_service = 'openlayers.Map:OLMap';
  $ol_map->options = array(
    'width' => 'auto',
    'height' => '500px',
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
    'layers' => array(
      0 => 'farm_map_geofield_formatter_layer_group',
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
    'renderer' => 'canvas',
  );
  $export['farm_map_geofield_formatter'] = $ol_map;

  return $export;
}

/**
 * Implements hook_default_openlayers_maps_alter().
 */
function farm_map_default_openlayers_maps_alter(&$maps) {

  // This module implements hook_module_implements_alter() to ensure that this
  // function always runs last, so we can ensure that any modifications made
  // below will take priority over any other implementations of this hook.
  // @see farm_map_module_implements_alter()

  // Iterate through all maps.
  foreach ($maps as &$map) {

    // Only alter maps that have a farm_* namespace.
    if (substr($map->machine_name, 0, 5) == 'farm_') {

      // Add "base layers" group layer to the end all maps.
      $map->options['layers'][] = 'farm_map_layer_group';

      // Reverse the order of layers.
      // This is necessary because of the following Openlayers module commit:
      // http://cgit.drupalcode.org/openlayers/commit/?id=321aee3
      $map->options['layers'] = array_reverse($map->options['layers']);
    }
  }
}
