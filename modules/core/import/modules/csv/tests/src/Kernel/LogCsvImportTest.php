<?php

namespace Drupal\Tests\farm_import_csv\Kernel;

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
  ];

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();
    $this->installConfig(['farm_harvest']);
  }

  /**
   * Test log CSV importer.
   */
  public function testLogCsvImport() {

    // Initialize the migration for harvest logs.
    $migration = $this->migrationManager->createInstance('log:harvest');

    // Execute the migration.
    $this->executeMigration($migration);

    // Confirm that two taxonomy terms were created with the expected values.
    $terms = Term::loadMultiple();
    $this->assertCount(2, $terms);
    $this->assertEquals('bulbs', $terms[1]->label());
    $this->assertEquals('lbs', $terms[2]->label());

    // Confirm that logs have been created with the expected values.
    $logs = Log::loadMultiple();
    $this->assertCount(3, $logs);
    $expected_values = [
      1 => [
        'name' => 'Harvest garlic',
        'timestamp' => 1689343200,
        'quantity' => [
          'measure' => 'count',
          'value' => '200',
          'units' => 'bulbs',
          'label' => 'total',
        ],
        'notes' => 'Great big bulbs',
        'status' => 'done',
      ],
      2 => [
        'name' => 'Harvest potatoes',
        'timestamp' => 1692021600,
        'quantity' => [
          'measure' => 'weight',
          'value' => '80',
          'units' => 'lbs',
          'label' => '',
        ],
        'notes' => 'Heavy harvest',
        'status' => 'done',
      ],
      3 => [
        'name' => 'Harvest onions',
        'timestamp' => 1694700000,
        'quantity' => [
          'measure' => 'weight',
          'value' => '',
          'units' => 'lbs',
          'label' => '',
        ],
        'notes' => 'Small bulbs from weed pressure',
        'status' => 'pending',
      ],
    ];
    foreach ($logs as $id => $log) {
      $this->assertEquals('harvest', $log->bundle());
      $this->assertEquals($expected_values[$id]['name'], $log->label());
      $this->assertEquals($expected_values[$id]['quantity']['measure'], $log->get('quantity')->referencedEntities()[0]->get('measure')->value);
      $this->assertEquals($expected_values[$id]['quantity']['value'], $log->get('quantity')->referencedEntities()[0]->get('value')->decimal);
      $this->assertEquals($expected_values[$id]['quantity']['units'], $log->get('quantity')->referencedEntities()[0]->get('units')->referencedEntities()[0]->label());
      $this->assertEquals($expected_values[$id]['quantity']['label'], $log->get('quantity')->referencedEntities()[0]->get('label')->value);
      $this->assertEquals($expected_values[$id]['timestamp'], $log->get('timestamp')->value);
      $this->assertEquals($expected_values[$id]['notes'], $log->get('notes')->value);
      $this->assertEquals('default', $log->get('notes')->format);
      $this->assertEquals($expected_values[$id]['status'], $log->get('status')->value);
    }
  }

}
