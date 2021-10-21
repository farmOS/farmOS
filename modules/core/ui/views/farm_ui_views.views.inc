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

  // Provide an asset_taxonomy_term_reference argument for views of assets.
  if (isset($data['asset_field_data'])) {
    $data['asset_field_data']['asset_taxonomy_term_reference'] = [
      'title' => t('Asset Taxonomy Term Reference'),
      'help' => t('Taxonomy Terms that are referenced by the asset.'),
      'argument' => [
        'id' => 'entity_taxonomy_term_reference',
      ],
    ];
  }

  // Provide a log_taxonomy_term_reference argument for views of logs.
  if (isset($data['log_field_data'])) {
    $data['log_field_data']['log_taxonomy_term_reference'] = [
      'title' => t('Log Taxonomy Term Reference'),
      'help' => t('Taxonomy Terms that are referenced by the log.'),
      'argument' => [
        'id' => 'entity_taxonomy_term_reference',
      ],
    ];
  }
}
