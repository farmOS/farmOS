<?php

/**
 * @file
 * Post update functions for farm_ui_map module.
 */

use Drupal\farm_map\Entity\MapBehavior;
use Drupal\farm_map\Entity\MapType;

/**
 * Create farmOS-map locations behavior, add it to dashboard map.
 */
function farm_ui_map_post_update_locations_behavior(&$sandbox = NULL) {

  // Create locations behavior.
  $behavior = MapBehavior::create([
    'id' => 'locations',
    'label' => 'Location asset layers',
    'description' => 'Displays location asset geometries in layers by asset type.',
    'library' => '',
    'settings' => [],
    'dependencies' => [
      'enforced' => [
        'module' => [
          'farm_ui_map',
        ],
      ],
    ],
  ]);
  $behavior->save();

  // Add the locations behavior to the dashboard map type.
  $dashboard = MapType::load('dashboard');
  $behaviors = $dashboard->getMapBehaviors();
  $behaviors[] = 'locations';
  $dashboard->set('behaviors', $behaviors);
  $dashboard->save();
}
