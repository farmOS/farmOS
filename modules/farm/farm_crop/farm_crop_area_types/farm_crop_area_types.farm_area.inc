<?php

/**
 * @file
 * Farm Area hooks implemented by the Farm Crop Area Types module.
 */

/**
 * Implements hook_farm_area_type_info().
 */
function farm_crop_area_types_farm_area_type_info() {
  return array(
    'bed' => array(
      'label' => t('Bed'),
      'style' => 'farm_map_style_green',
      'zoom_levels' => array(18, 19, 20, 21, 22, 23, 24),
      'weight' => -5,
      'dashboard_metric' => TRUE,
    ),
    'greenhouse' => array(
      'label' => t('Greenhouse'),
      'style' => 'farm_map_style_green',
      'weight' => 0,
    ),
  );
}
