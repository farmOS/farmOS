<?php
/**
 * @file
 * Farm dashboard hooks implemented by farm calendar module.
 */

/**
 * Implements hook_farm_dashboard_panes().
 */
function farm_calendar_farm_dashboard_panes() {
  return array(
    'farm_calendar_date' => array(
      'callback' => 'farm_calendar_dashboard_date',
      'group' => 'logs',
      'weight' => -1,
    ),
  );
}

/**
 * Date dashboard callback.
 */
function farm_calendar_dashboard_date() {
  return '<h2>' . t('Today is') . ' ' . l(date('M j Y'), 'farm/calendar/day') . '</h2>';
}
