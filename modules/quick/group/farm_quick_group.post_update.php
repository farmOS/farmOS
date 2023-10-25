<?php

/**
 * @file
 * Post update hooks for the farm_quick_group module.
 */

use Drupal\system\Entity\Action;

/**
 * Install system.action.quick_group.
 */
function farm_quick_group_post_update_install_quick_group_action(&$sandbox) {
  $config = Action::create([
    'id' => 'quick_group',
    'label' => 'Assign group membership',
    'type' => 'asset',
    'plugin' => 'quick_group',
    'dependencies' => [
      'module' => [
        'asset',
        'farm_quick_group',
      ],
    ],
  ]);
  $config->save();
}
