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
 * Perform logic when a map is viewed.
 *
 * @param $name
 *   The map name.
 * @param $element
 *   The map element.
 */
function hook_farm_map_view($name, $element) {

  // Add a farmOS map behavior JS file.
  drupal_add_js(drupal_get_path('module', 'mymodule'), 'mymodule.mybehavior.js');
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
