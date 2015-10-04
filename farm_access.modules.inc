<?php
/**
 * @file
 * Farm Access module permissions.
 */

/**
 * Implements hook_farm_access_perms().
 */
function farm_access_farm_access_perms($role) {
  $perms = array();

  // Allow Farm Managers to administer users and delegate roles.
  if ($role == 'Farm Manager') {

    // Administer users.
    $perms[] = 'administer users';

    // Allow role delegation (add one for each defined role).
    if (module_exists('role_delegation')) {
      $roles = module_invoke_all('farm_access_roles');
      foreach ($roles as $role) {
        $perms[] = 'assign ' . $role . ' role';
      }
    }
  }

  return $perms;
}

/**
 * Implements hook_farm_access_perms().
 */
function farm_asset_farm_access_perms($role) {
  $perms = array();

  // Grant access to RESTful Web Services resources.
  $perms[] = 'access resource farm_asset';

  return $perms;
}

/**
 * Implements hook_farm_access_perms().
 */
function log_farm_access_perms($role) {
  $perms = array();

  // Access farm dashboard.
  $perms[] = 'view log plan';

  // Access RESTful Web Services log resources.
  $perms[] = 'access resource log';

  return $perms;
}

/**
 * Implements hook_farm_access_perms().
 */
function node_farm_access_perms($role) {
  $perms = array();

  // This is needed to view nodes and taxonomy terms.
  $perms[] = 'access content';

  return $perms;
}

/**
 * Implements hook_farm_access_perms().
 */
function system_farm_access_perms($role) {
  $perms = array();

  // Grant access to admin pages.
  $perms[] = 'access administration pages';

  return $perms;
}

/**
 * Implements hook_farm_access_perms().
 */
function taxonomy_farm_access_perms($role) {
  $perms = array();

  // This is needed to add terms.
  if (in_array($role, array('Farm Manager', 'Farm Worker'))) {
    $perms[] = 'administer taxonomy';
  }

  // Access RESTful Web Services taxonomy_term resources.
  $perms[] = 'access resource taxonomy_term';

  return $perms;
}
