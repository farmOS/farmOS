<?php

namespace Drupal\Tests\farm_asset\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Tests the farm_asset CRUD.
 */
abstract class FarmAssetTestBase extends BrowserTestBase {

  /**
   * Modules to install.
   *
   * @var array
   */
  public static $modules = [
    'entity',
    'user',
    'farm_asset',
    'farm_asset_test',
    'field',
    'text',
  ];

  /**
   * A test user with administrative privileges.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $adminUser;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->adminUser = $this->drupalCreateUser($this->getAdministratorPermissions());
    $this->drupalLogin($this->adminUser);
    drupal_flush_all_caches();
  }

  /**
   * Gets the permissions for the admin user.
   *
   * @return string[]
   *   The permissions.
   */
  protected function getAdministratorPermissions() {
    return [
      'access administration pages',
      'administer farm_asset',
      'view any farm_asset',
      'create default farm_asset',
      'view any default farm_asset',
      'update own default farm_asset',
      'update any default farm_asset',
      'delete own default farm_asset',
      'delete any default farm_asset',
    ];
  }

  /**
   * Creates a farm_asset entity.
   *
   * @param array $values
   *   Array of values to feed the entity.
   *
   * @return \Drupal\farm_asset\Entity\FarmAssetInterface
   *   The farm_asset entity.
   */
  protected function createFarmAssetEntity(array $values = []) {
    $storage = \Drupal::service('entity_type.manager')->getStorage('farm_asset');
    $entity = $storage->create($values + [
      'name' => $this->randomMachineName(),
      'created' => \Drupal::time()->getRequestTime(),
      'type' => 'default',
    ]);
    return $entity;
  }

}
