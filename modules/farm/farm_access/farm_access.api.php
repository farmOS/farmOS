<?php

/**
 * @file
 * Hooks provided by farm_access.
 *
 * This file contains no working PHP code; it exists to provide additional
 * documentation for doxygen as well as to document hooks in the standard
 * Drupal manner.
 */

/**
 * @defgroup farm_access Farm access module integrations.
 *
 * Module integrations with the farm_access module.
 */

/**
 * @defgroup farm_access_hooks Farm access's hooks
 * @{
 * Hooks that can be implemented by other modules in order to extend farm_access.
 */

/**
 * Defines farm access roles.
 *
 * @return array
 *   Returns an array of farm access roles.
 */
function hook_farm_access_roles() {

  // Build a list of roles.
  $roles = array(
    'Farm Manager',
    'Farm Worker',
    'Farm Viewer',
  );
  return $roles;
}

/**
 * Defines farm access permissions.
 * Use farm_access_entity_perms() to generate permissions for common entity
 * types.
 *
 * @param string $role
 *   The role name to add permissions for.
 *
 * @return array
 *   Returns an array of farm access permissions.
 *
 * @see farm_access_entity_perms()
 */
function hook_farm_access_perms($role) {

  // Assemble a list of entity types provided by this module.
  $types = array(
    'farm_asset' => array(
      'planting',
    ),
    'log' => array(
      'farm_harvest',
      'farm_input',
      'farm_seeding',
      'farm_transplanting',
    ),
    'taxonomy' => array(
      'farm_crops',
      'farm_crop_families',
    ),
  );

  // Grant different CRUD permissions based on the role.
  $perms = array();
  switch ($role) {

    // Farm Manager and Worker
    case 'Farm Manager':
    case 'Farm Worker':
      $perms = farm_access_entity_perms($types);
      break;

    // Farm Viewer
    case 'Farm Viewer':
      $perms = farm_access_entity_perms($types, array('view'));
      break;
  }

  return $perms;
}

/**
 * Alter permissions that were defined by modules using hook_farm_access_perms().
 *
 * @param string $role
 *   The role name that permissions are being built for.
 * @param array $perms
 *   The permissions provided by other modules, passed by reference.
 */
function hook_farm_access_perms_alter($role, &$perms) {

  // Give Farm Managers permission to administer modules.
  if ($role == 'Farm Manager') {
    $perms[] = 'administer modules';
  }
}

/**
 * @}
 */
