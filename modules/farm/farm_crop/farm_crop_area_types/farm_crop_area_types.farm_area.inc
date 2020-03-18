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
      'style' => 'green',
      'weight' => -5,
      'dashboard_metric' => TRUE,
    ),
    'greenhouse' => array(
      'label' => t('Greenhouse'),
      'style' => 'green',
      'weight' => 0,
    ),
  );
}
