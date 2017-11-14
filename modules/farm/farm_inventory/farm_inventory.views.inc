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

/**
 * Implements hook_views_data_alter().
 */
function farm_inventory_views_data_alter(&$data) {

  // Asset inventory.
  $data['farm_asset']['inventory'] = array(
    'title' => t('Asset inventory value'),
    'help' => t('The current asset inventory value.'),
    'field' => array(
      'handler' => 'farm_inventory_handler_field_asset_inventory_value',
      'click sortable' => TRUE,
    ),
  );

  // Add custom asset type filter that only includes types with inventory
  // enabled.
  $data['farm_asset']['inventory_asset_type'] = array(
    'title' => t('Asset type (inventory)'),
    'help' => t('Asset type (provided by farm_inventory module).'),
    'filter' => array(
      'title' => t('Asset type (inventory)'),
      'help' => t('The farm asset type, including only asset types with inventory enabled.'),
      'handler' => 'farm_inventory_handler_filter_asset_type',
      'base' => 'farm_asset',
      'base field' => 'id',
      'label' => t('Asset type (inventory)'),
    ),
  );
}
