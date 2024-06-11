<?php

/**
 * @file
 * Post update functions for farm_ui module.
 */

/**
 * Install the farmOS UI Timeline module.
 */
function farm_ui_post_update_install_farm_ui_timeline(&$sandbox = NULL) {
  if (!\Drupal::service('module_handler')->moduleExists('farm_ui_timeline')) {
    \Drupal::service('module_installer')->install(['farm_ui_timeline']);
  }
}
