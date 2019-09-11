<?php

/**
 * @file
 * Farm Area hooks implemented by the Farm Livestock Area Types module.
 */

/**
 * Implements hook_farm_area_type_info().
 */
function farm_livestock_area_types_farm_area_type_info() {
  return array(
    'paddock' => array(
      'label' => t('Paddock'),
      'style' => 'darkgreen',
      'weight' => 5,
      'dashboard_metric' => TRUE,
    ),
  );
}
