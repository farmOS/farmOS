<?php

/**
 * @file
 * Hooks provided by farm_update.
 *
 * This file contains no working PHP code; it exists to provide additional
 * documentation for doxygen as well as to document hooks in the standard
 * Drupal manner.
 */

/**
 * @addtogroup hooks
 * @{
 */

/**
 * Specify config items that should be excluded from automatic updates.
 *
 * @return array
 *   An array of config item names.
 */
function hook_farm_update_exclude_config() {
  return [
    'views.view.farm_log',
    'asset.type.structure',
  ];
}

/**
 * @} End of "addtogroup hooks".
 */
