<?php

/**
 * @file
 * Hooks provided by farm_manager.
 *
 * This file contains no working PHP code; it exists to provide additional
 * documentation for doxygen as well as to document hooks in the standard
 * Drupal manner.
 */

/**
 * @defgroup farm_manager Farm manager module integrations.
 *
 * Module integrations with the farm_manager module.
 */

/**
 * @defgroup farm_manager_hooks Farm manager's hooks
 * @{
 * Hooks that can be implemented by other modules in order to extend farm_manager.
 */

/**
 * Defines farm manager permissions.
 * Use farm_manager_entity_perms() to generate permissions for common entity
 * types.
 *
 * @return array
 *   Returns an array of farm manager permissions.
 *
 * @see farm_manager_entity_perms()
 */
function hook_farm_manager_perms() {

  // Build a list of permissions on behalf of the farm_crop module.
  $types = array(
    'farm_asset' => array(
      'planting',
    ),
    'log' => array(
      'farm_harvest',
      'farm_input',
      'farm_seeding',
      'farm_transplanting',
    ),
    'taxonomy' => array(
      'farm_crops',
      'farm_crop_families',
    ),
  );
  return farm_manager_entity_perms($types);
}

/**
 * @}
 */
