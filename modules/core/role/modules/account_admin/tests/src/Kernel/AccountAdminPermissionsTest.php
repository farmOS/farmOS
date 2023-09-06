<?php

namespace Drupal\Tests\farm_role_account_admin\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\Tests\user\Traits\UserCreationTrait;

/**
 * Tests for Account Admin role permissions.
 *
 * @group farm
 */
class AccountAdminPermissionsTest extends KernelTestBase {

  use UserCreationTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'farm_role',
    'farm_role_account_admin',
    'farm_role_roles',
    'farm_settings',
    'role_delegation',
    'system',
    'user',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp():void {
    parent::setUp();
    $this->installEntitySchema('user');
    $this->installSchema('system', ['sequences']);
    $this->installConfig(['farm_role_account_admin', 'farm_role_roles']);
  }

  /**
   * Test that the Account Admin role gets appropriate permissions.
   */
  public function testAccountAdminPermissions() {

    // Create a user.
    $user = $this->setUpCurrentUser([], [], FALSE);

    // List Account Admin permissions.
    $account_admin_permissions = [
      'administer farm settings',
      'administer users',
      'assign farm_account_admin role',
      'assign farm_manager role',
      'assign farm_worker role',
      'assign farm_viewer role',
    ];

    // Ensure the user does not have permissions.
    foreach ($account_admin_permissions as $permission) {
      $this->assertFalse($user->hasPermission($permission));
    }

    // Add Account Admin role.
    $user->addRole('farm_account_admin');

    // Ensure the user has permissions.
    foreach ($account_admin_permissions as $permission) {
      $this->assertTrue($user->hasPermission($permission));
    }
  }

}
