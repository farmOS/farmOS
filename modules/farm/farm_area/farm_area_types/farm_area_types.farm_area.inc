<?php

/**
 * @file
 * Farm Area hooks implemented by the Farm Area Types module.
 */

/**
 * Implements hook_farm_area_type_info().
 */
function farm_area_types_farm_area_type_info() {
  return array(
    'building' => array(
      'label' => t('Building'),
      'style' => 'farm_map_style_red',
      'weight' => 0,
    ),
    'field' => array(
      'label' => t('Field'),
      'style' => 'farm_map_style_yellow',
      'weight' => 10,
      'dashboard_metric' => TRUE,
    ),
    'landmark' => array(
      'label' => t('Landmark'),
      'style' => 'farm_map_style_orange',
      'weight' => 50,
    ),
    'property' => array(
      'label' => t('Property'),
      'style' => 'farm_map_style_purple',
      'weight' => 100,
    ),
  );
}
