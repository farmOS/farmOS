<?php

/**
 * @file
 * Post update hooks for the farm_structure module.
 */

use Drupal\farm_structure\Entity\FarmStructureType;

/**
 * Add "Other" structure type configuration.
 */
function farm_structure_post_update_add_other_structure_type(&$sandbox) {
  $type = FarmStructureType::create([
    'id' => 'other',
    'label' => 'Other',
    'dependencies' => [
      'enforced' => [
        'module' => [
          'farm_structure',
        ],
      ],
    ],
  ]);
  $type->save();
}
