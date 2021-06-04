<?php

/**
 * @file
 * Provides Views data for farm_group.module.
 */

/**
 * Implements hook_views_data_alter().
 */
function farm_group_views_data_alter(array &$data) {

  // Add the computed group membership field to assets.
  $data['asset']['group'] = [
    'title' => t('Current group'),
    'field' => [
      'id' => 'asset_group',
      'field_name' => 'group',
    ],
    'argument' => [
      'id' => 'asset_group',
    ],
  ];
}
