<?php

/**
 * @file
 * Hooks provided by farm_client.
 *
 * This file contains no working PHP code; it exists to provide additional
 * documentation for doxygen as well as to document hooks in the standard
 * Drupal manner.
 */

/**
 * @defgroup farm_client Farm client module integrations.
 *
 * Module integrations with the farm_client module.
 */

/**
 * @defgroup farm_client_hooks Farm client's hooks
 * @{
 * Hooks that can be implemented by other modules in order to extend farm_client.
 */

/**
 * Provide information about client modules that this module provides.
 *
 * @return array
 *   Returns an array of client module information.
 */
function hook_farm_client_module_info() {
  return array(
    'weather' => array(
      'name' => 'weather',
      'label' => t('Weather'),
      'js' => drupal_get_path('module', 'farm_weather') . '/src/FieldModule/Weather/weather.js',
    ),
  );
}

/**
 * @}
 */
