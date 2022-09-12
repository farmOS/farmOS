<?php

namespace Drupal\farm_ui_views\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides task links for farmOS Logs Views.
 */
class FarmLogViewsTaskLink extends DeriverBase implements ContainerDeriverInterface {

  use StringTranslationTrait;

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a FarmActions object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static(
      $container->get('entity_type.manager'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    $links = [];

    // Add links for each bundle.
    $bundles = $this->entityTypeManager->getStorage('log_type')->loadMultiple();
    foreach ($bundles as $type => $bundle) {
      $links['farm.asset.logs.' . $type] = [
        'title' => $bundle->label(),
        'parent_id' => 'farm.asset.logs',
        'route_name' => 'view.farm_log.page_asset',
        'route_parameters' => [
          'log_type' => $type,
        ],
      ] + $base_plugin_definition;
    }

    return $links;
  }

}
