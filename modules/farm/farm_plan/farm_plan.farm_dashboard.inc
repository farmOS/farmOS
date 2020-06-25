<?php
/**
 * @file
 * Farm dashboard hooks implemented by farm plan module.
 */

/**
 * Implements hook_farm_dashboard_panes().
 */
function farm_plan_farm_dashboard_panes() {
  return array(
    'farm_plan_active_plans' => array(
      'view' => 'farm_plan',
      'view_display_id' => 'block_active_plans',
      'group' => 'plans',
    ),
  );
}
