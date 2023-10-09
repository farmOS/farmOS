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
    'entity',
    'farm_entity',
    'farm_equipment',
    'farm_id_tag',
    'farm_parent',
  ];

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();
    $this->installConfig(['farm_id_tag', 'farm_equipment']);

    // Add an asset to test parent relationship.
    $asset = Asset::create(['name' => 'Test parent', 'type' => 'equipment', 'status' => 'active']);
    $asset->save();
  }

  /**
   * Test asset CSV importer.
   */
  public function testAssetCsvImport() {

    // Run the CSV import.
    $this->importCsv('equipment.csv', 'csv_asset:equipment');

    // Confirm that 3 assets have been created with the expected values
    // (in addition to the 1 we created in setUp() above).
    $assets = Asset::loadMultiple();
    $this->assertCount(4, $assets);
    $expected_values = [
      2 => [
        'name' => 'Old tractor',
        'manufacturer' => '',
        'model' => '',
        'serial_number' => '',
        'id_tag' => [
          'id' => '12345',
          'type' => '',
          'location' => '',
        ],
        'parents' => [],
        'notes' => 'Inherited from Grandpa',
        'status' => 'archived',
      ],
      3 => [
        'name' => 'New tractor',
        'manufacturer' => '',
        'model' => '',
        'serial_number' => '',
        'id_tag' => [
          'id' => '67890',
          'type' => 'eid',
          'location' => 'trunk',
        ],
        'parents' => [],
        'notes' => 'Purchased recently',
        'status' => 'active',
      ],
      4 => [
        'name' => 'Baler',
        'manufacturer' => 'New Idea',
        'model' => '483 Round Baler',
        'serial_number' => '1234567890',
        'id_tag' => [
          'id' => '',
          'type' => '',
          'location' => '',
        ],
        'parents' => [
          'Test parent',
        ],
        'notes' => 'Makes big bales',
        'status' => 'active',
      ],
    ];
    foreach ($assets as $id => $asset) {
      // Skip assets created in setup().
      if ($id <= 1) {
        continue;
      }
      $this->assertEquals('equipment', $asset->bundle());
      $this->assertEquals($expected_values[$id]['name'], $asset->label());
      $this->assertEquals($expected_values[$id]['manufacturer'], $asset->get('manufacturer')->value);
      $this->assertEquals($expected_values[$id]['model'], $asset->get('model')->value);
      $this->assertEquals($expected_values[$id]['serial_number'], $asset->get('serial_number')->value);
      $this->assertEquals($expected_values[$id]['id_tag']['id'], $asset->get('id_tag')->id);
      $this->assertEquals($expected_values[$id]['id_tag']['type'], $asset->get('id_tag')->type);
      $this->assertEquals($expected_values[$id]['id_tag']['location'], $asset->get('id_tag')->location);
      $parents = $asset->get('parent')->referencedEntities();
      $this->assertEquals(count($expected_values[$id]['parents']), count($parents));
      foreach ($parents as $parent) {
        $this->assertContains($parent->label(), $expected_values[$id]['parents']);
      }
      $this->assertEquals($expected_values[$id]['notes'], $asset->get('notes')->value);
      $this->assertEquals('default', $asset->get('notes')->format);
      $this->assertEquals($expected_values[$id]['status'], $asset->get('status')->value);
      $this->assertEquals('Imported via CSV.', $asset->getRevisionLogMessage());
    }
  }

}
