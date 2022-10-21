<?php

/**
 * @file
 * Updates farm_entity_fields module.
 */

/**
 * Install farm_parent module.
 */
function farm_entity_fields_post_update_enable_farm_parent(&$sandbox = NULL) {
  if (!\Drupal::service('module_handler')->moduleExists('farm_parent')) {
    \Drupal::service('module_installer')->install(['farm_parent']);
  }
}
