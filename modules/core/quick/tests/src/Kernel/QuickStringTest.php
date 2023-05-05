<?php

namespace Drupal\Tests\farm_quick\Kernel;

use Drupal\asset\Entity\Asset;
use Drupal\asset\Entity\AssetType;
use Drupal\farm_quick\Traits\QuickStringTrait;
use Drupal\KernelTests\KernelTestBase;

/**
 * Tests for quick string trait methods.
 *
 * @group farm
 */
class QuickStringTest extends KernelTestBase {

  use QuickStringTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'asset',
    'farm_quick',
    'state_machine',
    'user',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->installEntitySchema('asset');
  }

  /**
   * Test trimString() method.
   */
  public function testTrimString() {

    // Test that a 255 character string is not trimmed.
    $long_string = 'Lorem ipsum dolor sit amet, nonummy ligula volutpat hac integer nonummy. Suspendisse ultricies, congue etiam tellus, erat libero, nulla eleifend, mauris pellentesque. Suspendisse integer praesent vel, integer gravida mauris, fringilla vehicula lacinia non';
    $name = $this->trimString($long_string, 255);
    $this->assertEquals($long_string, $name);

    // Test that a 256 character string is trimmed on a word boundary.
    $extra_long_string = 'Lorem ipsum dolor sit amet, nonummy ligula volutpat hac integer nonummy. Suspendisse ultricies, congue etiam tellus, erat libero, nulla eleifend, mauris pellentesque. Suspendisse integer praesent vel, integer gravida mauris, fringilla vehicula lacinia non!';
    $trimmed_extra_long_string = 'Lorem ipsum dolor sit amet, nonummy ligula volutpat hac integer nonummy. Suspendisse ultricies, congue etiam tellus, erat libero, nulla eleifend, mauris pellentesque. Suspendisse integer praesent vel, integer gravida mauris, fringilla vehicula lacinia…';
    $name = $this->trimString($extra_long_string, 255);
    $this->assertEquals($trimmed_extra_long_string, $name);
  }

  /**
   * Test prioritizedString() method.
   */
  public function testPrioritizedString() {

    // Define simple name parts.
    $parts = [
      'foo' => 'Foo',
      'bar' => 'Bar',
      'baz' => 'Baz',
    ];

    // Test simple name.
    $name = $this->prioritizedString($parts);
    $this->assertEquals('Foo Bar Baz', $name);

    // Test simple maximum lengths.
    $name = $this->prioritizedString($parts, [], 1);
    $this->assertEquals('…', $name);
    $name = $this->prioritizedString($parts, [], 5);
    $this->assertEquals('Foo…', $name);
    $name = $this->prioritizedString($parts, [], 10);
    $this->assertEquals('Foo Bar…', $name);

    // Test custom suffix.
    $name = $this->prioritizedString($parts, [], 3, 'OO');
    $this->assertEquals('FOO', $name);

    // Test priority keys.
    $priority_keys = ['foo', 'baz'];
    $name = $this->prioritizedString($parts, $priority_keys, 10);
    $this->assertEquals('Foo B… Baz', $name);
  }

  /**
   * Test entityLabelsSummary() method.
   */
  public function testEntityLabelsSummary() {

    // Create a test asset type.
    $asset_type = AssetType::create([
      'id' => 'test',
      'label' => 'Test',
      'workflow' => 'asset_default',
    ]);
    $asset_type->save();

    // Create 10 assets with randomly generated names.
    $assets = [];
    for ($i = 0; $i < 10; $i++) {
      $asset = Asset::create([
        'name' => $this->randomString(),
        'type' => 'test',
        'status' => 'active',
      ]);
      $asset->save();
      $assets[] = $asset;
    }

    // Test default with a cutoff of 3.
    $expected = $assets[0]->label() . ', ' . $assets[1]->label() . ', ' . $assets[2]->label() . ' (+7 more)';
    $name_summary = $this->entityLabelsSummary($assets);
    $this->assertEquals($expected, $name_summary);

    // Test with a cutoff of 1.
    $expected = $assets[0]->label() . ' (+9 more)';
    $name_summary = $this->entityLabelsSummary($assets, 1);
    $this->assertEquals($expected, $name_summary);

    // Test with a cutoff of 0.
    $labels = [];
    foreach ($assets as $asset) {
      $labels[] = $asset->label();
    }
    $expected = implode(', ', $labels);
    $name_summary = $this->entityLabelsSummary($assets, 0);
    $this->assertEquals($expected, $name_summary);
  }

}
