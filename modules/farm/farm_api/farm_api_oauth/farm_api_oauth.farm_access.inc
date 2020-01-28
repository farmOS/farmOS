<?php
/**
 * @file
 * Farm Access hooks implemented by farm_api_oauth module.
 */

/**
 * Implements hook_farm_access_perms().
 */
function farm_api_oauth_farm_access_perms($role) {
  // Load the list of farm roles.
  $roles = farm_access_roles();

  if (!empty($roles[$role]['access']['config'])) {
    return array('administer oauth clients');
  }
  else {
    return array();
  }
}
