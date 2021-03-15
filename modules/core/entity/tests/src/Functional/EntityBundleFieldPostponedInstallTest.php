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
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

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
    'farm_entity_bundle_fields_test',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp():void {
    parent::setUp();
    $this->entityFieldManager = $this->container->get('entity_field.manager');
    $this->entityTypeManager = $this->container->get('entity_type.manager');
    $this->database = $this->container->get('database');
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

  /**
   * Test that bundle fields can be reused across bundles.
   */
  public function testBundlePluginModuleUninstallation() {

    // Test that database tables exist after uninstalling a bundle with
    // a field storage definition used by other bundles.
    $this->moduleInstaller->uninstall(['farm_entity_bundle_fields_test']);

    // Must clear the cache for the test environment.
    $this->entityFieldManager->clearCachedFieldDefinitions();

    /** @var \Drupal\Core\Entity\Sql\DefaultTableMapping $table_mapping */
    $table_mapping = $this->entityTypeManager->getStorage('plan')
      ->getTableMapping();

    // Test that correct field storage definitions and database tables exist.
    $test_fields = [
      'second_plan_field' => FALSE,
      'asset' => TRUE,
      'log' => TRUE,
    ];
    $field_storage_definitions = $this->entityFieldManager->getFieldStorageDefinitions('plan');
    foreach ($test_fields as $field_name => $exists) {

      // Test the field storage definition existence.
      $this->assertEquals($exists, array_key_exists($field_name, $field_storage_definitions));

      // Test that the database table exists if the field storage definition
      // exists.
      if ($exists) {
        $table = $table_mapping->getDedicatedDataTableName($field_storage_definitions[$field_name]);
        $this->assertTrue($this->database->schema()->tableExists($table));
      }
    }
  }

}
