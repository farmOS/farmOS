<?php

namespace Drupal\farm_inventory;

use Drupal\asset\Entity\AssetInterface;

/**
 * Asset inventory logic.
 */
interface AssetInventoryInterface {

  /**
   * Get inventory summaries for an asset.
   *
   * @param \Drupal\asset\Entity\AssetInterface $asset
   *   The Asset entity.
   * @param string $measure
   *   The quantity measure of the inventory. See quantity_measures().
   * @param int $units
   *   The quantity units of the inventory (term ID).
   *
   * @return array
   *   Returns an array of asset inventory information.
   */
  public function getInventory(AssetInterface $asset, string $measure = '', int $units = 0): array;

}
