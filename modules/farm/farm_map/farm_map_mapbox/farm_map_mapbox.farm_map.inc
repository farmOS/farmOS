<?php
/**
 * @file
 * Farm Map hooks implemented by the Farm Map Mapbox module.
 */

/**
 * Implements hook_farm_map_behaviors().
 */
function farm_map_mapbox_farm_map_behaviors() {
  return array(
    'mapbox' => array(
      'js' => 'js/farmOS.map.behaviors.mapbox.js',
    ),
  );
}

/**
 * Implements hook_farm_map_behavior_settings().
 */
function farm_map_mapbox_farm_map_behavior_settings($behavior) {
  $settings = array();
  if ($behavior == 'mapbox') {
    $settings['api_key'] = variable_get('farm_map_mapbox_api_key', FALSE);
  }
  return $settings;
}

/**
 * Implements hook_farm_map_view().
 */
function farm_map_mapbox_farm_map_view($name, $element) {

  // If a Mapbox API key is set, enable Mapbox layers in all farm maps.
  if (!empty(variable_get('farm_map_mapbox_api_key', FALSE))) {
    farm_map_add_behavior('mapbox');
  }
}
