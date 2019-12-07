<?php
/**
 * @file
 * Farm Map hooks implemented by the Farm Area Generate module.
 */

/**
 * Implements hook_farm_map_behaviors().
 */
function farm_area_generate_farm_map_behaviors() {
  return array(
    'area_generate' => array(
      'js' => 'js/farmOS.map.behaviors.area_generate.js',
    ),
  );
}

/**
 * Implements hook_farm_map_view().
 */
function farm_area_generate_farm_map_view($name, $element) {

  // Add area generator behaviors.
  if ($name == 'farm_area_generate') {
    farm_map_add_behavior('popup');
    farm_map_add_behavior('areas', array('zoom' => TRUE));
    farm_map_add_behavior('area_generate');
  }
}
