<?php

/**
 * @file
 * Hooks provided by farm_help.
 *
 * This file contains no working PHP code; it exists to provide additional
 * documentation for doxygen as well as to document hooks in the standard
 * Drupal manner.
 */

/**
 * @defgroup farm_help Farm help module integrations.
 *
 * Module integrations with the farm_help module.
 */

/**
 * @defgroup farm_help_hooks farm_help's hooks
 * @{
 * Hooks that can be implemented by other modules in order to extend farm_help.
 */

/**
 * Add output to the /farm/help page.
 *
 * @return array
 *   Returns an array of actions and their meta information (see example below).
 */
function hook_farm_help_page() {

  // Add a link to farmOS.rog
  $output = array(
    l('farmOS.org', 'https://farmos.org'),
  );
  return $output;
}

/**
 * @}
 */
