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
  ];

  /**
   * Tests the farmOS fields are added to entities.
   */
  public function testFarmFields() {

    // Load the entity field manager.
    /** @var \Drupal\Core\Entity\EntityFieldManagerInterface $entity_field_manager */
    $entity_field_manager = $this->container->get('entity_field.manager');

    // Load asset field storage definitions.
    $fields = $entity_field_manager->getFieldStorageDefinitions('asset');

    // Confirm that all fields defined in farm_entity_asset_base_fields() exist.
    $this->assertArrayHasKey('data', $fields);
    $this->assertArrayHasKey('flag', $fields);
    $this->assertArrayHasKey('file', $fields);
    $this->assertArrayHasKey('id_tag', $fields);
    $this->assertArrayHasKey('image', $fields);
    $this->assertArrayHasKey('notes', $fields);
    $this->assertArrayHasKey('parent', $fields);

    // Load log field storage definitions.
    $fields = $entity_field_manager->getFieldStorageDefinitions('log');

    // Confirm that all fields defined in farm_entity_log_base_fields() exist.
    $this->assertArrayHasKey('asset', $fields);
    $this->assertArrayHasKey('category', $fields);
    $this->assertArrayHasKey('data', $fields);
    $this->assertArrayHasKey('flag', $fields);
    $this->assertArrayHasKey('file', $fields);
    $this->assertArrayHasKey('image', $fields);
    $this->assertArrayHasKey('notes', $fields);
    $this->assertArrayHasKey('owner', $fields);

    // Load field definitions for test logs.
    $fields = $entity_field_manager->getFieldDefinitions('log', 'test');

    // Confirm that all fields defined in FarmLogType::buildFieldDefinitions()
    // exist.
    $this->assertArrayHasKey('geometry', $fields);

    // Confirm that fields defined in hook_farm_entity_bundle_field_info()
    // exist.
    $this->assertArrayHasKey('test_hook_base_field', $fields);
    $this->assertArrayHasKey('test_hook_bundle_field', $fields);

    // Load plan field storage definitions.
    $fields = $entity_field_manager->getFieldStorageDefinitions('plan');

    // Confirm that all fields defined in farm_entity_plan_base_fields() exist.
    $this->assertArrayHasKey('data', $fields);
    $this->assertArrayHasKey('flag', $fields);
    $this->assertArrayHasKey('file', $fields);
    $this->assertArrayHasKey('image', $fields);
    $this->assertArrayHasKey('notes', $fields);

    // Load field definitions for test plans.
    $fields = $entity_field_manager->getFieldDefinitions('plan', 'test');

    // Confirm that all fields defined in FarmPlanType::buildFieldDefinitions()
    // exist.
    $this->assertArrayHasKey('asset', $fields);
    $this->assertArrayHasKey('log', $fields);
  }

  /**
   * Tests the farmOS base fields can be overridden.
   */
  public function testFarmFieldsOverride() {

    // Load the entity field manager.
    /** @var \Drupal\Core\Entity\EntityFieldManagerInterface $entity_field_manager */
    $entity_field_manager = $this->container->get('entity_field.manager');

    // Load field definitions for test_override logs.
    $fields = $entity_field_manager->getFieldDefinitions('log', 'test_override');

    // Test that a module extending FarmLogType can remove default bundle fields
    // that were provided in parent plugin classes.
    $this->assertArrayNotHasKey('geometry', $fields);

    // But also confirm that a module extending a base log type can NOT remove
    // bundle fields that were provided by hook_farm_entity_bundle_field_info().
    $this->assertArrayHasKey('test_hook_bundle_field', $fields);
  }

}
