<?php

declare(strict_types=1);

namespace Drupal\farm_ui_views\Plugin\views\argument;

use Drupal\views\Attribute\ViewsArgument;
use Drupal\views\Plugin\ViewsHandlerManager;
use Drupal\views\Plugin\views\argument\ArgumentPluginBase;
use Drupal\views\Plugin\views\query\Sql;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * Argument handler for both the asset and location fields on logs.
 */
#[ViewsArgument("asset_or_location")]
class AssetOrLocationArgument extends ArgumentPluginBase {

  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    #[Autowire(service: 'plugin.manager.views.join')]
    protected ViewsHandlerManager $viewsJoinPluginManager,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public function query($group_by = FALSE) {

    // Bail if not a SQL query.
    if (!$this->query instanceof Sql) {
      return;
    }

    // Join the log__asset table with a condition to match the asset ID.
    $this->ensureMyTable();
    /** @var \Drupal\views\Plugin\views\join\JoinPluginBase $join */
    $join = $this->viewsJoinPluginManager->createInstance('standard', [
      'table' => 'log__asset',
      'field' => 'entity_id',
      'left_table' => $this->table,
      'left_field' => 'id',
      'extra' => [
        [
          'field' => 'deleted',
          'value' => 0,
        ],
        [
          'field' => 'asset_target_id',
          'value' => $this->argument,
        ],
      ],
    ]);
    $asset_alias = $this->query->addRelationship('log__asset', $join, $this->table);

    // Join the log__location table with a condition to match the asset ID.
    /** @var \Drupal\views\Plugin\views\join\JoinPluginBase $join */
    $join = $this->viewsJoinPluginManager->createInstance('standard', [
      'table' => 'log__location',
      'field' => 'entity_id',
      'left_table' => $this->table,
      'left_field' => 'id',
      'extra' => [
        [
          'field' => 'deleted',
          'value' => 0,
        ],
        [
          'field' => 'location_target_id',
          'value' => $this->argument,
        ],
      ],
    ]);
    $location_alias = $this->query->addRelationship('log__location', $join, $this->table);

    // Limit the query to only include logs that reference the asset on the
    // asset OR location field. This must be added in a single where expression
    // so the condition is not combined with other filters from the view.
    $asset_condition = "$asset_alias.asset_target_id IS NOT NULL";
    $location_condition = "$location_alias.location_target_id IS NOT NULL";
    $this->query->addWhereExpression('0', "$this->table.id IS NOT NULL AND ($asset_condition OR $location_condition)");
  }

}
