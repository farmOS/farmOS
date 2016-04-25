<?php

/**
 * @file
 * Farm Log: Movement Views data.
 */

/**
 * Implements hook_views_data_alter().
 */
function farm_log_movement_views_data_alter(&$data) {

  $data['farm_asset']['latest_movement'] = array(
    'title' => t('Latest movement'),
    'help' => t('The latest movement record for this farm asset.'),
    'relationship' => array(
      'title' => t('Latest movement'),
      'help' => t('Relate the farm asset to its latest movement log.'),
      'handler' => 'farm_log_movement_handler_relationship_location',
      'base' => 'log',
      'base field' => 'id',
      'label' => t('Latest movement'),
    ),
  );
}
