<?php

/**
 * @file
 * Post update functions for farm_setup module.
 */

declare(strict_types=1);

/**
 * Uninstall the farm_settings module.
 */
function farm_setup_post_update_uninstall_farm_settings(&$sandbox) {

  // Uninstall the farm_settings module, if no other installed modules depend on
  // it.
  if (\Drupal::service('module_handler')->moduleExists('farm_settings')) {
    $modules = \Drupal::service('extension.list.module')->reset()->getList();
    $installed_dependents = [];
    if (!empty($modules['farm_settings']->required_by)) {
      foreach (array_keys($modules['farm_settings']->required_by) as $module) {
        if (\Drupal::service('module_handler')->moduleExists($module)) {
          $installed_dependents[] = $module;
        }
      }
    }
    if (empty($installed_dependents)) {
      \Drupal::service('module_installer')->uninstall(['farm_settings']);
    }
  }
}
