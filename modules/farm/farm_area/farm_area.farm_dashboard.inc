<?php
/**
 * @file
 * Farm dashboard hooks implemented by farm calendar module.
 */

/**
 * Implements hook_farm_dashboard_panes().
 */
function farm_area_farm_dashboard_panes() {
  return array(
    'farm_area_map' => array(
      'callback' => 'farm_area_dashboard_map',
      'group' => 'map',
    ),
  );
}

/**
 * Date dashboard callback.
 */
function farm_area_dashboard_map() {
  return drupal_render(farm_map_build('farm_areas'));
}
