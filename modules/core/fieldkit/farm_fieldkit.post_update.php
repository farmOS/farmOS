<?php

/**
 * @file
 * Post update functions for farm_fieldkit module.
 */

/**
 * Enable simple oauth password grant.
 */
function farm_fieldkit_post_update_enable_password_grant(&$sandbox = NULL) {
  if (!\Drupal::service('module_handler')->moduleExists('simple_oauth_password_grant')) {
    \Drupal::service('module_installer')->install(['simple_oauth_password_grant']);
  }
}
