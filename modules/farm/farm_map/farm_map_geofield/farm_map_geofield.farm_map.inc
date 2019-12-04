<?php
/**
 * @file
 * Farm Map hooks implemented by the Farm Map Geofield module.
 */

/**
 * Implements hook_farm_map_behaviors().
 */
function farm_map_geofield_farm_map_behaviors() {
  return array(
    'geofield' => array(
      'js' => 'js/farmOS.map.behaviors.geofield.js',
    ),
  );
}

/**
 * Implements hook_farm_map_view().
 */
function farm_map_geofield_farm_map_view($name, $element) {

  // Add geofield behavior to the geofield widget map.
  if ($name == 'farm_map_geofield_widget') {
    farm_map_add_behavior('geofield');
  }
}
