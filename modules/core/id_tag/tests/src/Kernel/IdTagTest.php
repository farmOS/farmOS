<?php

namespace Drupal\Tests\farm_id_tag\Kernel;

use Drupal\asset\Entity\Asset;
use Drupal\KernelTests\KernelTestBase;

/**
 * Test ID tag field.
 *
 * @group farm
 */
class IdTagTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'asset',
    'farm_field',
    'farm_id_tag',
    'farm_id_tag_test',
    'state_machine',
    'user',
  ];

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();
    $this->installEntitySchema('asset');
    $this->installConfig(['farm_id_tag', 'farm_id_tag_test']);
  }

  /**
   * Test ID tag fields.
   */
  public function testIdTagField() {

    // Test creating a new asset and saving ID tag information.
    $asset = Asset::create([
      'name' => $this->randomString(),
      'type' => 'test',
      'id_tag' => [
        'id' => '123456',
        'type' => 'other',
        'location' => 'Frame',
      ],
    ]);
    $violations = $asset->validate();
    $this->assertEmpty($violations);
    $asset->save();

    // Confirm that the asset was created with expected ID tag values.
    $assets = Asset::loadMultiple();
    $this->assertCount(1, $assets);
    $this->assertEquals('123456', $assets[1]->get('id_tag')->id);
    $this->assertEquals('other', $assets[1]->get('id_tag')->type);
    $this->assertEquals('Frame', $assets[1]->get('id_tag')->location);

    // Confirm that all sub-fields are optional.
    $asset = Asset::create([
      'name' => $this->randomString(),
      'type' => 'test',
      'id_tag' => [],
    ]);
    $violations = $asset->validate();
    $this->assertEmpty($violations);
    $asset = Asset::create([
      'name' => $this->randomString(),
      'type' => 'test',
      'id_tag' => [
        'id' => '',
        'type' => '',
        'location' => '',
      ],
    ]);
    $violations = $asset->validate();
    $this->assertEmpty($violations);

    // Confirm that an invalid tag type does not pass validation.
    $asset = Asset::create([
      'name' => $this->randomString(),
      'type' => 'test',
      'id_tag' => [
        'type' => 'invalid',
      ],
    ]);
    $violations = $asset->validate();
    $this->assertNotEmpty($violations);
    $this->assertEquals('Invalid ID tag type: invalid', $violations[0]->getMessage());
  }

}
