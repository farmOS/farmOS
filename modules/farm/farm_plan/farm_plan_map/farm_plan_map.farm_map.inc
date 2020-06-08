<?php
/**
 * @file
 * Farm Map hooks implemented by the Farm Plan Map module.
 */

/**
 * Implements hook_farm_map_behaviors().
 */
function farm_plan_map_farm_map_behaviors() {
  return array(
    'plan' => array(
      'js' => 'js/farmOS.map.behaviors.plan.js',
    ),
  );
}

/**
 * Implements hook_farm_map_view().
 */
function farm_plan_map_farm_map_view($name, $element) {

  // Add the plan behavior to farm plan maps.
  if ($name == 'farm_plan_map') {
    if (!empty($element['#plan_id'])) {
      farm_map_add_behavior('plan', array('plan_id' => $element['#plan_id'], 'zoom' => TRUE));
    }
  }
}
