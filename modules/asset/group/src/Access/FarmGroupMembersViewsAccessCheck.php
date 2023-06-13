<?php

namespace Drupal\farm_group\Access;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Routing\Access\AccessInterface;
use Drupal\Core\Routing\RouteMatchInterface;

/**
 * Checks access for displaying Views of group members.
 */
class FarmGroupMembersViewsAccessCheck implements AccessInterface {

  /**
   * The asset storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $assetStorage;

  /**
   * FarmGroupMembersViewsAccessCheck constructor.
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

    // Allow access if the asset is a group.
    /** @var \Drupal\asset\Entity\AssetInterface $asset */
    $asset = $this->assetStorage->load($asset_id);
    $access = AccessResult::allowedIf($asset->bundle() == 'group');

    // Invalidate the access result when assets are changed.
    $access->addCacheTags(["asset_list"]);
    return $access;
  }

}
