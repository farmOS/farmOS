<?php

/**
 * @file
 * Provides Views data for farm_entity_views.module.
 */

/**
 * Implements hook_views_data_alter().
 */
function farm_entity_views_views_data_alter(array &$data) {

  // Because Drupal core does not provide full Views integration for base fields
  // we must manually add support for certain fields.
  // Workaround for core issue #2489476.

  // Add support for state_machine filters.
  $status_filter = [
    'id' => 'state_machine_state',
    'field_name' => 'status',
  ];
  $tables = [
    'asset_field_data',
    'asset_field_revision',
    'log_field_data',
    'log_field_revision',
    'plan_field_data',
    'plan_field_revision',
  ];
  foreach ($tables as $table) {
    if (!empty($data[$table]['status'])) {
      $data[$table]['status']['filter'] = $status_filter;
    }
  }
}
