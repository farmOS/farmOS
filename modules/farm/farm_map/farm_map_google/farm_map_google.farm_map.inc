<?php
/**
 * @file
 * Farm Map hooks implemented by the Farm Map Google module.
 */

/**
 * Implements hook_farm_map_behaviors().
 */
function farm_map_google_farm_map_behaviors() {
  return array(
    'google' => array(
      'js' => 'js/farmOS.map.behaviors.google.js',
    ),
  );
}

/**
 * Implements hook_farm_map_view().
 */
function farm_map_google_farm_map_view($name, $element) {

  // If a Google API key is set, enable Google Maps layers in farm maps.
  $google_api_key = variable_get('farm_map_google_api_key', FALSE);
  if (!empty($google_api_key)) {

    // Add remote Google Maps JavaScript library, with the API key.
    $url = 'https://maps.googleapis.com/maps/api/js?v=3&key=' . $google_api_key;
    drupal_add_js($url, array('type' => 'external', 'weight' => -100));

    // Add a farmOS map behavior that will enable Google Maps.
    farm_map_add_behavior('google');
  }
}
