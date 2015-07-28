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
  $ol_component->machine_name = 'farm_area_component_area_details_popup';
  $ol_component->name = 'Farm Area: Area Details Popup';
  $ol_component->description = '';
  $ol_component->factory_service = 'openlayers.Component:Popup';
  $ol_component->options = array(
    'layers' => array(
      'farm_area_layer_areas' => 'farm_area_layer_areas',
    ),
    'positioning' => 'top-left',
    'autoPan' => 0,
    'autoPanAnimation' => '1000',
    'autoPanMargin' => '20',
  );
  $export['farm_area_component_area_details_popup'] = $ol_component;

  $ol_component = new stdClass();
  $ol_component->disabled = FALSE; /* Edit this to true to make a default ol_component disabled initially */
  $ol_component->api_version = 1;
  $ol_component->machine_name = 'farm_area_component_areas_zoom_to_source';
  $ol_component->name = 'Farm Area: Areas Zoom to Source';
  $ol_component->description = 'Automatically zoom to the areas source when the map is first built.';
  $ol_component->factory_service = 'openlayers.Component:ZoomToSource';
  $ol_component->options = array(
    'source' => array(
      'farm_area_source_property' => 'farm_area_source_property',
    ),
    'animations' => array(
      'pan' => 500,
      'zoom' => 500,
    ),
    'zoom' => 'auto',
    'enableAnimations' => 1,
    'max_zoom' => 20,
    'process_once' => 1,
  );
  $export['farm_area_component_areas_zoom_to_source'] = $ol_component;

  return $export;
}
