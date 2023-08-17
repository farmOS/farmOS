<?php

namespace Drupal\Tests\farm_import_csv\Kernel;

use Drupal\log\Entity\Log;

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

    // Confirm that logs have been created with the expected values.
    $logs = Log::loadMultiple();
    $this->assertCount(3, $logs);
    $expected_values = [
      1 => [
        'timestamp' => 1689343200,
        'name' => 'Harvest garlic',
        'notes' => 'Great big bulbs',
        'status' => 'done',
      ],
      2 => [
        'timestamp' => 1692021600,
        'name' => 'Harvest potatoes',
        'notes' => 'Heavy harvest',
        'status' => 'done',
      ],
      3 => [
        'timestamp' => 1694700000,
        'name' => 'Harvest onions',
        'notes' => 'Small bulbs from weed pressure',
        'status' => 'pending',
      ],
    ];
    foreach ($logs as $id => $log) {
      $this->assertEquals('harvest', $log->bundle());
      $this->assertEquals($expected_values[$id]['name'], $log->label());
      $this->assertEquals($expected_values[$id]['timestamp'], $log->get('timestamp')->value);
      $this->assertEquals($expected_values[$id]['notes'], $log->get('notes')->value);
      $this->assertEquals('default', $log->get('notes')->format);
      $this->assertEquals($expected_values[$id]['status'], $log->get('status')->value);
    }
  }

}
