<?php

/**
 * @file
 * Post update functions for farm_import module.
 */

/**
 * Install farmOS Setup module.
 */
function farm_import_post_update_install_farm_setup(&$sandbox = NULL) {
  if (!\Drupal::service('module_handler')->moduleExists('farm_setup')) {
    \Drupal::service('module_installer')->install(['farm_setup']);
  }
}
