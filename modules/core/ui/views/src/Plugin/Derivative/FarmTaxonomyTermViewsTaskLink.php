<?php

namespace Drupal\farm_ui_views\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides task links for farmOS Taxonomy Term Views.
 */
class FarmTaxonomyTermViewsTaskLink extends DeriverBase implements ContainerDeriverInterface {

  use StringTranslationTrait;

  /**
   * The entity type bundle info.
   *
   * @var \Drupal\Core\Entity\EntityTypeBundleInfoInterface
   */
  protected $entityTypeBundleInfo;

  /**
   * Constructs a FarmTaxonomyTermViewsTaskLink instance.
   *
   * @param \Drupal\Core\Entity\EntityTypeBundleInfoInterface $entity_bundle_info
   *   The entity type bundle info service.
   */
  public function __construct(EntityTypeBundleInfoInterface $entity_bundle_info) {
    $this->entityTypeBundleInfo = $entity_bundle_info;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static(
      $container->get('entity_type.bundle.info')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    $links = [];

    // Add asset and log task links to taxonomy term pages.
    foreach (['asset', 'log'] as $entity_type) {

      // Add default "All" secondary tab for each entity type.
      $links["farm.taxonomy_term.{$entity_type}s.all"] = [
        'title' => $this->t('All'),
        'parent_id' => "farm.taxonomy_term.{$entity_type}s",
        'route_name' => "view.farm_$entity_type.page_term",
        'route_parameters' => [
          'entity_bundle' => 'all',
        ],
      ] + $base_plugin_definition;

      // Add secondary tab for each entity bundle.
      $entity_bundles = $this->entityTypeBundleInfo->getBundleInfo($entity_type);
      foreach ($entity_bundles as $entity_bundle => $info) {
        $links["farm.taxonomy_term.{$entity_type}s.$entity_bundle"] = [
          'title' => $info['label'],
          'parent_id' => "farm.taxonomy_term.{$entity_type}s",
          'route_name' => "view.farm_$entity_type.page_term",
          'route_parameters' => [
            'entity_bundle' => $entity_bundle,
          ],
        ] + $base_plugin_definition;
      }
    }

    return $links;
  }

}
