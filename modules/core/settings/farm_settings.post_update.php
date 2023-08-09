<?php

/**
 * @file
 * Post update functions for farm_settings module.
 */

/**
 * Install farmOS Setup module.
 */
function farm_settings_post_update_install_farm_setup(&$sandbox = NULL) {
  if (!\Drupal::service('module_handler')->moduleExists('farm_setup')) {
    \Drupal::service('module_installer')->install(['farm_setup']);
  }
}
