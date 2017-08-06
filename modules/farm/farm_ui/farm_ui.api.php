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

    // Define farm_asset entity types provided by this module.
    'farm_asset' => array(

      // Plantings:
      'planting' => array(

        // Label
        'label' => t('Planting'),

        // Label (plural)
        'label_plural' => t('Plantings'),

        // View of plantings (optional).
        'view' => 'farm_plantings',
      ),
    ),

    // Define log entity types provided by this module.
    'log' => array(

      // Seedings:
      'farm_seeding' => array(

        // Label.
        'label' => t('Seeding'),

        // Label (plural).
        'label_plural' => t('Seedings'),

        // View of seedings (optional).
        'view' => 'farm_log_seeding',

        // The specific asset type that these logs apply to (optional).
        // This will add an action link to asset pages for adding a log.
        // It will also limit the asset type that can be referenced by the log.
        // Set this to 'none' if the log does not apply to any asset types.
        // Set it to 'all' if the log can apply to all asset types (this is the
        // default behavior).
        'farm_asset' => 'planting',

        // Set 'areas' to TRUE if the log type can be used on areas.
        // This will add an action link on area pages, add a View to area
        // pages, and will show a link in the area details popup.
        'areas' => TRUE,

        // Define the weight of this log type relative to others (optional).
        // This will be used to sort the log Views displayed on entities, as
        // well as action links displayed at the top of the page.
        // Best practice for this is to use increments of 10 between -90 and 90,
        // roughly in the order that logs will typically take place with an
        // entity. -100 and 100 should be reserved for special cases where it
        // absolutely needs to be the very first or the very last item.
        /**
         * @see hook_farm_ui_entity_views()
         */
        'weight' => 10,
      ),
    ),

    // Define taxonomy_term vocabularies provided by this module.
    'taxonomy_term' => array(

      // Crops:
      'farm_crops' => array(

        // Label.
        'label' => t('Crop'),

        // Label (plural).
        'label_plural' => t('Crops'),

        // View of crops (optional).
        'view' => 'farm_crops',

        // The specific asset type that these terms apply to (optional).
        'farm_asset' => 'planting',

        // The position of a contextual filter argument corresponding to this
        // taxonomy term in the View of assets that these terms apply to
        // (optional). This will enable the View of assets to be displayed
        // on the term pages, filtered to only show assets tagged with the
        // term being viewed. In most cases, this will be 2 or 3, because asset
        // Views should always have asset location as their first argument.
        'asset_view_arg' => 2,
      ),
    ),
  );
  return $entities;
}

/**
 * Provide a group that entity Views can be sorted into.
 *
 * @return array
 *   Returns an array of group information.
 *   Each element should have a unique key and an array of options, including:
 *     'title' - The title of the group. This is optional. If it not provided
 *       then the Views will not be wrapped in a fieldset.
 *     'weight' - The weight of the group relative to others.
 */
function hook_farm_ui_entity_view_groups() {
  $groups = array(
    'assets' => array(
      'weight' => 98,
    ),
    'logs' => array(
      'weight' => 99,
    ),
    'other' => array(
      'weight' => 100,
    ),
  );
  return $groups;
}

/**
 * Attach Views to an entity page.
 *
 * @param $entity_type
 *   The entity type. Currently supports: 'farm_asset' or 'taxonomy_term'.
 * @param $bundle
 *   The entity bundle.
 * @param $entity
 *   The loaded entity object.
 *
 * @return array
 *   Returns an array of View to attach to taxonomy term pages.
 *   Each element in the array can either be the name of a View,
 *   or an array of options, including:
 *     'name' - the machine name of the View
 *     'display' - which display of the View should be used
 *     'arg' - which argument the term id should be passed to in the View
 *       (this is useful if the View has more than one contextual filter)
 *     'group' - the group to put the View in (options are: assets, logs,
 *       other)
 *     'weight' - the weight of the View in the taxonomy page
 *       (this is useful for changing the order of Views)
 *     'always' - always display, even if there are no View results
 *       (default is FALSE)
 */
function hook_farm_ui_entity_views($entity_type, $bundle, $entity) {

  // If the entity is not a planting asset, bail.
  if (!($entity_type == 'farm_asset' && $bundle == 'planting')) {
    return array();
  }

  // Return a list of Views to include on Plantings.
  return array(

    // Example 1: simple View machine name.
    'farm_activity',

    // Example 2: explicitly set details like display, argument position,
    // and weight.
    array(
      'name' => 'farm_log_input',
      'display' => 'block',
      'arg' => 2,
      'group' => 'logs',
      'weight' => 10,
      'always' => TRUE,
    ),
  );
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
