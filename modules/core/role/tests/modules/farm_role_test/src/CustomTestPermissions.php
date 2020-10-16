<?php

namespace Drupal\farm_role_test;

use Drupal\user\RoleInterface;

/**
 * A permission callback used for testing.
 */
class CustomTestPermissions {

  /**
   * Grant permissions to the specified role.
   *
   * @param \Drupal\user\RoleInterface $role
   *   The role to grant permissions.
   *
   * @return array
   *   Array of permission strings.
   */
  public function permissions(RoleInterface $role) {

    // Array of permissions to return.
    $perms = [];

    // Default callback permission.
    $perms[] = 'default callback permission';

    // Add permissions based on role name.
    if ($role->id() == 'farm_test_manager') {
      $perms[] = 'my manager permission';
    }

    // Get the farm_role third party settings from the Role entity.
    $access_settings = $role->getThirdPartySetting('farm_role', 'access');
    $entity_settings = $access_settings['entity'] ?: [];

    // Only add permissions if `update all` and `delete all` are true.
    if (!empty($entity_settings['update all'] && $entity_settings['delete all'])) {
      $perms[] = 'recover all permission';
    }

    // Return array of permissions.
    return $perms;
  }

}
