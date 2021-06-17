<?php

namespace Drupal\Tests\asset\Functional;

use Drupal\Tests\farm_test\Functional\FarmBrowserTestBase;

/**
 * Tests the asset CRUD.
 */
abstract class AssetTestBase extends FarmBrowserTestBase {

  /**
   * Modules to install.
   *
   * @var array
   */
  protected static $modules = [
    'asset',
    'asset_test',
    'entity',
    'user',
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
  protected function setUp(): void {
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
      'administer assets',
      'view any asset',
      'create default asset',
      'view any default asset',
      'update own default asset',
      'update any default asset',
      'delete own default asset',
      'delete any default asset',
    ];
  }

  /**
   * Creates a asset entity.
   *
   * @param array $values
   *   Array of values to feed the entity.
   *
   * @return \Drupal\asset\Entity\AssetInterface
   *   The asset entity.
   */
  protected function createAssetEntity(array $values = []) {
    $storage = \Drupal::service('entity_type.manager')->getStorage('asset');
    $entity = $storage->create($values + [
      'name' => $this->randomMachineName(),
      'created' => \Drupal::time()->getRequestTime(),
      'type' => 'default',
    ]);
    return $entity;
  }

}
