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
  $perms = array();

  // Load the list of farm roles.
  $roles = farm_access_roles();

  // Load asset types.
  $asset_types = farm_asset_types();

  // Grant access to view and edit asset types.
  $asset_access_ops = array(
    'view' => array('view'),
    'edit' => array('create', 'edit', 'delete'),
  );
  foreach ($asset_access_ops as $access => $ops) {

    // If the role has access to these asset operations...
    if (!empty($roles[$role]['access'][$access])) {

      // Build a list of asset types that they have access to. If 'all' access
      // is granted, add all permissions. Or, if specific asset types are
      // provided, add them individually.
      $access_types['farm_asset'] = array();
      if ($roles[$role]['access'][$access] == 'all' || !empty($roles[$role]['access'][$access]['farm_asset']) && $roles[$role]['access'][$access]['farm_asset'] == 'all') {
        foreach ($asset_types as $type => $data) {
          $access_types['farm_asset'][] = $type;
        }
      }
      elseif (!empty($roles[$role]['access'][$access]['farm_asset'])) {
        foreach ($roles[$role]['access'][$access]['farm_asset'] as $asset_type) {
          if (!empty($asset_types[$asset_type])) {
            $access_types['farm_asset'][] = $asset_type;
          }
        }
      }

      // Build a list of entity permissions for the assets and operations and
      // merge them into the permissions this function will return.
      $asset_perms = farm_access_entity_perms($access_types, $ops);
      $perms = array_merge($perms, $asset_perms);
    }
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
