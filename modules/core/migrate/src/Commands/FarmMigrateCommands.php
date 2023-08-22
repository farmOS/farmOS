<?php

namespace Drupal\farm_migrate\Commands;

use Drupal\migrate_tools\Drush\MigrateToolsCommands;

/**
 * Farm Migrate Drush commands.
 *
 * @ingroup farm
 *
 * @deprecated in farm:2.2.0 and is removed from farm:3.0.0. Migrate from farmOS
 *   v1 to v2 before upgrading to farmOS v3.
 * @see https://www.drupal.org/node/3382609
 */
class FarmMigrateCommands extends MigrateToolsCommands {

  /**
   * Perform a 1.x data migration.
   *
   * @command farm_migrate:import
   *
   * @usage farm_migrate:import
   */
  public function farmMigrate() {
    $this->executeFarmMigrations();
  }

  /**
   * Rollback a 1.x data migration.
   *
   * @command farm_migrate:rollback
   *
   * @usage farm_migrate:rollback
   */
  public function farmRollback() {
    $this->executeFarmRollback();
  }

  /**
   * Define the farmOS migration groups in the order they should be executed.
   *
   * @return array
   *   Array of migration group names.
   */
  protected function farmMigrationGroups() {
    return [
      'farm_migrate_config',
      'farm_migrate_role',
      'farm_migrate_user',
      'farm_migrate_file',
      'farm_migrate_taxonomy',
      'farm_migrate_asset',
      'farm_migrate_area',
      'farm_migrate_asset_parent',
      'farm_migrate_sensor_data',
      'farm_migrate_quantity',
      'farm_migrate_log',
      'farm_migrate_plan',
    ];
  }

  /**
   * Executes all farmOS migrations.
   *
   * @throws \Exception
   *   If some migrations failed during execution.
   */
  protected function executeFarmMigrations() {
    $groups = $this->farmMigrationGroups();
    foreach ($groups as $group) {
      $options = [
        'group' => $group,
      ];
      $this->logger()->notice('Importing migration group: ' . $group);
      $this->import('', $options);
    }
  }

  /**
   * Rollback all farmOS migrations.
   *
   * @throws \Exception
   *   If some rollbacks failed during execution.
   */
  protected function executeFarmRollback() {
    $groups = $this->farmMigrationGroups();
    $groups = array_reverse($groups);
    foreach ($groups as $group) {
      $options = [
        'group' => $group,
      ];
      $this->logger()->notice('Rolling back migration group: ' . $group);
      $this->rollback('', $options);
    }
  }

}
