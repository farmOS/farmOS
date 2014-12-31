<?php
/**
 * @file
 * farm_manager.features.user_role.inc
 */

/**
 * Implements hook_user_default_roles().
 */
function farm_manager_user_default_roles() {
  $roles = array();

  // Exported role: farm_manager.
  $roles['farm_manager'] = array(
    'name' => 'Farm Manager',
    'weight' => '5',
    'machine_name' => 'farm_manager',
  );

  return $roles;
}
