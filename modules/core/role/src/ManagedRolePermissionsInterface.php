<?php

namespace Drupal\farm_role;

/**
 * Provides an interface for defining ManagedRolePermissions plugins.
 *
 * @internal
 *
 * @ingroup farm
 */
interface ManagedRolePermissionsInterface {

  /**
   * Returns the default permissions.
   *
   * @return array
   *   Array of permission strings.
   */
  public function getDefaultPermissions();

  /**
   * Returns the config permissions.
   *
   * @return array
   *   Array of permission strings.
   */
  public function getConfigPermissions();

  /**
   * Returns permission callback strings.
   *
   * @return array
   *   Array of function callbacks in controller syntax, see
   *   \Drupal\Core\Controller\ControllerResolver
   */
  public function getPermissionCallbacks();

}
