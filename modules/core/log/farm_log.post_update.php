<?php

/**
 * @file
 * Post update hooks for the farm_log module.
 */

declare(strict_types=1);

use Drupal\system\Entity\Action;

/**
 * Implements hook_removed_post_updates().
 */
function farm_log_removed_post_updates() {
  return [
    'farm_log_post_update_farm_log_workflow' => '4.x',
  ];
}

/**
 * Move asset_add_log_action to farm_log_asset module.
 */
function farm_log_post_update_move_asset_add_log_action(&$sandbox) {

  // Update the action's dependency from farm_log to farm_log_asset.
  $action = Action::load('asset_add_log_action');
  if (!is_null($action)) {
    $dependencies = $action->getDependencies();
    if (($key = array_search('farm_log', $dependencies['module'])) !== FALSE) {
      unset($dependencies['module'][$key]);
      $dependencies['module'][] = 'farm_log_asset';
      $action->set('dependencies', $dependencies);
      $action->save();
    }
  }
}

/**
 * Add 'Mark As Abandoned' option to actions.
 */
function farm_log_post_update_add_abandoned_status_action(&$sandbox) {

  // Create action for assigning abandoned status to logs.
  $action = Action::create([
    'id' => 'log_mark_as_abandoned_action',
    'label' => t('Mark as abandoned'),
    'type' => 'log',
    'plugin' => 'log_mark_as_abandoned_action',
    'configuration' => [],
    'dependencies' => [
      'module' => [
        'farm_log',
        'log',
      ],
    ],
  ]);
  $action->save();
}
