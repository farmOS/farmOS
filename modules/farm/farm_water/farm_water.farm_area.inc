<?php

/**
 * @file
 * Farm Area hooks implemented by the Farm Water module.
 */

/**
 * Implements hook_farm_area_type_info().
 */
function farm_water_farm_area_type_info() {
  return array(
    'water' => array(
      'label' => t('Water'),
      'style' => 'blue',
      'weight' => 50,
    ),
  );
}
