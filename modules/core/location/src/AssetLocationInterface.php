<?php

namespace Drupal\farm_location;

use Drupal\asset\Entity\AssetInterface;
use Drupal\log\Entity\LogInterface;

/**
 * Asset location logic.
 */
interface AssetLocationInterface {

  /**
   * Check if an asset is fixed.
   *
   * @param \Drupal\asset\Entity\AssetInterface $asset
   *   The Asset entity.
   *
   * @return bool
   *   Returns TRUE if it is fixed, FALSE otherwise.
   */
  public function isFixed(AssetInterface $asset): bool;

  /**
   * Check if an asset is located within other location assets.
   *
   * @param \Drupal\asset\Entity\AssetInterface $asset
   *   The Asset entity.
   *
   * @return bool
   *   Returns TRUE if the asset has a location, FALSE otherwise.
   */
  public function hasLocation(AssetInterface $asset): bool;

  /**
   * Check if an asset has geometry.
   *
   * @param \Drupal\asset\Entity\AssetInterface $asset
   *   The Asset entity.
   *
   * @return bool
   *   Returns TRUE if the asset has geometry, FALSE otherwise.
   */
  public function hasGeometry(AssetInterface $asset): bool;

  /**
   * Get location assets that an asset is located within.
   *
   * @param \Drupal\asset\Entity\AssetInterface $asset
   *   The Asset entity.
   *
   * @return array
   *   Returns an array of assets.
   */
  public function getLocation(AssetInterface $asset): array;

  /**
   * Get an asset's geometry.
   *
   * @param \Drupal\asset\Entity\AssetInterface $asset
   *   The Asset entity.
   *
   * @return string
   *   Returns a WKT string.
   */
  public function getGeometry(AssetInterface $asset): string;

  /**
   * Find the latest movement log that references an asset.
   *
   * @param \Drupal\asset\Entity\AssetInterface $asset
   *   The Asset entity.
   *
   * @return \Drupal\log\Entity\LogInterface|null
   *   A log entity, or NULL if no logs were found.
   */
  public function getMovementLog(AssetInterface $asset): ?LogInterface;

  /**
   * Set an asset's intrinsic geometry.
   *
   * @param \Drupal\asset\Entity\AssetInterface $asset
   *   The Asset entity.
   * @param string $wkt
   *   Geometry as a WKT string.
   */
  public function setIntrinsicGeometry(AssetInterface $asset, string $wkt): void;

  /**
   * Get assets that are in a location.
   *
   * @param \Drupal\asset\Entity\AssetInterface $location
   *   The location asset entity.
   *
   * @return array
   *   Returns an array of assets.
   */
  public function getAssetsByLocation(AssetInterface $location): array;

}
