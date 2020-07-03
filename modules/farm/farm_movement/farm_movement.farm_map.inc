<?php
/**
 * @file
 * Farm Map hooks implemented by the Farm Movement module.
 */

/**
 * Implements hook_farm_map_behaviors().
 */
function farm_movement_farm_map_behaviors() {
  return array(
    'assets_full' => array(
      'js' => 'js/farmOS.map.behaviors.assets_full.js',
    ),
    'assets_cluster' => array(
      'js' => 'js/farmOS.map.behaviors.assets_cluster.js',
    ),
    'move' => array(
      'js' => 'js/farmOS.map.behaviors.move.js',
    ),
  );
}

/**
 * Implements hook_farm_map_behavior_settings().
 */
function farm_movement_farm_map_behavior_settings($behavior) {
  $settings = array();
  if (in_array($behavior, array('assets_full', 'assets_cluster'))) {

    // Ensure that this only runs once per behavior. It adds a JS settings that
    // describes the available asset types. This gets merged into other settings
    // in drupal_add_js(), which can result in the asset types being duplicated
    // if multiple maps are on the page.
    static $farm_assets_added = array();
    if (!empty($farm_assets_added[$behavior])) {
      return $settings;
    }
    $farm_assets_added[$behavior] = TRUE;

    // Get all asset types.
    $asset_types = farm_asset_types();

    // Build an array of asset type information, including the label and GeoJSON
    // URLs for both full and centroid geometries.
    $types = array();
    foreach ($asset_types as $key => $type) {
      $types[] = array(
        'type' => $key,
        'label' => $type->label,
        'full' => url('farm/assets/geojson/full/' . $key),
        'centroid' => url('farm/assets/geojson/centroid/' . $key),
      );
    }

    // Reverse the array order so that they are added to the map from bottom
    // to top.
    $types = array_reverse($types);

    // Add the asset types array as a setting.
    $settings['types'] = $types;
  }
  return $settings;
}


/**
 * Implements hook_farm_map_view().
 */
function farm_movement_farm_map_view($name, $element) {

  // Add farmOS map behaviors that add asset geometries and clusters, as well as
  // the combined area geometry and popups.
  if ($name == 'farm_assets') {
    farm_map_add_behavior('areas_combined', array('zoom' => FALSE));
    farm_map_add_behavior('assets_full', array('zoom' => TRUE));
    farm_map_add_behavior('assets_cluster');
    farm_map_add_behavior('popup');
  }

  // Add move behavior to movement maps.
  if ($name == 'farm_movement') {
    farm_map_add_behavior('move');
  }
}
