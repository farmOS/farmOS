<?php

namespace Drupal\Tests\farm_import_csv\Kernel;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\file\Entity\File;
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
    'entity_reference_revisions',
    'entity_reference_validators',
    'farm_entity_fields',
    'farm_entity_views',
    'farm_field',
    'farm_format',
    'farm_import',
    'farm_import_csv',
    'farm_import_csv_test',
    'farm_log_quantity',
    'farm_migrate',
    'farm_quantity_standard',
    'file',
    'filter',
    'fraction',
    'image',
    'log',
    'migrate',
    'migrate_plus',
    'migrate_source_csv',
    'migrate_source_ui',
    'migrate_tools',
    'options',
    'quantity',
    'state_machine',
    'system',
    'taxonomy',
    'text',
    'user',
    'views',
  ];

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();
    $this->migrationManager = $this->container->get('plugin.manager.migration');
    $this->installEntitySchema('asset');
    $this->installEntitySchema('file');
    $this->installEntitySchema('log');
    $this->installEntitySchema('quantity');
    $this->installEntitySchema('taxonomy_term');
    $this->installEntitySchema('user');
    $this->installConfig(['farm_format', 'farm_entity_views', 'farm_quantity_standard', 'farm_import_csv']);
    $this->installSchema('farm_import_csv', ['farm_import_csv_entity']);

    // Run tests as the user 1 to avoid permissions issues.
    $this->setUpCurrentUser(['uid' => 1]);

    // Set the private:// filesystem to use the artifacts directory of the
    // farm_import_csv_test module.
    $this->setSetting('file_private_path', \Drupal::service('extension.list.module')->getPath('farm_import_csv_test') . '/artifacts');
  }

  /**
   * {@inheritdoc}
   */
  public function register(ContainerBuilder $container) {
    parent::register($container);

    // Register the private:// stream wrapper.
    $container->register('stream_wrapper.private', 'Drupal\Core\StreamWrapper\PrivateStream')
      ->addTag('stream_wrapper', ['scheme' => 'private']);
  }

  /**
   * Helper method for running a CSV file migration.
   *
   * @param string $filename
   *   The artifact filename.
   * @param string $migration_id
   *   The migration ID.
   */
  public function importCsv(string $filename, string $migration_id) {

    // Create a file entity.
    $file = File::create([
      'uid' => 1,
      'status' => 1,
      'filename' => $filename,
      'uri' => 'private://' . $filename,
      'filemime' => 'text/csv',
    ]);
    $file->save();

    // Set the source file path configuration.
    $configuration['source']['path'] = $file->getFileUri();

    // Initialize and run the migration.
    $migration = $this->migrationManager->createInstance($migration_id, $configuration);
    $this->executeMigration($migration);
  }

}
