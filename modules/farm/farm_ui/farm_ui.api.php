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
        // This will add an action link on area pages, and will show a link in
        // the area details popup.
        'areas' => TRUE,

        // Define the weight of this log type relative to others (optional)
        // This will be used to sort the log Views displayed on entities.
        // Best practice for this is to use increments of 10 between -90 and 90,
        // roughly in the order that logs will typically take place with an
        // entity. -100 and 100 should be reserved for special cases where a
        // View absolutely needs to be at the very top or the very bottom of
        // the list.
        /**
         * @see hook_farm_ui_asset_views() and hook_farm_ui_taxonomy_views()
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
      ),
    ),
  );
  return $entities;
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
function hook_farm_ui_asset_views(FarmAsset $farm_asset) {

  // If the entity is not a planting, bail.
  if ($farm_asset->type != 'planting') {
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
      'weight' => 10,
    ),
  );
}

/**
 * Attach Views to taxonomy term pages.
 *
 * @param object $term
 *   The taxonomy term entity.
 *
 * @return array
 *   Returns an array of View to attach to taxonomy term pages.
 *   Each element in the array can either be the name of a View,
 *   or an array of options, including:
 *     'name' - the machine name of the View
 *     'display' - which display of the View should be used
 *     'arg' - which argument the term id should be passed to in the View
 *       (this is useful if the View has more than one contextual filter)
 *     'weight' - the weight of the View in the taxonomy page
 *       (this is useful for changing the order of Views)
 *     'always' - always display, even if there are no View results
 *       (default is FALSE)
 */
function hook_farm_ui_taxonomy_views($term) {

  // If the term is not a crop, bail.
  if ($term->vocabulary_machine_name != 'farm_crops') {
    return array();
  }

  // Return a list of Views to include on Crops.
  return array(

    // Example 1: simple View machine name.
    'farm_planting',

    // Example 2: explicitly set details like display, argument position, weight.
    array(
      'name' => 'farm_log_input',
      'display' => 'block',
      'arg' => 2,
      'weight' => 10,
      'always' => TRUE,
    ),
  );
}

/**
 * @}
 */
