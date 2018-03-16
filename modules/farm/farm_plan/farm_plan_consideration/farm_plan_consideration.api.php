<?php

/**
 * @file
 * Hooks provided by farm_plan_consideration.
 *
 * This file contains no working PHP code; it exists to provide additional
 * documentation for doxygen as well as to document hooks in the standard
 * Drupal manner.
 */

/**
 * @defgroup farm_plan_consideration Farm Plan Consideration module integrations.
 *
 * Module integrations with the farm_plan_consideration module.
 */

/**
 * @defgroup farm_plan_consideration_hooks Farm Plan Consideration's hooks
 * @{
 * Hooks that can be implemented by other modules in order to extend
 * farm_plan_consideration.
 */

/**
 * Define plan consideration types.
 *
 * @return array
 *   Returns an array of information about consideration types provided by this
 *   modules.
 */
function hook_farm_plan_consideration_types() {
  return array(
    'mytype' => array(
      'label' => t('My consideration type'),
      'color' => 'green',
    ),
  );
}

/**
 * @}
 */
