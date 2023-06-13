<?php

namespace Drupal\farm_ui_views\Access;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Routing\Access\AccessInterface;
use Drupal\Core\Routing\RouteMatchInterface;

/**
 * Checks access for displaying Views of entities that reference taxonomy terms.
 */
class FarmTaxonomyTermEntityViewsAccessCheck implements AccessInterface {

  /**
   * The base entity type of the views this access check will be applied to.
   *
   * @var string
   */
  protected $baseEntityType;

  /**
   * The taxonomy term storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $taxonomyTermStorage;

  /**
   * The entity type bundle info.
   *
   * @var \Drupal\Core\Entity\EntityTypeBundleInfoInterface
   */
  protected $entityTypeBundleInfo;

  /**
   * The entity field manager service.
   *
   * @var \Drupal\Core\Entity\EntityFieldManagerInterface
   */
  protected $entityFieldManager;

  /**
   * FarmTaxonomyTermEntityViewsAccessCheck constructor.
   *
   * @param string $base_entity_type
   *   The base entity type of the views this access check will be applied to.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   * @param \Drupal\Core\Entity\EntityTypeBundleInfoInterface $entity_bundle_info
   *   The entity type bundle info service.
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entity_field_manager
   *   The entity field manager service.
   */
  public function __construct(string $base_entity_type, EntityTypeManagerInterface $entity_type_manager, EntityTypeBundleInfoInterface $entity_bundle_info, EntityFieldManagerInterface $entity_field_manager) {
    $this->baseEntityType = $base_entity_type;
    $this->taxonomyTermStorage = $entity_type_manager->getStorage('taxonomy_term');
    $this->entityTypeBundleInfo = $entity_bundle_info;
    $this->entityFieldManager = $entity_field_manager;
  }

  /**
   * A custom access check to filter out irrelevant entity bundles.
   *
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The route match.
   */
  public function access(RouteMatchInterface $route_match) {

    // If there is no "taxonomy_term" or "asset_type" parameter, bail.
    $term_id = $route_match->getParameter('taxonomy_term');
    $entity_bundle = $route_match->getParameter('entity_bundle');

    if (empty($term_id) || empty($entity_bundle)) {
      return AccessResult::forbidden();
    }

    $term = $this->taxonomyTermStorage->load($term_id);

    // Loop through all the entity bundles of the base entity type for the view
    // and only return AccessResult::allowed() for those which have a taxonomy
    // term entity reference field referencing the taxonomy term bundle of the
    // term we loaded above.
    $bundles = $this->entityTypeBundleInfo->getBundleInfo($this->baseEntityType);
    foreach (array_keys($bundles) as $type) {
      // If the route argument is 'all' then we check all the bundles, otherwise
      // only check the one that matches.
      if ($entity_bundle == 'all' || $type == $entity_bundle) {
        $field_definitions = $this->entityFieldManager->getFieldDefinitions($this->baseEntityType, $type);

        foreach (array_values($field_definitions) as $field_definition) {
          if ($field_definition->getType() == "entity_reference" && $field_definition->getSetting('target_type') == "taxonomy_term") {
            $handler_settings = $field_definition->getSetting('handler_settings') ?? [];

            if (in_array($term->bundle(), $handler_settings['target_bundles'] ?? [])) {
              return AccessResult::allowed();
            }
          }
        }
      }
    }

    return AccessResult::forbidden();
  }

}
