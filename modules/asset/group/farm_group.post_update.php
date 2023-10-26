<?php

/**
 * @file
 * Post update hooks for the farm_group module.
 */

use Drupal\system\Entity\Action;

/**
 * Uninstall system.action.asset_group_action.
 */
function farm_group_post_update_uninstall_asset_group_action(&$sandbox) {
  $config = Action::load('asset_group_action');
  if (!empty($config)) {
    $config->delete();
  }
}
