<?php

namespace Drupal\farm_role;

use Drupal\Component\Uuid\UuidInterface;
use Drupal\Core\Cache\MemoryCache\MemoryCacheInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\user\RoleStorage;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * FarmRoleStorage.
 *
 * Extend the RoleStorage class to include permissions defined with managed
 * farm roles.
 *
 * @ingroup farm
 */
class FarmRoleStorage extends RoleStorage {

  /**
   * The managed role permissions manager interface.
   *
   * @var \Drupal\farm_role\ManagedRolePermissionsManagerInterface
   */
  protected $managedRolePermissionsManager;

  /**
   * Constructs a ConfigEntityStorage object.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type definition.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory service.
   * @param \Drupal\Component\Uuid\UuidInterface $uuid_service
   *   The UUID service.
   * @param \Drupal\Core\Language\LanguageManagerInterface $language_manager
   *   The language manager.
   * @param \Drupal\Core\Cache\MemoryCache\MemoryCacheInterface $memory_cache
   *   The memory cache backend.
   * @param \Drupal\farm_role\ManagedRolePermissionsManagerInterface $managed_role_permissions_manager
   *   The managed role permissions manager.
   */
  public function __construct(EntityTypeInterface $entity_type, ConfigFactoryInterface $config_factory, UuidInterface $uuid_service, LanguageManagerInterface $language_manager, MemoryCacheInterface $memory_cache, ManagedRolePermissionsManagerInterface $managed_role_permissions_manager) {
    parent::__construct($entity_type, $config_factory, $uuid_service, $language_manager, $memory_cache);
    $this->managedRolePermissionsManager = $managed_role_permissions_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    return new static(
      $entity_type,
      $container->get('config.factory'),
      $container->get('uuid'),
      $container->get('language_manager'),
      $container->get('entity.memory_cache'),
      $container->get('plugin.manager.managed_role_permissions')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function isPermissionInRoles($permission, array $rids) {

    // Check if the permission is defined directly on the role.
    $has_permission = parent::isPermissionInRoles($permission, $rids);

    // Else check if the permission is included via farm_role rules.
    if (!$has_permission) {
      foreach ($this->loadMultiple($rids) as $role) {
        /** @var \Drupal\user\RoleInterface $role */
        $has_permission = $this->managedRolePermissionsManager->isPermissionInRole($permission, $role);
        if ($has_permission) {
          break;
        }
      }
    }

    return $has_permission;
  }

}
