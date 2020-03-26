<?php

namespace Drupal\farm_asset\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining farm_asset entities.
 *
 * @ingroup farm_asset
 */
interface FarmAssetInterface extends ContentEntityInterface, EntityChangedInterface, RevisionLogInterface, EntityOwnerInterface {

  /**
   * Gets the farm_asset name.
   *
   * @return string
   *   The farm_asset name.
   */
  public function getName();

  /**
   * Sets the farm_asset name.
   *
   * @param string $name
   *   The farm_asset name.
   *
   * @return \Drupal\farm_asset\Entity\FarmAssetInterface
   *   The farm_asset entity.
   */
  public function setName($name);

  /**
   * Gets the farm_asset creation timestamp.
   *
   * @return int
   *   Creation timestamp of the farm_asset.
   */
  public function getCreatedTime();

  /**
   * Sets the farm_asset creation timestamp.
   *
   * @param int $timestamp
   *   Creation timestamp of the farm_asset.
   *
   * @return \Drupal\farm_asset\Entity\FarmAssetInterface
   *   The farm_asset entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Gets the label of the the farm_asset type.
   *
   * @return string
   *   The label of the farm_asset type.
   */
  public function getBundleLabel();

}
