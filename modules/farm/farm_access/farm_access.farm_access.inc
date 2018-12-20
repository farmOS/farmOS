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
    ),
    'farm_worker' => array(
      'name' => 'Farm Worker',
    ),
    'farm_viewer' => array(
      'name' => 'Farm Viewer',
    ),
  );
  return $roles;
}

/**
 * Implements hook_farm_access_perms().
 */
function farm_asset_farm_access_perms($role) {
  $perms = array();

  // Set up default CRUD permissions for all farm asset entity types.
  $asset_types = farm_asset_types();
  $access_types = array(
    'farm_asset' => array(),
  );
  foreach ($asset_types as $type => $data) {
    $access_types['farm_asset'][] = $type;
  }
  switch ($role) {

    // Grant full access to Farm Manager and Worker roles.
    case 'farm_manager':
    case 'farm_worker':
      $perms = farm_access_entity_perms($access_types);
      break;

    // Grant read-only access to Farm Viewer role.
    case 'farm_viewer':
      $perms = farm_access_entity_perms($access_types, array('view'));
      break;
  }

  // Grant access to view farm assets.
  $perms[] = 'view farm assets';

  return $perms;
}

/**
 * Implements hook_farm_access_perms().
 */
function farm_plan_farm_access_perms($role) {
  $perms = array();

  // Set up default CRUD permissions for all farm plan entity types.
  $plan_types = farm_plan_types();
  $access_types = array(
    'farm_plan' => array(),
  );
  foreach ($plan_types as $type => $data) {
    $access_types['farm_plan'][] = $type;
  }
  switch ($role) {

    // Grant full access to Farm Manager and Worker roles.
    case 'farm_manager':
    case 'farm_worker':
      $perms = farm_access_entity_perms($access_types);
      break;

    // Grant read-only access to Farm Viewer role.
    case 'farm_viewer':
      $perms = farm_access_entity_perms($access_types, array('view'));
      break;
  }

  // Grant access to view farm plans.
  $perms[] = 'view farm plans';

  return $perms;
}

/**
 * Implements hook_farm_access_perms().
 */
function log_farm_access_perms($role) {
  $perms = array();

  // Set up default CRUD permissions for all log entity types.
  $log_types = log_types();
  $access_types = array(
    'log' => array(),
  );
  foreach ($log_types as $type => $data) {
    $access_types['log'][] = $type;
  }
  switch ($role) {

    // Grant full access to Farm Manager and Worker roles.
    case 'farm_manager':
    case 'farm_worker':
      $perms = farm_access_entity_perms($access_types);
      break;

    // Grant read-only access to Farm Viewer role.
    case 'farm_viewer':
      $perms = farm_access_entity_perms($access_types, array('view'));
      break;
  }

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

  // Allow Farm Managers to delegate roles.
  if ($role == 'farm_manager') {

    // Allow role delegation (add one for each defined role).
    $roles = module_invoke_all('farm_access_roles');
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
  $perms = array();

  // Assemble a list of all taxonomies.
  $vocabularies = taxonomy_get_vocabularies();
  $types = array(
    'taxonomy' => array(),
  );
  foreach ($vocabularies as $vocab) {
    $types['taxonomy'][] = $vocab->machine_name;
  }

  // Grant different CRUD permissions based on the role.
  switch ($role) {

    // Farm Manager and Worker
    case 'farm_manager':
    case 'farm_worker':
      $perms = farm_access_entity_perms($types);
      break;

    // Farm Viewer
    case 'farm_viewer':
      $perms = farm_access_entity_perms($types, array('view'));
      break;
  }

  // This is needed to add terms.
  if (in_array($role, array('farm_manager', 'farm_worker'))) {
    $perms[] = 'administer taxonomy';
  }

  return $perms;
}

/**
 * Implements hook_farm_access_perms().
 */
function user_farm_access_perms($role) {
  $perms = array();

  // Allow Farm Managers and Farm Workers to view user profiles.
  if ($role == 'farm_manager' || $role == 'farm_worker') {
    $perms[] = 'access user profiles';
  }

  // Allow Farm Managers to administer users.
  if ($role == 'farm_manager') {

    // Administer users.
    $perms[] = 'administer users';
  }

  return $perms;
}
