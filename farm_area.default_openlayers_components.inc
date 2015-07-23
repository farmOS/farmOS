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

  $ol_component = new stdClass();
  $ol_component->disabled = FALSE; /* Edit this to true to make a default ol_component disabled initially */
  $ol_component->api_version = 1;
  $ol_component->machine_name = 'farm_area_component_areas_zoom_to_source';
  $ol_component->name = 'Farm Area Component: Areas Zoom to Source';
  $ol_component->description = 'Automatically zoom to the areas source when the map is first built.';
  $ol_component->factory_service = 'openlayers.Component:ZoomToSource';
  $ol_component->options = array(
    'source' => 'views_geojson_farm_areas_geojson_feed',
    'animations' => array(
      'pan' => '500',
      'zoom' => '500',
    ),
    'zoom' => 'auto',
    'enableAnimations' => 1,
    'max_zoom' => '0',
    'process_once' => 1,
  );
  $export['farm_area_component_areas_zoom_to_source'] = $ol_component;

  return $export;
}
