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
    'farm_entity_fields',
    'farm_entity_test',
    'farm_flag',
    'farm_id_tag',
    'farm_location',
    'farm_log',
    'farm_log_asset',
    'farm_owner',
    'farm_parent',
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
    $field_names = [
      'data',
      'flag',
      'file',
      'id_tag',
      'image',
      'intrinsic_geometry',
      'is_fixed',
      'is_location',
      'notes',
      'owner',
      'parent',
    ];
    foreach ($field_names as $field_name) {
      $this->assertArrayHasKey($field_name, $fields, "The asset $field_name field exists.");
    }

    // Test parent field constraints.
    $parent_field_constraints = $fields['parent']->getConstraints();
    $this->assertArrayHasKey('CircularReference', $parent_field_constraints);
    $this->assertArrayHasKey('DuplicateReference', $parent_field_constraints);

    // Test log field storage definitions.
    $fields = $this->entityFieldManager->getFieldStorageDefinitions('log');
    $field_names = [
      'asset',
      'data',
      'flag',
      'file',
      'geometry',
      'image',
      'is_movement',
      'location',
      'notes',
      'owner',
      'test_hook_base_field',
    ];
    foreach ($field_names as $field_name) {
      $this->assertArrayHasKey($field_name, $fields, "The log $field_name field exists.");
    }

    // Test plan field storage definitions.
    $fields = $this->entityFieldManager->getFieldStorageDefinitions('plan');
    $field_names = [
      'data',
      'flag',
      'file',
      'image',
      'notes',
    ];
    foreach ($field_names as $field_name) {
      $this->assertArrayHasKey($field_name, $fields, "The plan $field_name field exists.");
    }
  }

  /**
   * Test farmOS fields defined in hook_farm_entity_bundle_field_info().
   */
  public function testHookFarmEntityBundleFieldInfo() {

    // Get the log field storage definitions.
    $log_storage_definitions = $this->entityFieldManager->getFieldStorageDefinitions('log');

    // Test that 'test_hook_bundle_field' has a storage definition with the
    // correct provider.
    $this->assertArrayHasKey('test_hook_bundle_field', $log_storage_definitions);
    $this->assertEquals('farm_entity_test', $log_storage_definitions['test_hook_bundle_field']->getProvider());

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

      // Assert field storage definition exists and has the correct provider.
      $this->assertArrayHasKey($field_name, $log_storage_definitions);
      $this->assertEquals('farm_entity_test', $log_storage_definitions[$field_name]->getProvider());

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
