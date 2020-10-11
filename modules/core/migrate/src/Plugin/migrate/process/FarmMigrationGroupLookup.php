<?php

namespace Drupal\farm_migrate\Plugin\migrate\process;

use Drupal\migrate\Plugin\migrate\process\MigrationLookup;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;

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
 * @todo
 * Remove if migrate_plus incorporates this plugin upstream.
 * See: https://gitlab.com/drupalspoons/migrate_plus/-/issues/240
 */
class FarmMigrationGroupLookup extends MigrationLookup {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {

    // Get the migration group ID from the process configuration.
    $lookup_migration_group_id = $this->configuration['migration_group'];

    // Load all migrations.
    $manager = \Drupal::service('plugin.manager.migration');
    $migrations = $manager->createInstances([]);

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
