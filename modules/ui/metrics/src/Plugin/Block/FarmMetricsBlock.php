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

    // Create a container for asset metrics.
    $output['asset'] = [
      '#markup' => '<strong>' . Link::createFromRoute('Assets', 'view.farm_asset.page')->toString() . '</strong>',
      'metrics' => [
        '#type' => 'container',
        '#attributes' => [
          'class' => 'assets metrics-container',
        ],
      ],
    ];
    $metrics = $this->getEntityMetrics('asset');
    foreach ($metrics as $metric) {
      $output['asset']['metrics'][] = [
        '#markup' => $metric,
      ];
    }
    if (empty($metrics)) {
      $output['asset']['metrics']['empty']['#markup'] = '<p>' . $this->t('No assets found.') . '</p>';
    }
    $output['#cache']['tags'][] = 'asset_list';
    $output['#cache']['tags'][] = 'config:asset_type_list';

    // Create a section for log metrics.
    $output['log'] = [
      '#markup' => '<strong>' . Link::createFromRoute('Logs', 'view.farm_log.page')->toString() . '</strong>',
      'metrics' => [
        '#type' => 'container',
        '#attributes' => [
          'class' => 'logs metrics-container',
        ],
      ],
    ];
    $metrics = $this->getEntityMetrics('log');
    foreach ($metrics as $metric) {
      $output['log']['metrics'][] = [
        '#markup' => $metric,
      ];
    }
    if (empty($metrics)) {
      $output['log']['metrics']['empty']['#markup'] = '<p>' . $this->t('No logs found.') . '</p>';
    }
    $output['#cache']['tags'][] = 'log_list';
    $output['#cache']['tags'][] = 'config:log_type_list';

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
      $count = $this->entityTypeManager->getStorage($entity_type)->getAggregateQuery()
        ->condition('type', $bundle)
        ->count()
        ->execute();
      $route_name = "view.farm_$entity_type.page_type";
      $metrics[] = Link::createFromRoute($bundle_info['label'] . ': ' . $count, $route_name, ['arg_0' => $bundle], ['attributes' => ['class' => ['metric', 'button']]])->toString();
    }

    return $metrics;
  }

}
