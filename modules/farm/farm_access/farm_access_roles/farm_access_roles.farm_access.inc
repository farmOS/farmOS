<?php
/**
 * @file
 * Farm access hooks implemented by farm access roles module.
 */

/**
 * Implements hook_farm_access_roles().
 */
function farm_access_roles_farm_access_roles() {

  // Build a list of roles.
  $roles = array(
    'farm_manager' => array(
      'name' => 'Farm Manager',
      'access' => array(
        'view' => 'all',
        'edit' => 'all',
        'config' => TRUE,
      ),
    ),
    'farm_worker' => array(
      'name' => 'Farm Worker',
      'access' => array(
        'view' => 'all',
        'edit' => 'all',
      ),
    ),
    'farm_viewer' => array(
      'name' => 'Farm Viewer',
      'access' => array(
        'view' => 'all',
      ),
    ),
  );
  return $roles;
}
