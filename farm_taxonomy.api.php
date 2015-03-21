<?php

/**
 * @file
 * This file contains no working PHP code; it exists to provide additional
 * documentation for doxygen as well as to document hooks in the standard
 * Drupal manner.
 */


/**
 * @defgroup farm_taxonomy Farm taxonomy module integrations.
 *
 * Module integrations with the farm_taxonomy module.
 */

/**
 * @defgroup farm_taxonomy_hooks Farm taxonomy's hooks
 * @{
 * Hooks that can be implemented by other modules in order to extend farm_taxonomy.
 */

/**
 * Attach Views to taxonomy term pages.
 *
 * @return array
 *   Returns an array of View to attach to taxonomy term pages.
 *   Each element in the array can either be the name of a View,
 *   or an array of options, including:
 *     'name' - the machine name of the View
 *     'arg' - which argument the term id should be passed to in the View
 *       (this is useful if the View has more than one contextual filter)
 */
function hook_taxonomy_term_view_views($term) {

  // If the term is not a crop, bail.
  if ($term->vocabulary_machine_name != 'crop') {
    return;
  }

  // Return a list of Views to include on Plantings.
  return array(

    // Example 1: simple View machine name
    'farm_planting',

    // Example 2: also include the position of the term id argument
    array(
      'name' => 'farm_log_input',
      'arg' => 2,
    ),
  );
}

/**
 * @}
 */
