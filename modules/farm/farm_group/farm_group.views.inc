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
      'base' => 'log',
      'base field' => 'id',
      'label' => t('Group membership'),
    ),
  );
}
