<?php
/**
 * @file
 * Farm Map hooks implemented by the Farm Area module.
 */

/**
 * Implements hook_farm_map_behaviors().
 */
function farm_area_farm_map_behaviors() {
  return array(
    'areas' => array(
      'js' => 'js/farmOS.map.behaviors.areas.js',
    ),
    'areas_combined' => array(
      'js' => 'js/farmOS.map.behaviors.areas_combined.js',
    ),
  );
}

/**
 * Implements hook_farm_map_behavior_settings().
 */
function farm_area_farm_map_behavior_settings($behavior) {
  $settings = array();
  if ($behavior == 'areas') {

    // Ensure that this only runs once. It adds a JS settings that describes the
    // available area types. This gets merged into other settings in
    // drupal_add_js(), which can result in the area types being duplicated if
    // multiple maps are on the page.
    static $farm_areas_added = FALSE;
    if ($farm_areas_added) {
      return $settings;
    }
    $farm_areas_added = TRUE;

    // Get all area types.
    $area_types = farm_area_types();

    // Build an array of area type layer information, including the label, style,
    // and GeoJSON URL for each.
    $area_layers = array();
    foreach ($area_types as $key => $type) {
      $area_layers[] = array(
        'label' => $type['label'],
        'style' => $type['style'],
        'url' => url('farm/areas/geojson/' . $key),
      );
    }

    // Reverse the array order so that they are added to the map from bottom
    // to top.
    $area_layers = array_reverse($area_layers);

    // Add the area layers array as a setting.
    $settings['layers'] = $area_layers;
  }
  return $settings;
}

/**
 * Implements hook_farm_map_view().
 */
function farm_area_farm_map_view($name, $element) {

  // Add a farmOS map behavior that adds all area layers to the areas map.
  if ($name == 'farm_areas') {
    farm_map_add_behavior('popup');
    farm_map_add_behavior('areas', array('zoom' => TRUE));
  }

  // Show "all areas" layer in geofield maps.
  if (in_array($name,  array('farm_map_geofield', 'farm_map_geofield_widget'))) {
    farm_map_add_behavior('areas_combined', array('zoom' => FALSE));
  }
}
