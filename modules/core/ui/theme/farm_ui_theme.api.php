<?php

/**
 * @file
 * Hooks provided by farm_ui_theme.
 *
 * This file contains no working PHP code; it exists to provide additional
 * documentation for doxygen as well as to document hooks in the standard
 * Drupal manner.
 */

/**
 * @addtogroup hooks
 * @{
 */

/**
 * Add entity form field groups.
 *
 * @param string $entity_type
 *   The entity type.
 * @param string $bundle
 *   The bundle.
 */
function hook_farm_ui_theme_field_groups(string $entity_type, string $bundle) {
  if ($entity_type == 'asset' && $bundle == 'animal') {
    return [
      'custom' => [
        'location' => 'main',
        'title' => t('Custom'),
        'weight' => 50,
      ],
    ];
  }
  return [];
}

/**
 * Specify the field groups to place entity fields in.
 *
 * @param string $entity_type
 *   The entity type.
 * @param string $bundle
 *   The bundle.
 *
 * @return string[]
 *   An array keyed by field ID mapping to field group.
 */
function hook_farm_ui_theme_field_group_items(string $entity_type, string $bundle) {
  if ($entity_type == 'asset' && $bundle == 'animal') {
    return [
      'nickname' => 'bundle',
      'sex' => 'bundle',
    ];
  }
  return [];
}

/**
 * Specify the regions that asset, log, and plan content items should be in.
 *
 * @param string $entity_type
 *   The entity type ('asset', 'log', or 'plan').
 *
 * @return array
 *   An array of item name arrays, keyed by region name ('top', 'first',
 *   'second', 'bottom'). For example:
 *   [
 *     'top' => [],
 *     'first' => [],
 *     'second' => [
 *       'status',
 *       'type',
 *     ],
 *     'bottom' => [],
 *   ];
 */
function hook_farm_ui_theme_region_items(string $entity_type) {
  if ($entity_type == 'log') {
    return [
      'second' => [
        'is_movement',
      ],
    ];
  }
  return [];
}

/**
 * @} End of "addtogroup hooks".
 */
