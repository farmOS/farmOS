<?php

namespace Drupal\farm_ui_views\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides task links for farmOS Taxonomy Term Views.
 */
class FarmTaxonomyTermViewsTaskLink extends DeriverBase implements ContainerDeriverInterface {

  use StringTranslationTrait;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The entity type bundle info.
   *
   * @var \Drupal\Core\Entity\EntityTypeBundleInfoInterface
   */
  protected $entityTypeBundleInfo;

  /**
   * Constructs a FarmTaxonomyTermViewsTaskLink instance.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   * @param \Drupal\Core\Entity\EntityTypeBundleInfoInterface $entity_bundle_info
   *   The entity type bundle info service.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, EntityTypeBundleInfoInterface $entity_bundle_info) {
    $this->entityTypeManager = $entity_type_manager;
    $this->entityTypeBundleInfo = $entity_bundle_info;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('entity_type.bundle.info')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    $links = [];

    // Add asset and log task links to taxonomy term pages.
    foreach (['asset', 'log'] as $entity_type_id) {

      // Get the entity type definition.
      $entity_type = $this->entityTypeManager->getDefinition($entity_type_id);

      // Add tab for each entity type.
      $links[$entity_type_id] = [
        'title' => $entity_type->getCollectionLabel(),
        'route_name' => "view.farm_$entity_type_id.page_term",
        'base_route' => 'entity.taxonomy_term.canonical',
        'weight' => 50,
      ] + $base_plugin_definition;

      // Build the parent id from the base ID.
      $base_id = $base_plugin_definition['id'];
      $parent_id = "$base_id:$entity_type_id";

      // Add default "All" secondary tab for each entity type.
      $links["$entity_type_id.all"] = [
        'title' => $this->t('All'),
        'parent_id' => $parent_id,
        'route_name' => "view.farm_$entity_type_id.page_term",
        'route_parameters' => [
          'entity_bundle' => 'all',
        ],
      ] + $base_plugin_definition;

      // Add secondary tab for each entity bundle.
      $entity_bundles = $this->entityTypeBundleInfo->getBundleInfo($entity_type_id);
      foreach ($entity_bundles as $entity_bundle => $info) {
        $links["$entity_type_id.$entity_bundle"] = [
          'title' => $info['label'],
          'parent_id' => $parent_id,
          'route_name' => "view.farm_$entity_type_id.page_term",
          'route_parameters' => [
            'entity_bundle' => $entity_bundle,
          ],
        ] + $base_plugin_definition;
      }
    }

    return $links;
  }

}
