<?php

/**
 * @file
 * Post update functions for Data Stream Notifications module.
 */

/**
 * Install farmOS Notifications module.
 */
function data_stream_notification_post_update_enable_farm_notification(&$sandbox = NULL) {
  if (!\Drupal::service('module_handler')->moduleExists('farm_notification')) {
    \Drupal::service('module_installer')->install(['farm_notification']);
  }
}
