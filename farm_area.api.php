<?php

/**
 * @file
 * This file contains no working PHP code; it exists to provide additional
 * documentation for doxygen as well as to document hooks in the standard
 * Drupal manner.
 */


/**
 * @defgroup farm_area Farm area module integrations.
 *
 * Module integrations with the farm_area module.
 */

/**
 * @defgroup farm_area_hooks Farm area's hooks
 * @{
 * Hooks that can be implemented by other modules in order to extend farm_area.
 */

/**
 * Provide links for farm areas.
 *
 * @param $id
 *   The area that links are being generated for.
 *
 * @return array
 *   Returns an array of links.
 */
function hook_farm_area_links($id) {
  $path = 'taxonomy/term/' . $id;
  return array(
    l('Plantings', $path . '/plantings'),
    l('Animals', $path . '/animals'),
  );
}

/**
 * @}
 */
