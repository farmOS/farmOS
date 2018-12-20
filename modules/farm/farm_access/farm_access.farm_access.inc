<?php
/**
 * @file
 * Farm access hooks implemented by farm access module.
 */

/**
 * Implements hook_farm_access_roles().
 */
function farm_access_farm_access_roles() {

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

/**
 * Implements hook_farm_access_perms().
 *
 * Set up default CRUD permissions for all farm asset entity types.
 */
function farm_asset_farm_access_perms($role) {

  // Use the helper function to generate a list of entity type bundles
  // permissions for the given role.
  $perms = farm_access_entity_bundles_role_perms('farm_asset', $role);

  // Grant access to view farm assets.
  $perms[] = 'view farm assets';

  return $perms;
}

/**
 * Implements hook_farm_access_perms().
 */
function farm_plan_farm_access_perms($role) {

  // Use the helper function to generate a list of entity type bundles
  // permissions for the given role.
  $perms = farm_access_entity_bundles_role_perms('farm_plan', $role);

  // Grant access to view farm plans.
  $perms[] = 'view farm plans';

  return $perms;
}

/**
 * Implements hook_farm_access_perms().
 */
function log_farm_access_perms($role) {

  // Use the helper function to generate a list of entity type bundles
  // permissions for the given role.
  $perms = farm_access_entity_bundles_role_perms('log', $role);

  // View all logs.
  $perms[] = 'view all logs';

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
function role_delegation_farm_access_perms($role) {
  $perms = array();

  // Load the list of farm roles.
  $roles = farm_access_roles();

  // If this role has 'config' access, allow them to delegate roles.
  if (!empty($roles[$role]['access']['config'])) {
    foreach ($roles as $role) {
      if (empty($role['name'])) {
        continue;
      }
      $perms[] = 'assign ' . $role['name'] . ' role';
    }
  }

  return $perms;
}

/**
 * Implements hook_farm_access_perms().
 */
function taxonomy_farm_access_perms($role) {

  // Use the helper function to generate a list of entity type bundles
  // permissions for the given role.
  $perms = farm_access_entity_bundles_role_perms('taxonomy_term', $role);

  // Load the list of farm roles.
  $roles = farm_access_roles();

  // If the role has high-level 'edit' access of taxonomy terms, give them the
  // 'administer taxonomy' permission so that they can add terms.
  if (!empty($roles[$role]['access']['edit'])) {
    $perms[] = 'administer taxonomy';
  }

  return $perms;
}

/**
 * Implements hook_farm_access_perms().
 */
function user_farm_access_perms($role) {
  $perms = array();

  // Allow Farm Managers to administer users.
  if ($role == 'farm_manager') {

    // Administer users.
    $perms[] = 'administer users';
  }

  // Allow all roles to view user profiles.
  $perms[] = 'access user profiles';

  return $perms;
}
