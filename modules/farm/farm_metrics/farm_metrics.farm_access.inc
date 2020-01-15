<?php
/**
 * @file
 * Farm access hooks implemented by farm_metrics module.
 */

/**
 * Implements hook_farm_access_perms().
 */
function farm_metrics_farm_access_perms($role) {
  // Load the list of farm roles.
  $roles = farm_access_roles();

  // If this role has 'config' access, grant area generator access.
  if (!empty($roles[$role]['access']['config'])) {
    return array('access farm metrics');
  }
  else {
    return array();
  }
}
