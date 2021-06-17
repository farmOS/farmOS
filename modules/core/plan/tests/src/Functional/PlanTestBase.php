<?php

namespace Drupal\Tests\plan\Functional;

use Drupal\Tests\farm_test\Functional\FarmBrowserTestBase;

/**
 * Tests the plan CRUD.
 */
abstract class PlanTestBase extends FarmBrowserTestBase {

  /**
   * Modules to install.
   *
   * @var array
   */
  protected static $modules = [
    'plan',
    'plan_test',
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
      'administer plans',
      'view any plan',
      'create default plan',
      'view any default plan',
      'update own default plan',
      'update any default plan',
      'delete own default plan',
      'delete any default plan',
    ];
  }

  /**
   * Creates a plan entity.
   *
   * @param array $values
   *   Array of values to feed the entity.
   *
   * @return \Drupal\plan\Entity\PlanInterface
   *   The plan entity.
   */
  protected function createPlanEntity(array $values = []) {
    $storage = \Drupal::service('entity_type.manager')->getStorage('plan');
    $entity = $storage->create($values + [
      'name' => $this->randomMachineName(),
      'created' => \Drupal::time()->getRequestTime(),
      'type' => 'default',
    ]);
    return $entity;
  }

}
