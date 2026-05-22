<?php

declare(strict_types=1);

namespace Drupal\Tests\farm_import_csv\Kernel;

use Drupal\asset\Entity\Asset;
use Drupal\log\Entity\Log;
use Drupal\taxonomy\Entity\Term;
use Drupal\text\Plugin\Field\FieldType\TextLongItem;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * Tests for log CSV importers.
 */
#[Group('farm')]
#[RunTestsInSeparateProcesses]
class LogCsvImportTest extends CsvImportTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'farm_activity',
    'farm_category',
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
    $this->installConfig([
      'farm_activity',
      'farm_harvest',
      'farm_plant',
      'farm_land',
      'farm_land_types',
      'farm_location',
    ]);

    // Create assets to test asset_lookup.
    $terms[] = Term::create(['name' => 'Garlic', 'vid' => 'plant_type']);
    $terms[] = Term::create(['name' => 'Potato', 'vid' => 'plant_type']);
    foreach ($terms as $term) {
      $term->save();
    }
    $assets[] = Asset::create(['name' => 'Garlic', 'type' => 'plant', 'plant_type' => 1]);
    $assets[] = Asset::create(['name' => 'Potatoes 1', 'type' => 'plant', 'plant_type' => 2]);
    $assets[] = Asset::create(['name' => 'Potatoes 2', 'type' => 'plant', 'plant_type' => 2, 'id_tag' => ['id' => '1234']]);
    $assets[] = Asset::create(['name' => 'Field A', 'type' => 'land', 'land_type' => 'land']);
    $assets[] = Asset::create(['name' => 'Field B', 'type' => 'land', 'land_type' => 'land']);
    $assets[] = Asset::create(['name' => 'Field C', 'type' => 'land', 'land_type' => 'land']);
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

    // Run the harvest CSV import.
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
        'flags' => [
          'priority',
          'review',
        ],
        'status' => 'done',
        'test_string' => 'foo',
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
        'flags' => [
          'monitor',
        ],
        'status' => 'done',
        'test_string' => 'bar',
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
        'flags' => [],
        'status' => 'pending',
        'test_string' => 'baz',
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
      $this->assertInstanceOf(TextLongItem::class, $log->get('notes')->first());
      $this->assertEquals('default', $log->get('notes')->first()->format);
      if (!empty($expected_values[$id]['categories'])) {
        foreach ($log->get('category')->referencedEntities() as $category) {
          $this->assertTrue(in_array($category->label(), $expected_values[$id]['categories']));
        }
      }
      if (!empty($expected_values[$id]['flags'])) {
        foreach ($log->get('flag') as $flag) {
          $this->assertTRUE(in_array($flag->getValue()['value'], $expected_values[$id]['flags']));
        }
      }
      $this->assertEquals($expected_values[$id]['status'], $log->get('status')->value);
      $this->assertEquals($expected_values[$id]['test_string'], $log->get('test_string')->value);
      $this->assertEquals('Imported via CSV.', $log->getRevisionLogMessage());
    }

    // Run the movement (activity) CSV import.
    $this->importCsv('movements.csv', 'csv_log:activity');

    // Load the log that was created and confirm it has expected values.
    $log = Log::load(4);
    $this->assertEquals('activity', $log->bundle());
    $this->assertEquals(1725890400, $log->get('timestamp')->value);
    $this->assertEquals('Move Garlic to Field B', $log->label());
    $this->assertEquals('Garlic', $log->get('asset')->referencedEntities()[0]->label());
    $this->assertEquals('Field B', $log->get('location')->referencedEntities()[0]->label());
    $this->assertEquals('POINT(1 2)', $log->get('geometry')->value);
    $this->assertEquals(1, $log->get('is_movement')->value);
    $this->assertEquals('done', $log->get('status')->value);
  }

}
