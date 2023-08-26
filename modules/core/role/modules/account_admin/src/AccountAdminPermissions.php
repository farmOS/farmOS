<?php

namespace Drupal\farm_role_account_admin;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\farm_role\ManagedRolePermissionsManagerInterface;
use Drupal\user\RoleInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Add permissions to the Account Admin role.
 */
class AccountAdminPermissions implements ContainerInjectionInterface {

  /**
   * The managed role permissions manager.
   *
   * @var \Drupal\farm_role\ManagedRolePermissionsManagerInterface
   */
  protected $managedRolePermissionsManager;

  /**
   * Constructs an AccountAdminPermissions object.
   *
   * @param \Drupal\farm_role\ManagedRolePermissionsManagerInterface $managed_role_permissions_manager
   *   The managed role permissions manager.
   */
  public function __construct(ManagedRolePermissionsManagerInterface $managed_role_permissions_manager) {
    $this->managedRolePermissionsManager = $managed_role_permissions_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.managed_role_permissions'),
    );
  }

  /**
   * Add permissions to default farmOS roles.
   *
   * @param \Drupal\user\RoleInterface $role
   *   The role to add permissions to.
   *
   * @return array
   *   An array of permission strings.
   */
  public function permissions(RoleInterface $role) {
    $perms = [];

    // Add permissions to the farm_account_admin role.
    if ($role->id() == 'farm_account_admin') {

      // Grant the ability to assign managed farmOS roles.
      $roles = $this->managedRolePermissionsManager->getMangedRoles();
      foreach ($roles as $role) {
        $perms[] = 'assign ' . $role->id() . ' role';
      }
    }

    return $perms;
  }

}
