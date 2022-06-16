<?php

/**
 * @file
 * Post update hooks for the farm_map module.
 */

use Drupal\farm_map\Entity\MapBehavior;

/**
 * Rename geofield map behavior to input.
 */
function farm_map_post_update_map_input_behavior(&$sandbox) {

  // Create the new input behavior.
  $input_behavior = MapBehavior::create([
    'id' => 'input',
    'label' => 'Input',
    'description' => 'Syncs editable map layer data into a form input.',
    'library' => 'farm_map/behavior_input',
    'settings' => [],
    'dependencies' => [
      'enforced' => [
        'module' => [
          'farm_map',
        ],
      ],
    ],
  ]);
  $input_behavior->save();

  // Delete the geofield behavior.
  $geofield_behavior = MapBehavior::load('geofield');
  $geofield_behavior->delete();
}
