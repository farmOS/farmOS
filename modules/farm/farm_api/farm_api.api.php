<?php

/**
 * @file
 * Hooks provided by farm_api.
 *
 * This file contains no working PHP code; it exists to provide additional
 * documentation for doxygen as well as to document hooks in the standard
 * Drupal manner.
 */

/**
 * @defgroup farm_api Farm API module integrations.
 *
 * Module integrations with the farm_api module.
 */

/**
 * @defgroup farm_api_hooks Farm API's hooks
 * @{
 * Hooks that can be implemented by other modules in order to extend farm_api.
 */

/**
 * Provide general information about this farmOS system.
 *
 * @return array
 *   Returns an array of farm information.
 */
function hook_farm_info() {
  global $base_url, $conf;
  $info = array(
    'name' => $conf['site_name'],
    'url' => $base_url,
  );
  return $info;
}

/**
 * @}
 */
