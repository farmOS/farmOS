<?php

/**
 * @file
 * Hooks provided by farm_ui.
 *
 * This file contains no working PHP code; it exists to provide additional
 * documentation for doxygen as well as to document hooks in the standard
 * Drupal manner.
 */

/**
 * @defgroup farm_ui Farm UI module integrations.
 *
 * Module integrations with the farm_ui module.
 */

/**
 * @defgroup farm_ui_hooks Farm UI's hooks
 * @{
 * Hooks that can be implemented by other modules in order to extend
 * farm_ui.
 */

/**
 * Define farmOS-specific information about entities that the module provides.
 * This is used to generate UI elements in farmOS.
 *
 * @return array
 *   Returns an array of entities and metadata about them (see example below).
 */
function hook_farm_ui_entities() {
  $entities = array(
    'farm_asset' => array(
      'planting' => array(
        'label' => t('Planting'),
        'label_plural' => t('Plantings'),
        'view' => 'farm_plantings',
      ),
    ),
  );
  return $entities;
}

/**
 * Add breadcrumbs to the asset view page.
 *
 * @param FarmAsset $farm_asset
 *   The farm asset entity.
 *
 * @return array
 *   Returns an array of links to add to the asset breadcrumb.
 */
function hook_farm_ui_asset_breadcrumb(FarmAsset $farm_asset) {

  // If the asset is an animal, add a link to the animals list.
  $breadcrumb = array();
  if ($farm_asset->type == 'animal') {
    $breadcrumb[] = l(t('Assets'), 'farm/assets');
    $breadcrumb[] = l(t('Animals'), 'farm/assets/animals');
  }
  return $breadcrumb;
}

/**
 * Provide action links on specific paths, asset types, and views.
 *
 * @return array
 *   Returns an array of actions and their meta information (see example below).
 */
function hook_farm_ui_actions() {

  // Define farm area actions.
  $actions = array(
    'foo' => array(
      'title' => t('Add a foo log'),
      'href' => 'log/add/farm_foo',
      'paths' => array(
        'farm/asset/%/foo',
      ),
      'assets' => array(
        'bar',
      ),
      'views' => array(
        'foo_view',
      ),
    ),
  );
  return $actions;
}

/**
 * @}
 */
