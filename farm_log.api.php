<?php

/**
 * @file
 * Hooks provided by farm_log.
 *
 * This file contains no working PHP code; it exists to provide additional
 * documentation for doxygen as well as to document hooks in the standard
 * Drupal manner.
 */

/**
 * @defgroup farm_log Farm log module integrations.
 *
 * Module integrations with the farm_log module.
 */

/**
 * @defgroup farm_log_hooks Farm log's hooks
 * @{
 * Hooks that can be implemented by other modules in order to extend farm_log.
 */

/**
 * Specify what asset types can be referenced by field_farm_asset on a given
 * log type.
 *
 * @param string $log_type
 *   The log type that contains the asset reference field (field_farm_asset).
 *
 * @return string
 *   Returns the asset type machine name that can be referenced.
 */
function hook_farm_log_allowed_asset_reference_type($log_type) {

  // On seeding logs, only plantings can be referenced.
  if ($log_type == 'farm_seeding') {
    return 'planting';
  }
}

/**
 * @}
 */
