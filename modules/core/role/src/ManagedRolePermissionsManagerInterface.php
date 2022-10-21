<?php

namespace Drupal\farm_role;

use Drupal\user\RoleInterface;

/**
 * Interface for the ManagedRolePermissionsManager.
 *
 * @internal
 *
 * @ingroup farm
 */
interface ManagedRolePermissionsManagerInterface {

  /**
   * Returns an array of managed roles.
   *
   * @return \Drupal\user\RoleInterface[]
   *   An array of managed roles.
   */
  public function getMangedRoles(): array;

  /**
   * Checks if the role is a managed role.
   *
   * @param \Drupal\user\RoleInterface $role
   *   The Role to check.
   *
   * @return bool
   *   If the role is a managed role.
   */
  public function isManagedRole(RoleInterface $role);

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
