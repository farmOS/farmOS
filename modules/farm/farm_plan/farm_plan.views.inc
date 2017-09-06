<?php

/**
 * @file
 * Farm plan views hooks.
 */

/**
 * Implements hook_views_plugins().
 */
function farm_plan_views_plugins() {
  return array(
    'argument validator' => array(
      'farm_plan' => array(
        'title' => t('Farm plan'),
        'handler' => 'farm_plan_plugin_argument_validate_farm_plan',
        'path' => drupal_get_path('module', 'farm_plan') . '/views/plugins',
      ),
    ),
  );
}
