<?php

namespace Drupal\farm_migrate\Plugin\migrate\process;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\MigrateLookupInterface;
use Drupal\migrate\MigrateStubInterface;
use Drupal\migrate\Plugin\migrate\process\MigrationLookup;
use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate\Plugin\MigrationPluginManagerInterface;
use Drupal\migrate\Row;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Looks up the value of a property based on a previous migration group.
 *
 * @MigrateProcessPlugin(
 *   id = "farm_migration_group_lookup"
 * )
 *
 * This extends from the core migration_lookup process plugin, loads a list of
 * all migrations in the specified migration_group, then passes that to the
 * parent class, along with all other configuration keys.
 *
 * Example:
 *
 * @code
 * process:
 *   uid:
 *     plugin: farm_migration_group_lookup
 *     migration_group: users
 *     source: author
 * @endcode
 *
 * @todo Remove if migrate_plus incorporates this plugin upstream.
 * See: https://gitlab.com/drupalspoons/migrate_plus/-/issues/240
 *
 * @deprecated in farm:2.2.0 and is removed from farm:3.0.0. Migrate from farmOS
 *   v1 to v2 before upgrading to farmOS v3.
 * @see https://www.drupal.org/node/3382609
 */
class FarmMigrationGroupLookup extends MigrationLookup {

  /**
   * Migration plugin manager service.
   *
   * @var \Drupal\migrate\Plugin\MigrationPluginManagerInterface
   */
  protected $migrationPluginManager;

  /**
   * Constructs a MigrationLookup object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\migrate\Plugin\MigrationInterface $migration
   *   The Migration the plugin is being used in.
   * @param \Drupal\migrate\MigrateLookupInterface $migrate_lookup
   *   The migrate lookup service.
   * @param \Drupal\migrate\MigrateStubInterface $migrate_stub
   *   The migrate stub service.
   * @param \Drupal\migrate\Plugin\MigrationPluginManagerInterface $migation_plugin_manager
   *   Migration plugin manager service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, MigrationInterface $migration, MigrateLookupInterface $migrate_lookup, MigrateStubInterface $migrate_stub, MigrationPluginManagerInterface $migation_plugin_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $migration, $migrate_lookup, $migrate_stub);
    $this->migrationPluginManager = $migation_plugin_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition, MigrationInterface $migration = NULL) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $migration,
      $container->get('migrate.lookup'),
      $container->get('migrate.stub'),
      $container->get('plugin.manager.migration'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {

    // Get the migration group ID from the process configuration.
    $lookup_migration_group_id = $this->configuration['migration_group'];

    // Load all migrations.
    $migrations = $this->migrationPluginManager->createInstances([]);

    // Filter by group.
    $group_migrations = [];
    foreach ($migrations as $id => $migration) {
      $definition = $migration->getPluginDefinition();
      if ($definition['migration_group'] == $lookup_migration_group_id) {
        $group_migrations[] = $id;
      }
    }

    // Set the migration configuration and delegate processing to the parent
    // MigrationLookup::transform() method.
    $this->configuration['migration'] = $group_migrations;
    return parent::transform($value, $migrate_executable, $row, $destination_property);
  }

}
