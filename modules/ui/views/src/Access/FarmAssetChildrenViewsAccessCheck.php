<?php

namespace Drupal\farm_ui_views\Access;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Routing\Access\AccessInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Checks access for displaying Views of asset children.
 */
class FarmAssetChildrenViewsAccessCheck implements AccessInterface {

  /**
   * The asset storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $assetStorage;

  /**
   * FarmAssetChildrenViewsAccessCheck constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->assetStorage = $entity_type_manager->getStorage('asset');
  }

  /**
   * A custom access check.
   *
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The route match.
   */
  public function access(RouteMatchInterface $route_match) {

    // If there is no "asset" parameter, bail.
    $asset_id = $route_match->getParameter('asset');
    if (empty($asset_id)) {
      return AccessResult::allowed();
    }

    // Run a count query to see if there are any assets that reference this
    // asset as a parent.
    $result = $this->assetStorage->getAggregateQuery()
      ->condition('parent.entity.id', $asset_id)
      ->aggregate('id', 'COUNT')
      ->execute();

    // Determine access based on the child count.
    $child_count = (int) $result[0]['id_count'] ?? 0;
    $access = AccessResult::allowedIf($child_count > 0);

    // Invalidate the access result when assets are changed.
    $access->addCacheTags(["asset_list"]);
    return $access;
  }

}
