<?php

/**
 * @file
 * Post update hooks for the farm_import_csv module.
 */

declare(strict_types=1);

/**
 * Install farm_migrate as a dependency of farm_import_csv.
 */
function farm_import_csv_post_update_install_farm_migrate(&$sandbox) {
  if (!\Drupal::service('module_handler')->moduleExists('farm_migrate')) {
    \Drupal::service('module_installer')->install(['farm_migrate']);
  }
}

/**
 * Uninstall the Migrate Source UI module, unless other modules depend on it.
 */
function farm_import_csv_post_update_uninstall_migrate_source_ui(&$sandbox = NULL) {
  if (\Drupal::service('module_handler')->moduleExists('migrate_source_ui')) {
    $modules = \Drupal::service('extension.list.module')->reset()->getList();
    $installed_dependents = [];
    if (!empty($modules['migrate_source_ui']->required_by)) {
      foreach (array_keys($modules['migrate_source_ui']->required_by) as $module) {
        if (\Drupal::service('module_handler')->moduleExists($module)) {
          $installed_dependents[] = $module;
        }
      }
    }
    if (empty($installed_dependents)) {
      \Drupal::service('module_installer')->uninstall(['migrate_source_ui']);
    }
  }
}
