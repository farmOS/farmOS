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
 * Provide a list of log categories that should be created when the module
 * is installed. Note that these will be passed through the t() function when
 * they are created so that they can be translated. This does mean that they
 * will only be translated once, to whatever the site's default language is.
 *
 * @return array
 *   Returns an array of log categories (as simple strings).
 */
function hook_farm_log_categories() {
  return array(
    'My module category',
    'My other category',
  );
}

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
