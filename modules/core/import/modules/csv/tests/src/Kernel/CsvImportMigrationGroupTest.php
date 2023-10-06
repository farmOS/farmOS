<?php

namespace Drupal\Tests\farm_import_csv\Kernel;

/**
 * Tests for CSV import migration group.
 *
 * @group farm
 */
class CsvImportMigrationGroupTest extends CsvImportTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'farm_animal_type',
    'farm_equipment',
    'farm_harvest',
    'farm_id_tag',
  ];

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();
    $this->installConfig(['farm_animal_type']);
    $this->installConfig(['farm_equipment']);
    $this->installConfig(['farm_harvest']);
    $this->installConfig(['farm_import_csv_test']);
  }

  /**
   * Test that migration group configuration is applied.
   */
  public function testMigrationGroupConfig() {
    $migrations = $this->migrationManager->getDefinitions();
    foreach ($migrations as $migration) {
      $this->assertEquals('farm_import_csv', $migration['migration_group']);
      $this->assertEquals('csv_file', $migration['source']['plugin']);
      $this->assertEquals('tmp://', $migration['source']['path']);
      $this->assertTrue($migration['destination']['validate']);
    }
  }

}
