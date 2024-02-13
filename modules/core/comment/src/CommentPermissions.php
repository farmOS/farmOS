<?php

namespace Drupal\farm_comment;

use Drupal\user\RoleInterface;

/**
 * Add comment permissions to managed farmOS roles.
 */
class CommentPermissions {

  /**
   * Add permissions to role.
   *
   * @param \Drupal\user\RoleInterface $role
   *   The role to add permissions to.
   *
   * @return array
   *   An array of permission strings.
   */
  public function permissions(RoleInterface $role) {
    $perms = [];

    // Load farm_role access rules from third-party settings. Bail if empty.
    $access = $role->getThirdPartySetting('farm_role', 'access');
    if (empty($access)) {
      return $perms;
    }

    // If the role has "view all" access, allow viewing comments.
    if (!empty($access['entity']['view all'])) {
      $perms[] = 'access comments';
    }

    // If the role has "edit all" access, allow posting/editing comments.
    if (!empty($access['entity']['update all'])) {
      $perms[] = 'post comments';
      $perms[] = 'skip comment approval';
      $perms[] = 'edit own comments';
    }

    return $perms;
  }

}
