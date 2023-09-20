<?php

namespace Drupal\farm_import_csv\Access;

use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;
use Drupal\migrate\Plugin\MigrationPluginManager;

/**
 * Access checking logic for CSV importers.
 */
class CsvImportMigrationAccess {

  /**
   * The migration plugin manager.
   *
   * @var \Drupal\migrate\Plugin\MigrationPluginManager
   */
  protected $pluginManagerMigration;

  /**
   * MigrateSourceUiForm constructor.
   *
   * @param \Drupal\migrate\Plugin\MigrationPluginManager $plugin_manager_migration
   *   The migration plugin manager.
   */
  public function __construct(MigrationPluginManager $plugin_manager_migration) {
    $this->pluginManagerMigration = $plugin_manager_migration;
  }

  /**
   * Checks access to a migration.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user account to check access for.
   * @param string $migration_id
   *   The migration ID.
   *
   * @return \Drupal\Core\Access\AccessResultInterface
   *   An access result.
   */
  public function access(AccountInterface $account, string $migration_id) {

    // Load the migration definition.
    try {
      $definition = $this->pluginManagerMigration->getDefinition($migration_id);
    }
    catch (PluginNotFoundException $e) {
      return AccessResult::forbidden();
    }

    // If the source plugin is csv_file, and it is in the farm_import_csv
    // migration group, check access based on third party settings.
    if ($definition['source']['plugin'] == 'csv_file' && $definition['migration_group'] == 'farm_import_csv') {
      $permissions = [];
      if (!empty($definition['third_party_settings']['farm_import_csv']['access']['permissions'])) {
        $permissions = $definition['third_party_settings']['farm_import_csv']['access']['permissions'];
      }
      return AccessResult::allowedIfHasPermissions($account, $permissions);
    }

    // Otherwise, deny access.
    return AccessResult::forbidden();
  }

}
