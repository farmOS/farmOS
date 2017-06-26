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
        // This will add an action link on area pages.
        'areas' => TRUE,
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
