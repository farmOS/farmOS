<?php
/**
 * @file
 * farm_plan_map.default_openlayers_components.inc
 */

/**
 * Implements hook_default_openlayers_components().
 */
function farm_plan_map_default_openlayers_components() {
  $export = array();

  $javascript = file_get_contents(drupal_get_path('module', 'farm_plan_map') . '/js/farm_plan_map_layers.js');

  // Define an inline javascript component that adds area and asset layers to
  // a plan map.
  /**
   * @todo
   * This is just a placeholder until we migrate to
   * https://github.com/mstenta/farmOS-map
   */
  $ol_component = new stdClass();
  $ol_component->disabled = FALSE; /* Edit this to true to make a default ol_component disabled initially */
  $ol_component->api_version = 1;
  $ol_component->machine_name = 'farm_plan_map_layers';
  $ol_component->name = 'Farm Plan Map: Layers';
  $ol_component->description = 'Adds plan asset and area layers to the map via inline JavaScript, so that the plan ID can be appended to the GeoJSON source URLs.';
  $ol_component->factory_service = 'openlayers.Component:InlineJS';
  $ol_component->options = array(
    'javascript' => $javascript,
  );
  $export['farm_plan_map_layers'] = $ol_component;

  return $export;
}
