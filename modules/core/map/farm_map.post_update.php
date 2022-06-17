<?php

/**
 * @file
 * Post update hooks for the farm_map module.
 */

use Drupal\farm_map\Entity\MapBehavior;
use Drupal\farm_map\Entity\MapType;

/**
 * Generalize geofield map types and behavior.
 */
function farm_map_post_update_generalize_geofield_map_types_behavior(&$sandbox) {

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

  // Delete the geofield_widget map type.
  $geofield_widget_map_type = MapType::load('geofield_widget');
  $geofield_widget_map_type->delete();
}
