<?php

/**
 * @file
 * Farm movement views data.
 */

/**
 * Implements hook_views_data_alter().
 */
function farm_movement_views_data_alter(&$data) {

  $data['farm_asset']['latest_movement'] = array(
    'title' => t('Latest movement'),
    'help' => t('The latest movement for this farm asset.'),
    'relationship' => array(
      'title' => t('Latest movement'),
      'help' => t('Relate the farm asset to its latest movement.'),
      'handler' => 'farm_movement_handler_relationship_location',
      'base' => 'field_collection_item',
      'base field' => 'item_id',
      'label' => t('Latest movement'),
    ),
  );
}
