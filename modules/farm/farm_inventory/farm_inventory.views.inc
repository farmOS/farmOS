<?php

/**
 * @file
 * Farm inventory views integration.
 */

/**
 * Implements hook_views_data().
 */
function farm_inventory_views_data() {

  // Describe the {ffarm_inventory_asset_type} table to Views.
  $data['farm_inventory_asset_type']['table']['group'] = t('Inventory asset type');

  // Create an implicit relationship to the farm_asset_type table, so that it's
  // possible to link inventory configuration to assets.
  $data['farm_inventory_asset_type']['table']['join'] = array(
    'farm_asset' => array(
      'left_field' => 'type',
      'field' => 'type',
    ),
  );

  // Inventory enabled.
  $data['farm_inventory_asset_type']['enabled'] = array(
    'title' => t('Inventory enabled'),
    'help' => t('Whether or not inventory is enabled for the asset type.'),
    'field' => array(
      'handler' => 'views_handler_field_boolean',
      'click sortable' => TRUE,
    ),
    'filter' => array(
      'handler' => 'views_handler_filter_boolean_operator',
      'label' => t('Enabled'),
      'type' => 'yes-no',
      'use equal' => TRUE,
    ),
    'sort' => array(
      'handler' => 'views_handler_sort',
    ),
  );

  // Inventory enabled.
  $data['farm_inventory_asset_type']['individual'] = array(
    'title' => t('Asset is individual'),
    'help' => t('Whether or not to treat assets as individuals if inventory adjustments are not available.'),
    'field' => array(
      'handler' => 'views_handler_field_boolean',
      'click sortable' => TRUE,
    ),
    'filter' => array(
      'handler' => 'views_handler_filter_boolean_operator',
      'label' => t('Individual'),
      'type' => 'yes-no',
      'use equal' => TRUE,
    ),
    'sort' => array(
      'handler' => 'views_handler_sort',
    ),
  );

  return $data;
}
