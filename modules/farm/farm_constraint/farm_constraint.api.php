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
 * @return array|bool
 *   This can either return an array of information about the constraint (see
 *   example below), or it can return a simple boolean TRUE/FALSE.
 */
function hook_farm_constraint($type, $bundle, $id) {

  // Check to see if any other records reference this entity.
  // ...

  // Return information about the constraint. This should include a 'constraint'
  // key that is a unique constraint type. It can can include a simple
  // description message, information about entities that reference the entity,
  // and any other information you want to include. Each constraint should be an
  // array of information, so that multiple constraints can be included.
  return array(
    array(
      'constraint' => 'foo_constraint',
      'description' => t('You cannot delete this because I said so!'),
    ),
    array(
      'constraint' => 'bar_constraint',
      'entity_type' => 'log',
      'entity_id' => 1234,
    ),
  );
}

/**
 * @}
 */
