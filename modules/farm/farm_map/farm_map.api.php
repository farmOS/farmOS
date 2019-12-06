<?php

/**
 * @file
 * Hooks provided by farm_map.
 *
 * This file contains no working PHP code; it exists to provide additional
 * documentation for doxygen as well as to document hooks in the standard
 * Drupal manner.
 */

/**
 * @defgroup farm_map Farm map module integrations.
 *
 * Module integrations with the farm_map module.
 */

/**
 * @defgroup farm_map_hooks Farm map's hooks
 * @{
 * Hooks that can be implemented by other modules in order to extend farm_map.
 */

/**
 * Define farmOS-map behaviors provided by this module. Modules can add a
 * behavior to a map with farm_map_add_behavior('mybehavior'). This will add the
 * JavaScript file to the page and invoke hook_farm_map_behavior_settings() to
 * add necessary Drupal JS settings to the page.
 */
function hook_farm_map_behaviors() {
  return array(
    'my_behavior' => array(
      'js' => 'js/my_behavior.js',
    ),
  );
}

/**
 * Return an array of settings for a given behavior. These will be added to the
 * page as Drupal JS settings in:
 * Drupal.settings.farm_map.behaviors.[behaviorname]
 */
function hook_farm_map_behavior_settings($behavior) {
  $settings = array();
  if ($behavior == 'my_behavior') {
    $settings['foo'] = 'bar';
  }
  return $settings;
}

/**
 * Perform logic when a map is viewed.
 *
 * @param $name
 *   The map name.
 * @param $element
 *   The map element.
 */
function hook_farm_map_view($name, $element) {

  // Add my farmOS map behavior.
  if ($name == 'my_map') {
    farm_map_add_behavior('my_behavior');
  }
}

/**
 * Extract geometries from an entity.
 *
 * @param $entity_type
 *   The entity type machine name.
 * @param $entity
 *   The entity object.
 *
 * @return array
 *   Return an array of geometry strings in WKT format. An associative array
 *   is allowed, and the keys can be used to differentiate multiple geometries
 *   from the same entity.
 */
function hook_farm_map_entity_geometries($entity_type, $entity) {
  $geometries = array();

  // Find geometry in the standard geofield.
  if (empty($entity->field_farm_geofield[LANGUAGE_NONE][0]['geom'])) {
    $geometries[] = $entity->field_farm_geofield[LANGUAGE_NONE][0]['geom'];
  }

  return $geometries;
}

/**
 * @}
 */
