<?php

/**
 * @file
 * Post update functions for farm_settings module.
 */

declare(strict_types=1);

/**
 * Install the farm_api_oauth module.
 */
function farm_api_post_update_split_oauth_module(&$sandbox) {
  if (!\Drupal::service('module_handler')->moduleExists('farm_api_oauth')) {
    \Drupal::service('module_installer')->install(['farm_api_oauth']);
  }
}

/**
 * Implements hook_removed_post_updates().
 */
function farm_api_removed_post_updates() {
  return [
    'farm_api_post_update_remove_consumer_fields' => '4.x',
    'farm_api_post_update_enable_static_oauth2_scopes' => '4.x',
    'farm_api_post_update_enable_default_consumer_module' => '4.x',
    'farm_api_post_update_uninstall_jsonapi_extras' => '4.x',
  ];
}
