<?php

/**
 * @file
 * Post update functions for farm_kml module.
 */

use Drupal\system\Entity\Action;

/**
 * Move KML export actions to new farm_export_kml module.
 */
function farm_kml_post_update_move_kml_export_actions(&$sandbox = NULL) {

  // Delete the existing KML export action config entities.
  $configs = Action::loadMultiple(['asset_kml_action', 'log_kml_action']);
  foreach ($configs as $config) {
    $config->delete();
  }

  // Install the farm_export_kml module. This will recreate the actions.
  if (!\Drupal::service('module_handler')->moduleExists('farm_export_kml')) {
    \Drupal::service('module_installer')->install(['farm_export_kml']);
  }
}
