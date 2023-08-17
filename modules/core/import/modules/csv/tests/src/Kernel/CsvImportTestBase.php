<?php

namespace Drupal\Tests\farm_import_csv\Kernel;

use Drupal\Tests\migrate\Kernel\MigrateTestBase;
use Drupal\Tests\user\Traits\UserCreationTrait;

/**
 * Base class for farmOS CSV importer kernel tests.
 *
 * @group farm
 */
class CsvImportTestBase extends MigrateTestBase {

  use UserCreationTrait;

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
    'farm_entity_fields',
    'farm_field',
    'farm_format',
    'farm_import',
    'farm_import_csv',
    'farm_import_csv_test',
    'file',
    'filter',
    'image',
    'log',
    'migrate',
    'migrate_plus',
    'migrate_source_csv',
    'migrate_source_ui',
    'migrate_tools',
    'state_machine',
    'system',
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
    $this->installEntitySchema('asset');
    $this->installEntitySchema('taxonomy_term');
    $this->installEntitySchema('user');
    $this->installConfig(['farm_format']);

    // Run tests as the user 1 to avoid permissions issues.
    $this->setUpCurrentUser(['uid' => 1]);
  }

}
