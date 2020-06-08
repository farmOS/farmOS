<?php
/**
 * @file
 * Farm Map hooks implemented by the Farm Mapknitter module.
 */

/**
 * Implements hook_farm_map_behaviors().
 */
function farm_mapknitter_farm_map_behaviors() {
  return array(
    'mapknitter' => array(
      'js' => 'farmOS.map.behaviors.mapknitter.js',
    ),
  );
}

/**
 * Implements hook_farm_map_behavior_settings().
 */
function farm_mapknitter_farm_map_behavior_settings($behavior) {
  $settings = array();
  if ($behavior == 'mapknitter') {
    $settings['slug'] = variable_get('farm_mapknitter_slug', '');
    $settings['title'] = variable_get('farm_mapknitter_title', '');
  }
  return $settings;
}

/**
 * Implements hook_farm_map_view().
 */
function farm_mapknitter_farm_map_view($name, $element) {

  // If a MapKnitter slug and title are set, add the layer to all farmOS maps.
  if (variable_get('farm_mapknitter_slug', FALSE) && variable_get('farm_mapknitter_title', FALSE)) {
    farm_map_add_behavior('mapknitter');
  }
}
