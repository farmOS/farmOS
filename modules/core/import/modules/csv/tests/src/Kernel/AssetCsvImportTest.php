<?php

namespace Drupal\Tests\farm_import_csv\Kernel;

use Drupal\asset\Entity\Asset;

/**
 * Tests for asset CSV importers.
 *
 * @group farm
 */
class AssetCsvImportTest extends CsvImportTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'farm_equipment',
  ];

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();
    $this->installConfig(['farm_equipment']);
  }

  /**
   * Test asset CSV importer.
   */
  public function testAssetCsvImport() {

    // Run the CSV import.
    $this->importCsv('equipment.csv', 'asset:equipment');

    // Confirm that assets have been created with the expected values.
    $assets = Asset::loadMultiple();
    $this->assertCount(3, $assets);
    $expected_values = [
      1 => [
        'name' => 'Old tractor',
        'notes' => 'Inherited from Grandpa',
        'status' => 'archived',
      ],
      2 => [
        'name' => 'New tractor',
        'notes' => 'Purchased recently',
        'status' => 'active',
      ],
      3 => [
        'name' => 'Baler',
        'notes' => 'Makes big bales',
        'status' => 'active',
      ],
    ];
    foreach ($assets as $id => $asset) {
      $this->assertEquals('equipment', $asset->bundle());
      $this->assertEquals($expected_values[$id]['name'], $asset->label());
      $this->assertEquals($expected_values[$id]['notes'], $asset->get('notes')->value);
      $this->assertEquals('default', $asset->get('notes')->format);
      $this->assertEquals($expected_values[$id]['status'], $asset->get('status')->value);
    }
  }

}
