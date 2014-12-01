<?php

/**
 * @file
 * Farm Area Views data.
 */

/**
 * Implements hook_views_data_alter().
 */
function farm_log_views_data_alter(&$data) {

  $data['farm_asset']['latest_movement'] = array(
    'title' => t('Latest movement'),
    'help' => t('The latest movement record for this farm asset.'),
    'relationship' => array(
      'title' => t('Latest movement'),
      'help' => t('Relate the farm asset to its latest movement log.'),
      'handler' => 'farm_log_handler_relationship_location',
      'field_name' => 'field_farm_asset',
      'base' => 'log',
      'base field' => 'id',
      'field table' => 'field_data_field_farm_asset',
      'field field' => 'field_farm_asset_target_id',
      'date table' => 'field_data_field_farm_date',
      'date field' => 'entity_id',
      'label' => t('latest movement'),
      'join_extra' => array(
        0 => array(
          'field' => 'entity_type',
          'value' => 'log',
        ),
        1 => array(
          'field' => 'bundle',
          'value' => 'farm_movement',
        ),
        2 => array(
          'field' => 'deleted',
          'value' => 0,
          'numeric' => TRUE,
        ),
      ),
    ),
  );
}
