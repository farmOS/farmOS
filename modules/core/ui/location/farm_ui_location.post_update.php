<?php

/**
 * @file
 * Post update functions for farm_ui_location module.
 */

use Drupal\farm_map\Entity\MapType;

/**
 * Add farmOS locations map type.
 */
function farm_ui_location_post_update_add_locations_map_type(&$sandbox = NULL) {

  // Create locations map type.
  $map_type = MapType::create([
    'id' => 'locations',
    'label' => 'Locations',
    'description' => 'The farmOS locations map.',
    'behaviors' => [
      'location',
    ],
    'options' => [],
    'dependencies' => [
      'enforced' => [
        'module' => [
          'farm_ui_location',
        ],
      ],
    ],
  ]);
  $map_type->save();
}
