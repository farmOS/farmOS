<?php

namespace Drupal\Tests\farm_entity\Kernel;

use Drupal\KernelTests\KernelTestBase;

/**
 * Tests farmOS entity fields.
 *
 * @group farm
 */
class FarmEntityFieldTest extends KernelTestBase {

  /**
   * The entity field manager.
   *
   * @var \Drupal\Core\Entity\EntityFieldManagerInterface
   */
  protected $entityFieldManager;

  /**
   * {@inheritdoc}
   */
  protected $profile = 'farm';

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'entity',
    'asset',
    'log',
    'plan',
    'farm_field',
    'farm_entity',
    'farm_entity_test',
    'farm_location',
    'farm_log',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp():void {
    parent::setUp();
    $this->entityFieldManager = $this->container->get('entity_field.manager');
  }

  /**
   * Test farmOS fields defined in hook_entity_base_field_info().
   */
  public function testHookEntityBaseFieldInfo() {

    // Test asset field storage definitions.
    $fields = $this->entityFieldManager->getFieldStorageDefinitions('asset');
    $this->assertArrayHasKey('data', $fields);
    $this->assertArrayHasKey('flag', $fields);
    $this->assertArrayHasKey('file', $fields);
    $this->assertArrayHasKey('id_tag', $fields);
    $this->assertArrayHasKey('image', $fields);
    $this->assertArrayHasKey('intrinsic_geometry', $fields);
    $this->assertArrayHasKey('is_fixed', $fields);
    $this->assertArrayHasKey('is_location', $fields);
    $this->assertArrayHasKey('notes', $fields);
    $this->assertArrayHasKey('parent', $fields);

    // Test log field storage definitions.
    $fields = $this->entityFieldManager->getFieldStorageDefinitions('log');
    $this->assertArrayHasKey('asset', $fields);
    $this->assertArrayHasKey('category', $fields);
    $this->assertArrayHasKey('data', $fields);
    $this->assertArrayHasKey('flag', $fields);
    $this->assertArrayHasKey('file', $fields);
    $this->assertArrayHasKey('geometry', $fields);
    $this->assertArrayHasKey('image', $fields);
    $this->assertArrayHasKey('is_movement', $fields);
    $this->assertArrayHasKey('location', $fields);
    $this->assertArrayHasKey('notes', $fields);
    $this->assertArrayHasKey('owner', $fields);

    // Test plan field storage definitions.
    $fields = $this->entityFieldManager->getFieldStorageDefinitions('plan');
    $this->assertArrayHasKey('data', $fields);
    $this->assertArrayHasKey('flag', $fields);
    $this->assertArrayHasKey('file', $fields);
    $this->assertArrayHasKey('image', $fields);
    $this->assertArrayHasKey('notes', $fields);
  }

  /**
   * Test farmOS fields defined in hook_farm_entity_bundle_field_info().
   */
  public function testHookFarmEntityBundleFieldInfo() {

    // Get the log field storage definitions.
    $log_storage_definitions = $this->entityFieldManager->getFieldStorageDefinitions('log');

    // Test that 'test_hook_bundle_field' has a storage definition.
    $this->assertArrayHasKey('test_hook_bundle_field', $log_storage_definitions);

    // Test fields definitions for the 'test' log type.
    $fields = $this->entityFieldManager->getFieldDefinitions('log', 'test');
    $this->assertArrayHasKey('test_hook_bundle_field', $fields);

    // Test fields definitions for the 'test_override' log type.
    $fields = $this->entityFieldManager->getFieldDefinitions('log', 'test_override');
    $this->assertArrayHasKey('test_hook_bundle_field', $fields);

    // Get all log bundles.
    /** @var \Drupal\Core\Entity\EntityTypeBundleInfoInterface $entity_type_bundle_info */
    $entity_type_bundle_info = $this->container->get('entity_type.bundle.info');
    $bundles = $entity_type_bundle_info->getBundleInfo('log');

    // Test that all log types have a bundle specific field.
    foreach (array_keys($bundles) as $bundle) {
      $fields = $this->entityFieldManager->getFieldDefinitions('log', $bundle);
      $field_name = 'test_hook_bundle_' . $bundle . '_specific_field';

      // Assert field storage definition exists.
      $this->assertArrayHasKey($field_name, $log_storage_definitions);

      // Assert field definition for the bundle.
      $this->assertArrayHasKey($field_name, $fields);
    }
  }

  /**
   * Test farmOS fields defined in buildFieldDefinitions().
   */
  public function testBuildFieldDefinitions() {

    // Test plan field definitions.
    $fields = $this->entityFieldManager->getFieldDefinitions('plan', 'test');
    $this->assertArrayHasKey('asset', $fields);
    $this->assertArrayHasKey('log', $fields);
  }

  /**
   * Test that farmOS base fields can be overridden.
   */
  public function testFarmFieldsOverride() {

    // Load field definitions for test_override logs.
    $fields = $this->entityFieldManager->getFieldDefinitions('log', 'test_override');

    // Test that a module extending FarmLogType can remove default bundle fields
    // that were provided in parent plugin classes.
    $this->assertArrayNotHasKey('test_default_bundle_field', $fields);

    // But also confirm that a module extending a base log type can NOT remove
    // bundle fields that were provided by hook_farm_entity_bundle_field_info().
    $this->assertArrayHasKey('test_hook_bundle_field', $fields);
  }

}
