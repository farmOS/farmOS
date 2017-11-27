<?php

/**
 * @file
 * Hooks provided by farm_asset_property.
 *
 * This file contains no working PHP code; it exists to provide additional
 * documentation for doxygen as well as to document hooks in the standard
 * Drupal manner.
 */

/**
 * @defgroup farm_asset_property Farm asset property module integrations.
 *
 * Module integrations with the farm_asset_property module.
 */

/**
 * @defgroup farm_asset_property_hooks Farm asset property's hooks
 * @{
 * Hooks that can be implemented by other modules in order to extend farm_asset_property.
 */

/**
 * Defines farm asset properties maintained by this module.
 *
 * @return array
 *   Returns an array of farm asset property names.
 */
function hook_farm_asset_property() {
  return array(
    'farm_grazing_animal_type',
    'farm_grazing_planned_arrival',
    'farm_grazing_planned_departure',
  );
}

/**
 * @}
 */
