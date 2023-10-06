<?php

namespace Drupal\farm_ui_metrics\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Link;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Metrics' block.
 *
 * @Block(
 *   id = "farm_metrics_block",
 *   admin_label = @Translation("Farm Metrics")
 * )
 */
class FarmMetricsBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The bundle info service.
   *
   * @var \Drupal\Core\Entity\EntityTypeBundleInfoInterface
   */
  protected $bundleInfo;

  /**
   * Constructs a \Drupal\Component\Plugin\PluginBase object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   * @param \Drupal\Core\Entity\EntityTypeBundleInfoInterface $bundle_info
   *   The bundle info service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager, EntityTypeBundleInfoInterface $bundle_info) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
    $this->bundleInfo = $bundle_info;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('entity_type.bundle.info')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $output = [];

    // Create a list of asset metrics.
    $assets_label = $this->entityTypeManager->getStorage('asset')->getEntityType()->getCollectionLabel() . ' (' . $this->t('active') . ')';
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

      // Only include active assets.
      if ($entity_type == 'asset') {
        $query->condition('status', 'active');
      }

      $count = $query->count()->execute();
      $route_name = "view.farm_$entity_type.page_type";
      $metrics[] = Link::createFromRoute($bundle_info['label'] . ': ' . $count, $route_name, ['arg_0' => $bundle], ['attributes' => ['class' => ['metric']]])->toRenderable();
    }

    return $metrics;
  }

}
