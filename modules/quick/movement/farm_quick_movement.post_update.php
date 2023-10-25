<?php

/**
 * @file
 * Post update hooks for the farm_quick_movement module.
 */

use Drupal\system\Entity\Action;

/**
 * Install system.action.quick_movement.
 */
function farm_quick_movement_post_update_install_quick_movement_action(&$sandbox) {
  $config = Action::create([
    'id' => 'quick_movement',
    'label' => 'Record movement',
    'type' => 'asset',
    'plugin' => 'quick_movement',
    'dependencies' => [
      'module' => [
        'asset',
        'farm_quick_movement',
      ],
    ],
  ]);
  $config->save();
}
