<?php

/**
 * @file
 * Provides Views data for farm_location.module.
 */

/**
 * Implements hook_views_data_alter().
 */
function farm_location_views_data_alter(array &$data) {

  // Add computed fields to assets.
  if (isset($data['asset'])) {

    // Computed geometry.
    $data['asset']['geometry'] = [
      'title' => t('Geometry'),
      'field' => [
        'id' => 'asset_geometry',
        'field_name' => 'geometry',
      ],
    ];

    // Computed location.
    $data['asset']['location'] = [
      'title' => t('Current location'),
      'field' => [
        'id' => 'asset_location',
        'field_name' => 'location',
      ],
      'argument' => [
        'id' => 'asset_location',
      ],
    ];
  }
}
