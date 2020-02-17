<?php
/**
 * @file
 * Farm Map hooks implemented by the Farm Soil NRCS module.
 */

/**
 * Implements hook_farm_map_behaviors().
 */
function farm_soil_nrcs_farm_map_behaviors() {
  return array(
    'soil_survey' => array(
      'js' => 'farmOS.map.behaviors.soil_survey.js',
    ),
  );
}

/**
 * Implements hook_farm_map_view().
 */
function farm_soil_nrcs_farm_map_view($name, $element) {

  // Add a farmOS map behavior that provides the NRCS Soil Survey layer.
  farm_map_add_behavior('soil_survey');
}
