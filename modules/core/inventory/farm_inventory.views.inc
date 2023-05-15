<?php

/**
 * @file
 * Provides Views data for farm_inventory.module.
 */

/**
 * Implements hook_views_data_alter().
 */
function farm_inventory_views_data_alter(array &$data) {

  // Add computed inventory field to assets.
  if (isset($data['asset'])) {
    $data['asset']['inventory'] = [
      'title' => t('Current inventory'),
      'field' => [
        'id' => 'asset_inventory',
        'field_name' => 'inventory',
      ],
    ];
  }
}
