<?php

/**
 * @file
 * Provides Views data for farm_ui_views.module.
 */

/**
 * Implements hook_views_data_alter().
 */
function farm_ui_views_views_data_alter(array &$data) {

  // Provide an asset_or_location argument for views of logs.
  if (isset($data['log_field_data'])) {
    $data['log_field_data']['asset_or_location'] = [
      'title' => t('Asset or location'),
      'help' => t('Assets that are referenced by the asset or location field on the log.'),
      'argument' => [
        'id' => 'asset_or_location',
      ],
    ];
  }
}
