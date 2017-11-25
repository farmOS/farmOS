<?php

/**
 * @file
 * Hooks provided by farm_constraint.
 *
 * This file contains no working PHP code; it exists to provide additional
 * documentation for doxygen as well as to document hooks in the standard
 * Drupal manner.
 */

/**
 * @defgroup farm_constraint Farm constraint module integrations.
 *
 * Module integrations with the farm_constraint module.
 */

/**
 * @defgroup farm_constraint_hooks Farm constraint's hooks
 * @{
 * Hooks that can be implemented by other modules in order to extend farm_constraint.
 */

/**
 * Defines farm constraint types.
 *
 * @param $type
 *   The entity type.
 * @param $bundle
 *   The entity bundle.
 * @param $id
 *   The entity id.
 *
 * @return bool
 *   Return TRUE if a constraint exists. FALSE otherwise.
 */
function hook_farm_constraint($type, $bundle, $id) {

  // Check to see if any other records reference this entity.
  // ...

  // Constraint exists!
  return TRUE;
}

/**
 * @}
 */
