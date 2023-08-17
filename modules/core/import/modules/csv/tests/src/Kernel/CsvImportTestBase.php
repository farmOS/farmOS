<?php

namespace Drupal\Tests\farm_import_csv\Kernel;

use Drupal\Tests\migrate\Kernel\MigrateTestBase;

/**
 * Base class for farmOS CSV importer kernel tests.
 *
 * @group farm
 */
class CsvImportTestBase extends MigrateTestBase {

  /**
   * The migration manager.
   *
   * @var \Drupal\migrate\Plugin\MigrationPluginManagerInterface
   */
  protected $migrationManager;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'asset',
    'farm_import',
    'farm_import_csv',
    'farm_import_csv_test',
    'filter',
    'log',
    'migrate',
    'migrate_plus',
    'migrate_source_csv',
    'migrate_source_ui',
    'migrate_tools',
    'taxonomy',
    'text',
    'user',
  ];

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();
    $this->migrationManager = $this->container->get('plugin.manager.migration');
    $this->installEntitySchema('taxonomy_term');
  }

}
