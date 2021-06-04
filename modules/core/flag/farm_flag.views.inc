<?php

/**
 * @file
 * Provides Views data for farm_flag.module.
 */

/**
 * Implements hook_views_data_alter().
 */
function farm_flag_views_data_alter(array &$data) {

  // Because Drupal core does not provide full Views integration for base fields
  // we must manually specify the list_field views filter for the flag field.
  // Define the views filter settings.
  $flag_filter = [
    'id' => 'list_field',
    'field_name' => 'flag',
    'allow_empty' => TRUE,
  ];
  $tables = [
    'asset__flag',
    'asset_revision__flag',
    'log__flag',
    'log_revision__flag',
    'plan__flag',
    'plan_revision__flag',
  ];
  foreach ($tables as $table) {
    if (!empty($data[$table]['flag_value'])) {
      $data[$table]['flag_value']['filter'] = $flag_filter;
    }
  }
}
