<?php

declare(strict_types=1);

namespace Drupal\farm_ui_metrics\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Link;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Provides a 'Metrics' block.
 */
#[Block(
  id: 'farm_metrics_block',
  admin_label: new TranslatableMarkup('Farm Metrics'),
)]
class FarmMetricsBlock extends BlockBase implements ContainerFactoryPluginInterface {

  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    protected EntityTypeManagerInterface $entityTypeManager,
    protected EntityTypeBundleInfoInterface $bundleInfo,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $output = [];

    // Create a list of asset metrics.
    $assets_label = $this->entityTypeManager->getStorage('asset')->getEntityType()->getCollectionLabel();
    $output['asset'] = [
      '#theme' => 'item_list',
      '#title' => Link::createFromRoute($assets_label, 'view.farm_asset.page')->toRenderable(),
      '#items' => $this->getEntityMetrics('asset'),
      '#empty' => $this->t('No assets found.'),
      '#wrapper_attributes' => [
        'class' => ['assets', 'metrics-container'],
      ],
      '#cache' => [
        'tags' => [
          'asset_list',
          'config:asset_type_list',
        ],
      ],
    ];

    // Create a list of log metrics.
    $logs_label = $this->entityTypeManager->getStorage('log')->getEntityType()->getCollectionLabel();
    $output['log'] = [
      '#theme' => 'item_list',
      '#title' => Link::createFromRoute($logs_label, 'view.farm_log.page')->toRenderable(),
      '#items' => $this->getEntityMetrics('log'),
      '#empty' => $this->t('No logs found.'),
      '#wrapper_attributes' => [
        'class' => ['logs', 'metrics-container'],
      ],
      '#cache' => [
        'tags' => [
          'log_list',
          'config:log_type_list',
        ],
      ],
    ];

    // Attach CSS.
    $output['#attached']['library'][] = 'farm_ui_metrics/metrics_block';

    // Return the output.
    return $output;
  }

  /**
   * Gather metrics for rendering in the block.
   *
   * @param string $entity_type
   *   The entity type machine name.
   *
   * @return array
   *   Returns an array of metric information.
   */
  protected function getEntityMetrics($entity_type) {
    $metrics = [];

    // Load bundles.
    $bundles = $this->bundleInfo->getBundleInfo($entity_type);

    // Count records by type.
    foreach ($bundles as $bundle => $bundle_info) {
      $query = $this->entityTypeManager->getStorage($entity_type)->getAggregateQuery()
        ->accessCheck(TRUE)
        ->condition('type', $bundle);

      // Exclude archived assets.
      if ($entity_type == 'asset') {
        $query->condition('archived', 0);
      }

      $count = $query->count()->execute();
      $route_name = "view.farm_$entity_type.page_type";
      $metrics[] = Link::createFromRoute($bundle_info['label'] . ': ' . $count, $route_name, ['arg_0' => $bundle], ['attributes' => ['class' => ['metric']]])->toRenderable();
    }

    return $metrics;
  }

}
