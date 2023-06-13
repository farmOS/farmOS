<?php

namespace Drupal\farm_ui_views\Access;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Routing\Access\AccessInterface;
use Drupal\Core\Routing\RouteMatchInterface;

/**
 * Checks access for displaying Views of inventory quantities for an asset.
 */
class FarmInventoryAssetViewsAccessCheck implements AccessInterface {

  /**
   * The asset storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $assetStorage;

  /**
   * FarmInventoryAssetViewsAccessCheck constructor.
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

    // Allow access if the asset has an inventory.
    /** @var \Drupal\asset\Entity\AssetInterface $asset */
    $asset = $this->assetStorage->load($asset_id);
    $access = AccessResult::allowedIf($asset->hasField('inventory') && !$asset->get('inventory')->isEmpty());

    // Invalidate the access result when the asset is changed.
    $access->addCacheTags($asset->getCacheTags());
    return $access;
  }

}
