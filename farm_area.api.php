<?php

/**
 * @file
 * Hooks provided by farm_area.
 *
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
 * @param int $id
 *   The area id that links are being generated for.
 *
 * @return array
 *   Returns an array of arrays to represent area links.
 */
function hook_farm_area_links($id) {
  $path = 'farm/area/' . $id;
  return array(
    array(
      'title' => t('Plantings'),
      'href' => $path . '/plantings',
      'weight' => 0,
    ),
    array(
      'title' => t('Animals'),
      'href' => $path . '/animals',
      'options' => array(
        'fragment' => 'Animals',
      ),
      'weight' => 20,
    ),
  );
}

/**
 * Provide details about farm areas.
 *
 * @param int $id
 *   The area id.
 *
 * @return array
 *   Returns a render array to add to the area's popup.
 */
function hook_farm_area_details($id) {

  // Start a render array.
  $output = array();

  // Add "Hello world!" to area details.
  $output[] = array(
    '#type' => 'markup',
    '#markup' => 'Hello world!',
  );

  // Return the render array.
  return $output;
}

/**
 * @}
 */
