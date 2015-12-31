<?php
/**
 * @file
 * farm_soil.default_openlayers_maps.inc
 */

/**
 * Implements hook_default_openlayers_maps_alter().
 */
function farm_soil_default_openlayers_maps_alter(&$maps) {

  // Add the soil layers to farm maps.
  $farm_maps = array(
    'farm_areas',
    'farm_map_geofield',
    'farm_map_geofield_formatter',
  );

  // Iterate over the farm maps and see if they exist.
  foreach ($farm_maps as $farm_map) {

    // If this map exists in the list of existing maps...
    if (array_key_exists($farm_map, $maps)) {

      // Add the soil layer group.
      $maps[$farm_map]->options['layers'][] = 'farm_soil_layer_group';
    }
  }
}
