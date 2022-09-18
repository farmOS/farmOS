<?php

namespace Drupal\farm_location;

use Drupal\asset\Entity\AssetInterface;
use Drupal\log\Entity\LogInterface;

/**
 * Asset location logic.
 */
interface AssetLocationInterface {

  /**
   * Check if an asset is a location.
   *
   * @param \Drupal\asset\Entity\AssetInterface $asset
   *   The Asset entity.
   *
   * @return bool
   *   Returns TRUE if it is a location, FALSE otherwise.
   */
  public function isLocation(AssetInterface $asset): bool;

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
   * @param int|null $timestamp
   *   Include logs with a timestamp less than or equal to this.
   *   If this is NULL (default), the current time will be used.
   *
   * @return bool
   *   Returns TRUE if the asset has a location, FALSE otherwise.
   */
  public function hasLocation(AssetInterface $asset, $timestamp = NULL): bool;

  /**
   * Check if an asset has geometry.
   *
   * @param \Drupal\asset\Entity\AssetInterface $asset
   *   The Asset entity.
   * @param int|null $timestamp
   *   Include logs with a timestamp less than or equal to this.
   *   If this is NULL (default), the current time will be used.
   *
   * @return bool
   *   Returns TRUE if the asset has geometry, FALSE otherwise.
   */
  public function hasGeometry(AssetInterface $asset, $timestamp = NULL): bool;

  /**
   * Get location assets that an asset is located within.
   *
   * @param \Drupal\asset\Entity\AssetInterface $asset
   *   The Asset entity.
   * @param int|null $timestamp
   *   Include logs with a timestamp less than or equal to this.
   *   If this is NULL (default), the current time will be used.
   *
   * @return array
   *   Returns an array of assets.
   */
  public function getLocation(AssetInterface $asset, $timestamp = NULL): array;

  /**
   * Get an asset's geometry.
   *
   * @param \Drupal\asset\Entity\AssetInterface $asset
   *   The Asset entity.
   * @param int|null $timestamp
   *   Include logs with a timestamp less than or equal to this.
   *   If this is NULL (default), the current time will be used.
   *
   * @return string
   *   Returns a WKT string.
   */
  public function getGeometry(AssetInterface $asset, $timestamp = NULL): string;

  /**
   * Find the latest movement log that references an asset.
   *
   * @param \Drupal\asset\Entity\AssetInterface $asset
   *   The Asset entity.
   * @param int $timestamp
   *   Include logs with a timestamp less than or equal to this.
   *   If this is 0 (default), the current time will be used.
   *
   * @return \Drupal\log\Entity\LogInterface|null
   *   A log entity, or NULL if no logs were found.
   */
  public function getMovementLog(AssetInterface $asset, int $timestamp = 0): ?LogInterface;

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
   * Get assets that are in locations.
   *
   * @param \Drupal\asset\Entity\AssetInterface[] $locations
   *   An array of location assets to lookup.
   * @param int|null $timestamp
   *   Include logs with a timestamp less than or equal to this.
   *   If this is NULL (default), the current time will be used.
   *
   * @return \Drupal\asset\Entity\AssetInterface[]
   *   An array of asset objects indexed by their IDs.
   */
  public function getAssetsByLocation(array $locations, $timestamp = NULL): array;

}
