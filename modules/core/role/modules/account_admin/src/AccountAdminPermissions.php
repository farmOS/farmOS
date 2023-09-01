<?php

namespace Drupal\farm_role_account_admin;

use Drupal\Core\Config\ConfigFactoryInterface;
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
   * The config factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs an AccountAdminPermissions object.
   *
   * @param \Drupal\farm_role\ManagedRolePermissionsManagerInterface $managed_role_permissions_manager
   *   The managed role permissions manager.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory service.
   */
  public function __construct(ManagedRolePermissionsManagerInterface $managed_role_permissions_manager, ConfigFactoryInterface $config_factory) {
    $this->managedRolePermissionsManager = $managed_role_permissions_manager;
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.managed_role_permissions'),
      $container->get('config.factory'),
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

      // Load the module settings.
      $settings = $this->configFactory->get('farm_role_account_admin.settings');

      // Grant the ability to assign managed farmOS roles.
      $roles = $this->managedRolePermissionsManager->getMangedRoles();
      foreach ($roles as $role) {

        // Do not allow assigning the "Account Admin" role if
        // allow_peer_role_assignment is disabled.
        if ($role->id() == 'farm_account_admin' && !$settings->get('allow_peer_role_assignment', FALSE)) {
          continue;
        }

        // Add permission to assign the role.
        $perms[] = 'assign ' . $role->id() . ' role';
      }
    }

    return $perms;
  }

}
