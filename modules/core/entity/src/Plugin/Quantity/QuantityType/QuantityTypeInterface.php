<?php

namespace Drupal\farm_entity\Plugin\Quantity\QuantityType;

use Drupal\entity\BundlePlugin\BundlePluginInterface;

/**
 * Defines the interface for quantity types.
 */
interface QuantityTypeInterface extends BundlePluginInterface {

  /**
   * Gets the quantity type label.
   *
   * @return string
   *   The quantity type label.
   */
  public function getLabel();

}
