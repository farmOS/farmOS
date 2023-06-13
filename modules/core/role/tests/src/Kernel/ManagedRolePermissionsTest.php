<?php

namespace Drupal\Tests\farm_role\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\Tests\user\Traits\UserCreationTrait;
use Drupal\user\Entity\Role;

/**
 * Tests for Managed Role permissions.
 *
 * @group farm
 */
class ManagedRolePermissionsTest extends KernelTestBase {

  use UserCreationTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'system',
    'user',
    'log',
    'state_machine',
    'farm_role',
    'farm_role_test',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp():void {
    parent::setUp();

    $this->installEntitySchema('user');
    $this->installEntitySchema('log');
    $this->installSchema('system', ['sequences']);
    $this->installConfig(['farm_role', 'farm_role_test', 'log']);
  }

  /**
   * Test that managed roles get default permissions.
   */
  public function testManagedRoleDefaultAccess() {

    // Create a user.
    $user = $this->setUpCurrentUser([], [], FALSE);

    // Ensure the user does not have default permissions.
    $this->assertFalse($user->hasPermission('test default permission'));

    // Add farm_test role.
    $user->addRole('farm_test');

    // Ensure the user has default permissions.
    $this->assertTrue($user->hasPermission('test default permission'));
  }

  /**
   * Test that managed roles with config access get config permissions.
   */
  public function testManagedRoleConfigAccess() {

    /** @var \Drupal\user\RoleInterface $role */
    $role = Role::load('farm_test_manager');

    // Test that the role's config setting is TRUE.
    $this->assertNotEmpty($role->getThirdPartySetting('farm_role', 'access', FALSE));
    $access_settings = $role->getThirdPartySetting('farm_role', 'access');
    $this->assertTrue(!empty($access_settings['config']));

    // Create a user.
    $user = $this->setUpCurrentUser([], [], FALSE);

    // Ensure the user does not have config access permissions.
    $this->assertFalse($user->hasPermission('test config access permission'));

    // Ensure the farm_test does not provide config access permissions.
    $user->addRole('farm_test');
    $this->assertFalse($user->hasPermission('test config access permission'));

    // Ensure the farm_test_manager role provides config access permissions.
    $user->addRole('farm_test_manager');
    $this->assertTrue($user->hasPermission('test config access permission'));
  }

  /**
   * Test that managed roles get permissions provided by callbacks.
   */
  public function testManagedRolePermissionCallbacks() {

    // Create a user.
    $user = $this->setUpCurrentUser([], [], FALSE);

    // Ensure the user does not include permission callback.
    $this->assertFalse($user->hasPermission('default callback permission'));

    // Ensure the farm_test includes valid callbacks permissions.
    $user->addRole('farm_test');
    $this->assertTrue($user->hasPermission('default callback permission'));
    $this->assertFalse($user->hasPermission('my manager permission'));
    $this->assertFalse($user->hasPermission('recover all permission'));

    // Ensure the farm_test_manager role includes valid callback perms.
    $user->addRole('farm_test_manager');
    $this->assertTrue($user->hasPermission('default callback permission'));
    $this->assertTrue($user->hasPermission('my manager permission'));
    $this->assertTrue($user->hasPermission('recover all permission'));
  }

  /**
   * Test that managed roles get high level operation permissions.
   */
  public function testManagedRoleHighLevelOperations() {

    /** @var \Drupal\user\RoleInterface $role */
    $role = Role::load('farm_test_manager');

    // Get the roles entity access settings.
    $this->assertNotEmpty($role->getThirdPartySetting('farm_role', 'access', FALSE));
    $access_settings = $role->getThirdPartySetting('farm_role', 'access');
    $entity_settings = $access_settings['entity'];

    // List of high level operations.
    $operations = [
      'view all',
      'create all',
      'update all',
      'delete all',
    ];

    // Ensure that the role has access to each high level operation.
    foreach ($operations as $operation) {
      $this->assertTrue(!empty($entity_settings[$operation]));
    }

    // Log bundles.
    $log_bundles = ['observation', 'harvest'];

    // Log entity operation prefixes.
    $operation_prefixes = [
      'view own',
      'view any',
      'create',
      'update own',
      'update any',
      'delete own',
      'delete any',
    ];

    // Create a user.
    $user = $this->setUpCurrentUser([], [], FALSE);

    // Ensure the user does not have permissions to logs.
    foreach ($operation_prefixes as $prefix) {
      foreach ($log_bundles as $bundle) {
        $this->assertFalse($user->hasPermission($prefix . ' ' . $bundle . ' log'));
      }
    }

    // Ensure farm_test_manager provides permissions for "default" log type.
    $user->addRole('farm_test_manager');
    foreach ($operation_prefixes as $prefix) {
      foreach ($log_bundles as $bundle) {
        $this->assertTrue($user->hasPermission($prefix . ' ' . $bundle . ' log'));
      }
    }
  }

  /**
   * Test that managed roles get granular entity permissions.
   */
  public function testManagedRoleGranularPermissions() {

    /** @var \Drupal\user\RoleInterface $role */
    $role = Role::load('farm_test');

    // Get the roles entity type access settings.
    $this->assertNotEmpty($role->getThirdPartySetting('farm_role', 'access', FALSE));
    $access_settings = $role->getThirdPartySetting('farm_role', 'access');
    $entity_settings = $access_settings['entity'];
    $log_settings = $entity_settings['type']['log'];

    // Ensure the farm_test role's granular access is configured correctly.
    // View all log types.
    $this->assertTrue(in_array('all', $log_settings['view any']));

    // Create all log types.
    $this->assertTrue(in_array('observation', $log_settings['create']));

    // Update any observation log.
    $this->assertTrue(in_array('observation', $log_settings['update any']));

    // Update own log types.
    $this->assertTrue(in_array('all', $log_settings['update own']));

    // Delete own log.
    $this->assertTrue(in_array('all', $log_settings['delete own']));

    // Create a user.
    $user = $this->setUpCurrentUser([], [], FALSE);
    $user->addRole('farm_test');

    // Log bundles.
    $log_bundles = ['observation', 'harvest'];

    // Test that the user only has permissions to specific log bundles
    // as defined by the farm_test role.
    foreach ($log_settings as $operation => $granted_bundles) {
      foreach ($log_bundles as $bundle) {
        $should_have_permission = in_array($bundle, $granted_bundles);
        if (in_array('all', $granted_bundles)) {
          $should_have_permission = TRUE;
        }
        $has_permission = $user->hasPermission($operation . ' ' . $bundle . ' log');
        $this->assertEquals($should_have_permission, $has_permission);
      }
    }
  }

}
