<?php

namespace Drupal\Tests\farm_import_csv\Kernel;

use Drupal\asset\Entity\Asset;
use Drupal\log\Entity\Log;
use Drupal\taxonomy\Entity\Term;

/**
 * Tests for log CSV importers.
 *
 * @group farm
 */
class LogCsvImportTest extends CsvImportTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'farm_harvest',
    'farm_id_tag',
    'farm_land',
    'farm_land_types',
    'farm_location',
    'farm_log',
    'farm_log_asset',
    'farm_log_category',
    'farm_map',
    'farm_plant',
    'farm_plant_type',
    'geofield',
    'rest',
    'serialization',
    'views_geojson',
  ];

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();
    $this->installConfig(['farm_harvest', 'farm_plant', 'farm_land', 'farm_land_types', 'farm_location']);

    // Create assets to test asset_lookup.
    $terms[] = Term::create(['name' => 'Garlic', 'vid' => 'plant_type']);
    $terms[] = Term::create(['name' => 'Potato', 'vid' => 'plant_type']);
    foreach ($terms as $term) {
      $term->save();
    }
    $assets[] = Asset::create(['name' => 'Garlic', 'type' => 'plant', 'plant_type' => 1, 'status' => 'active']);
    $assets[] = Asset::create(['name' => 'Potatoes 1', 'type' => 'plant', 'plant_type' => 2, 'status' => 'active']);
    $assets[] = Asset::create(['name' => 'Potatoes 2', 'type' => 'plant', 'plant_type' => 2, 'id_tag' => ['id' => '1234'], 'status' => 'active']);
    $assets[] = Asset::create(['name' => 'Field A', 'type' => 'land', 'land_type' => 'land', 'status' => 'active']);
    $assets[] = Asset::create(['name' => 'Field B', 'type' => 'land', 'land_type' => 'land', 'status' => 'active']);
    $assets[] = Asset::create(['name' => 'Field C', 'type' => 'land', 'land_type' => 'land', 'status' => 'active']);
    foreach ($assets as $asset) {
      $asset->save();
    }

    // Create log categories.
    $term = Term::create(['name' => 'Category 1', 'vid' => 'log_category']);
    $term->save();
    $term = Term::create(['name' => 'Category 2', 'vid' => 'log_category']);
    $term->save();
  }

  /**
   * Test log CSV importer.
   */
  public function testLogCsvImport() {

    // Run the CSV import.
    $this->importCsv('harvests.csv', 'csv_log:harvest');

    // Confirm that two taxonomy terms were created with the expected values
    // (in addition to the 4 we created in setUp() above).
    $terms = Term::loadMultiple();
    $this->assertCount(6, $terms);
    $this->assertEquals('bulbs', $terms[5]->label());
    $this->assertEquals('lbs', $terms[6]->label());

    // Confirm that logs have been created with the expected values.
    $logs = Log::loadMultiple();
    $this->assertCount(3, $logs);
    $expected_values = [
      1 => [
        'name' => 'Harvest garlic',
        'timestamp' => 1689343200,
        'assets' => [
          'Garlic',
        ],
        'locations' => [
          'Field A',
        ],
        'quantity' => [
          'measure' => 'count',
          'value' => '200',
          'units' => 'bulbs',
          'label' => 'total',
        ],
        'notes' => 'Great big bulbs',
        'categories' => [
          'Category 1',
        ],
        'status' => 'done',
      ],
      2 => [
        'name' => 'Harvest potatoes',
        'timestamp' => 1692021600,
        'assets' => [
          'Potatoes 1',
          'Potatoes 2',
        ],
        'locations' => [
          'Field B',
          'Field C',
        ],
        'quantity' => [
          'measure' => 'weight',
          'value' => '80',
          'units' => 'lbs',
          'label' => '',
        ],
        'notes' => 'Heavy harvest',
        'categories' => [
          'Category 1',
          'Category 2',
        ],
        'status' => 'done',
      ],
      3 => [
        'name' => 'Harvest onions',
        'timestamp' => 1694700000,
        'assets' => [],
        'locations' => [],
        'quantity' => [
          'measure' => 'weight',
          'value' => '',
          'units' => 'lbs',
          'label' => '',
        ],
        'notes' => 'Small bulbs from weed pressure',
        'categories' => [],
        'status' => 'pending',
      ],
    ];
    foreach ($logs as $id => $log) {
      $this->assertEquals('harvest', $log->bundle());
      $this->assertEquals($expected_values[$id]['name'], $log->label());
      $assets = $log->get('asset')->referencedEntities();
      $this->assertEquals(count($expected_values[$id]['assets']), count($assets));
      foreach ($assets as $asset) {
        $this->assertContains($asset->label(), $expected_values[$id]['assets']);
      }
      $locations = $log->get('location')->referencedEntities();
      $this->assertEquals(count($expected_values[$id]['locations']), count($locations));
      foreach ($locations as $location) {
        $this->assertContains($location->label(), $expected_values[$id]['locations']);
      }
      $this->assertEquals($expected_values[$id]['quantity']['measure'], $log->get('quantity')->referencedEntities()[0]->get('measure')->value);
      $this->assertEquals($expected_values[$id]['quantity']['value'], $log->get('quantity')->referencedEntities()[0]->get('value')->decimal);
      $this->assertEquals($expected_values[$id]['quantity']['units'], $log->get('quantity')->referencedEntities()[0]->get('units')->referencedEntities()[0]->label());
      $this->assertEquals($expected_values[$id]['quantity']['label'], $log->get('quantity')->referencedEntities()[0]->get('label')->value);
      $this->assertEquals($expected_values[$id]['timestamp'], $log->get('timestamp')->value);
      $this->assertEquals($expected_values[$id]['notes'], $log->get('notes')->value);
      $this->assertEquals('default', $log->get('notes')->format);
      if (!empty($expected_values[$id]['categories'])) {
        foreach ($log->get('category')->referencedEntities() as $category) {
          $this->assertTRUE(in_array($category->label(), $expected_values[$id]['categories']));
        }
      }
      $this->assertEquals($expected_values[$id]['status'], $log->get('status')->value);
      $this->assertEquals('Imported via CSV.', $log->getRevisionLogMessage());
    }
  }

}
