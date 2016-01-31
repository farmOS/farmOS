<?php

/**
 * @file
 * Hooks provided by farm_asset.
 *
 * This file contains no working PHP code; it exists to provide additional
 * documentation for doxygen as well as to document hooks in the standard
 * Drupal manner.
 */

/**
 * @defgroup farm_asset Farm asset module integrations.
 *
 * Module integrations with the farm_asset module.
 */

/**
 * @defgroup farm_asset_hooks Farm asset's hooks
 * @{
 * Hooks that can be implemented by other modules in order to extend farm_asset.
 */

/**
 * Add breadcrumbs to the asset view page.
 *
 * @param FarmAsset $farm_asset
 *   The farm asset entity.
 *
 * @return array
 *   Returns an array of links to add to the asset breadcrumb.
 */
function hook_farm_asset_breadcrumb(FarmAsset $farm_asset) {

  // If the asset is an animal, add a link to the animals list.
  $breadcrumb = array();
  if ($farm_asset->type == 'animal') {
    $breadcrumb[] = l(t('Assets'), 'farm/assets');
    $breadcrumb[] = l(t('Animals'), 'farm/assets/animals');
  }
  return $breadcrumb;
}

/**
 * Attach Views to asset view pages.
 *
 * @param FarmAsset $farm_asset
 *   The farm asset entity.
 *
 * @return array
 *   Returns an array of View names to attach to farm asset pages.
 */
function hook_farm_asset_view_views(FarmAsset $farm_asset) {

  // If the entity is not a planting, bail.
  if ($farm_asset->type != 'planting') {
    return array();
  }

  // Return a list of Views to include on Plantings.
  return array(
    'farm_log_seeding',
    'farm_log_transplanting',
    'farm_log_input',
    'farm_log_harvest',
    'farm_log_activity',
    'farm_log_observation',
    'farm_log_movement',
  );
}

/**
 * @}
 */
