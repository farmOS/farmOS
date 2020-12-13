<?php

namespace Drupal\farm_entity\Plugin\Asset\AssetType;

use Drupal\entity\BundlePlugin\BundlePluginInterface;

/**
 * Defines the interface for asset types.
 */
interface AssetTypeInterface extends BundlePluginInterface {

  /**
   * Gets the asset type label.
   *
   * @return string
   *   The asset type label.
   */
  public function getLabel();

  /**
   * Gets the asset workflow ID.
   *
   * @return string
   *   The asset workflow ID.
   */
  public function getWorkflowId();

}
