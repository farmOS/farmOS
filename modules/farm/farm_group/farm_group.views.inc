<?php

/**
 * @file
 * Farm group views data.
 */

/**
 * Implements hook_views_data_alter().
 */
function farm_group_views_data_alter(&$data) {

  $data['farm_asset']['membership'] = array(
    'title' => t('Group membership'),
    'help' => t('The group(s) that this asset is a member of.'),
    'relationship' => array(
      'title' => t('Group membership'),
      'help' => t('Relate the farm asset to its latest group membership log.'),
      'handler' => 'farm_group_handler_relationship_membership',
      'base' => 'field_collection_item',
      'base field' => 'item_id',
      'label' => t('Group membership'),
    ),
  );
}

/**
 * Implements hook_field_views_data_alter().
 */
function farm_group_field_views_data_alter(&$result, $field, $module) {

  // Replace the filter handler for field_farm_group fields so that we can
  // expose a select list of group options.
  $owner_field = 'field_farm_group';
  if ($field['field_name'] == $owner_field) {
    if (!empty($result['field_data_' . $owner_field][$owner_field . '_target_id']['filter'])) {
      $filter = &$result['field_data_' . $owner_field][$owner_field . '_target_id']['filter'];
      $filter['handler'] = 'farm_group_handler_filter_asset_group';
    }
  }
}
