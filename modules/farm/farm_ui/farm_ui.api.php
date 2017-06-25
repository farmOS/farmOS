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
 * Add breadcrumbs to the taxonomy view page.
 *
 * @param object $term
 *   The taxonomy term entity.
 *
 * @return array
 *   Returns an array of links to add to the term's breadcrumb.
 */
function hook_farm_ui_taxonomy_breadcrumb($term) {
  $breadcrumb = array();

  // If the term is in farm_areas, add a link to /farm/areas.
  if ($term->vocabulary_machine_name == 'farm_areas') {
    $breadcrumb[] = l(t('Areas'), 'farm/areas');
  }

  return $breadcrumb;
}

/**
 * @}
 */
