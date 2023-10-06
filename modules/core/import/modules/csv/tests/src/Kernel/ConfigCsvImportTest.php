<?php

namespace Drupal\Tests\farm_import_csv\Kernel;

use Drupal\log\Entity\Log;
use Drupal\taxonomy\Entity\Term;

/**
 * Tests for config CSV importers.
 *
 * @group farm
 */
class ConfigCsvImportTest extends CsvImportTestBase {

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
    $this->installConfig(['farm_import_csv_test']);
  }

  /**
   * Test log CSV importer.
   */
  public function testConfigCsvImport() {

    // Run the CSV import.
    $this->importCsv('egg-harvests.csv', 'egg_harvest');

    // Confirm that one taxonomy term was created with the expected values.
    $terms = Term::loadMultiple();
    $this->assertCount(1, $terms);
    $this->assertEquals('egg(s)', $terms[1]->label());

    // Confirm that logs have been created with the expected values.
    $logs = Log::loadMultiple();
    $this->assertCount(3, $logs);
    $expected_values = [
      1 => [
        'timestamp' => 1687442400,
        'quantity' => '8',
      ],
      2 => [
        'timestamp' => 1687528800,
        'quantity' => '2',
      ],
      3 => [
        'timestamp' => 1687615200,
        'quantity' => '1',
      ],
    ];
    foreach ($logs as $id => $log) {
      $this->assertEquals('harvest', $log->bundle());
      $this->assertEquals($expected_values[$id]['timestamp'], $log->get('timestamp')->value);
      $this->assertEquals('Collected ' . $expected_values[$id]['quantity'] . ' egg(s)', $log->label());
      $this->assertEquals('count', $log->get('quantity')->referencedEntities()[0]->get('measure')->value);
      $this->assertEquals($expected_values[$id]['quantity'], $log->get('quantity')->referencedEntities()[0]->get('value')->decimal);
      $this->assertEquals('egg(s)', $log->get('quantity')->referencedEntities()[0]->get('units')->referencedEntities()[0]->label());
      $this->assertEquals('done', $log->get('status')->value);
    }
  }

}
