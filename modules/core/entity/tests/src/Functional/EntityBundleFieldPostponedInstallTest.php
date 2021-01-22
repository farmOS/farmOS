<?php

namespace Drupal\Tests\farm_entity\Functional;

use Drupal\Tests\farm\Functional\FarmBrowserTestBase;

/**
 * Tests that bundle fields are created during a postponed install.
 *
 * @group farm
 */
class EntityBundleFieldPostponedInstallTest extends FarmBrowserTestBase {

  /**
   * The entity field manager.
   *
   * @var \Drupal\Core\Entity\EntityFieldManagerInterface
   */
  protected $entityFieldManager;

  /**
   * The module installer service.
   *
   * @var \Drupal\Core\Extension\ModuleInstallerInterface
   */
  protected $moduleInstaller;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'farm_entity',
    'farm_entity_test',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp():void {
    parent::setUp();
    $this->entityFieldManager = $this->container->get('entity_field.manager');
    $this->moduleInstaller = $this->container->get('module_installer');
  }

  /**
   * Test installing the farm_entity_contrib_test module after farm_entity_test.
   */
  public function testBundleFieldPostponedInstall() {

    // Install the farm_entity_contrib_test module.
    $result = $this->moduleInstaller->install(['farm_entity_contrib_test'], TRUE);
    $this->assertTrue($result);

    // Must clear the cache for the test environment.
    $this->entityFieldManager->clearCachedFieldDefinitions();

    // Test log field storage definition.
    $fields = $this->entityFieldManager->getFieldStorageDefinitions('log');
    $this->assertArrayHasKey('test_contrib_hook_bundle_field', $fields);

    // Test bundle field storage definition.
    $fields = $this->entityFieldManager->getFieldDefinitions('log', 'test');
    $this->assertArrayHasKey('test_contrib_hook_bundle_field', $fields);
  }

}
