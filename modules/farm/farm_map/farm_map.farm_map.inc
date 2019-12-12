<?php
/**
 * @file
 * Farm Map hooks implemented by the Farm Map module.
 */

/**
 * Implements hook_farm_map_behaviors().
 */
function farm_map_farm_map_behaviors() {
  return array(
    'popup' => array(
      'js' => 'js/farmOS.map.behaviors.popup.js',
    ),
    'wkt' => array(
      'js' => 'js/farmOS.map.behaviors.wkt.js',
    ),
  );
}

/**
 * Implements hook_farm_map_view().
 */
function farm_map_farm_map_view($name, $element) {

  // If WKT was provided in the element, show it in the map.
  if (isset($element['#wkt'])) {
    $settings = array(
      'zoom' => TRUE,
    );
    farm_map_add_behavior('wkt', $settings);
  }
}
