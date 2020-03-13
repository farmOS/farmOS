<?php
/**
 * @file
 * Farm dashboard hooks implemented by farm log module.
 */

/**
 * Implements hook_farm_dashboard_panes().
 */
function farm_log_farm_dashboard_panes() {
  return array(
    'farm_log_upcoming' => array(
      'view' => 'farm_log',
      'view_display_id' => 'block_upcoming',
      'group' => 'logs',
      'weight' => 10,
    ),
    'farm_log_late' => array(
      'view' => 'farm_log',
      'view_display_id' => 'block_late',
      'group' => 'logs',
      'weight' => 11,
    ),
     'farm_log_flag' => array(
      'view' => 'farm_log',
      'view_display_id' => 'block_flags',
      'group' => 'logs',
      'weight' => 12,
    ),
  );
}

