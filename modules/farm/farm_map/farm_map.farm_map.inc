<?php
/**
 * @file
 * Farm Map hooks implemented by the Farm Map module.
 */

/**
 * Implements hook_farm_map_behaviors().
 */
function farm_map_farm_map_behaviors() {
  return array(
    'popup' => array(
      'js' => 'js/farmOS.map.behaviors.popup.js',
    ),
  );
}
