<?php

namespace Drupal\farm_import_csv\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Drupal\migrate\Plugin\MigrationPluginManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides menu links for CSV importers.
 */
class CsvImportMenuLink extends DeriverBase implements ContainerDeriverInterface {

  /**
   * The migration plugin manager.
   *
   * @var \Drupal\migrate\Plugin\MigrationPluginManagerInterface
   */
  protected MigrationPluginManagerInterface $migrationPluginManager;

  /**
   * CsvImportMenuLink constructor.
   *
   * @param \Drupal\migrate\Plugin\MigrationPluginManagerInterface $migration_plugin_manager
   *   The migration plugin manager.
   */
  public function __construct(MigrationPluginManagerInterface $migration_plugin_manager) {
    $this->migrationPluginManager = $migration_plugin_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static(
      $container->get('plugin.manager.migration'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    $links = [];

    // Load CSV importers.
    $importers = $this->migrationPluginManager->getDefinitions();

    // Add a link for each CSV importer in the farm_import_csv group.
    foreach ($importers as $id => $importer) {
      if (!($importer['source']['plugin'] == 'csv_file' && $importer['migration_group'] == 'farm_import_csv')) {
        continue;
      }
      $route_id = 'farm.import.csv.' . $id;
      $links[$route_id] = [
        'title' => $importer['label'],
        'parent' => 'farm.import.csv',
        'route_name' => 'farm.import.csv.importer',
        'route_parameters' => [
          'migration_id' => $id,
        ],
      ] + $base_plugin_definition;
    }

    return $links;
  }

}
