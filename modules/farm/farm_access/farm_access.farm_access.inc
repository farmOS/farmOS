<?php
/**
 * @file
 * Farm access hooks implemented by farm access module.
 */

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

  // Load the list of farm roles.
  $roles = farm_access_roles();

  // If this role has 'config' access, allow them to administer users.
  if (!empty($roles[$role]['access']['config'])) {
    $perms[] = 'administer users';
  }

  // Allow all roles to view user profiles.
  $perms[] = 'access user profiles';

  return $perms;
}
