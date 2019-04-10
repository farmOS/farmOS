<?php

/**
 * @file
 * Hooks provided by farm_fields_dynamic.
 *
 * This file contains no working PHP code; it exists to provide additional
 * documentation for doxygen as well as to document hooks in the standard
 * Drupal manner.
 */

/**
 * @defgroup farm_fields_dynamic Farm dynamic field module integrations.
 *
 * Module integrations with the farm_fields_dynamic module.
 */

/**
 * @defgroup farm_fields_dynamic_hooks Farm dynamic field's hooks
 * @{
 * Hooks that can be implemented by other modules in order to extend farm_fields_dynamic.
 */

/**
 * Define dynamic field bases.
 *
 * @return array
 *   An array of field base definitions, keyed by field name.
 */
function hook_farm_fields_dynamic_bases() {
  return array(
    'my_field' => array(
      // ...
    ),
  );
}

/**
 * Define dynamic field instances.
 *
 * @return array
 *   An array of field instance definitions.
 */
function hook_farm_fields_dynamic_instances() {
  return array(
    array(
      // ...
    ),
  );
}

/**
 * @}
 */
