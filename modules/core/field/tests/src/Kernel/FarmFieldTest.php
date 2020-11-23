<?php

namespace Drupal\Tests\entity\Kernel;

use Drupal\KernelTests\KernelTestBase;

/**
 * Tests the bundle plugin API.
 *
 * @group farm
 */
class FarmFieldTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected $profile = 'farm';

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'asset',
    'log',
    'farm_field',
    'farm_field_test',
  ];

  /**
   * Tests the farmOS base fields are added to entities.
   */
  public function testPluginBundles() {

    // Load the entity field manager.
    /** @var \Drupal\Core\Entity\EntityFieldManagerInterface $entity_field_manager */
    $entity_field_manager = $this->container->get('entity_field.manager');

    // Load asset field storage definitions.
    $fields = $entity_field_manager->getFieldStorageDefinitions('asset');

    // Confirm that all fields defined in farm_field_asset_base_fields() exist.
    $this->assertArrayHasKey('data', $fields);
    $this->assertArrayHasKey('file', $fields);
    $this->assertArrayHasKey('image', $fields);
    $this->assertArrayHasKey('notes', $fields);
    $this->assertArrayHasKey('parent', $fields);

    // Load log field storage definitions.
    $fields = $entity_field_manager->getFieldStorageDefinitions('log');

    // Confirm that all fields defined in farm_field_log_base_fields() exist.
    $this->assertArrayHasKey('asset', $fields);
    $this->assertArrayHasKey('category', $fields);
    $this->assertArrayHasKey('data', $fields);
    $this->assertArrayHasKey('file', $fields);
    $this->assertArrayHasKey('image', $fields);
    $this->assertArrayHasKey('notes', $fields);
    $this->assertArrayHasKey('owner', $fields);
  }

}
