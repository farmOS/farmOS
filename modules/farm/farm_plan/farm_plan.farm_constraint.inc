<?php

/**
 * @file
 * Farm constraint hook implementations.
 */

/**
 * Implements hook_farm_constraint().
 */
function farm_plan_farm_constraint($type, $bundle, $id) {

  // Define plan references to check for
  // (see farm_constraint_table_references_exist()).
  $plan_references = array(
    'area' => array(
      'type' => 'taxonomy_term',
      'tables' => array(
        'farm_plan_area' => 'area_id',
      ),
    ),
    'asset' => array(
      'type' => 'farm_asset',
      'tables' => array(
        'farm_plan_asset' => 'asset_id',
      ),
    ),
    'log' => array(
      'type' => 'log',
      'tables' => array(
        'farm_plan_log' => 'log_id',
      ),
    ),
    'user' => array(
      'type' => 'user',
      'tables' => array(
        'farm_plan_user' => 'user_id',
      ),
    ),
  );

  // Check to see if references exist.
  return farm_constraint_table_references_exist($plan_references, $type, $bundle, $id);
}
