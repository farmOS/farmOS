<?php

namespace Drupal\farm_access;

use Drupal\user\RoleInterface;

/**
 * Interface for the ManagedRolePermissionsManager.
 *
 * @ingroup farm
 */
interface ManagedRolePermissionsManagerInterface {

  /**
   * Checks if the role has a specified permission.
   *
   * @param string $permission
   *   The permission string to check.
   * @param \Drupal\user\RoleInterface $role
   *   The Role to check.
   *
   * @return bool
   *   If the role has the permission.
   */
  public function isPermissionInRole($permission, RoleInterface $role);

}
