<?php

/**
 * @file
 * Post update hooks for the farm_import_csv module.
 */

/**
 * Install farm_migrate as a dependency of farm_import_csv.
 */
function farm_import_csv_post_update_install_farm_migrate(&$sandbox) {
  if (!\Drupal::service('module_handler')->moduleExists('farm_migrate')) {
    \Drupal::service('module_installer')->install(['farm_migrate']);
  }
}
