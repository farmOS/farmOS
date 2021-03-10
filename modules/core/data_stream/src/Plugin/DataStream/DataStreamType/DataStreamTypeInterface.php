<?php

namespace Drupal\data_stream\Plugin\DataStream\DataStreamType;

use Drupal\Core\Plugin\PluginFormInterface;
use Drupal\entity\BundlePlugin\BundlePluginInterface;

/**
 * Defines the interface for data stream types.
 */
interface DataStreamTypeInterface extends BundlePluginInterface, PluginFormInterface {

  /**
   * Gets the data stream type label.
   *
   * @return string
   *   The data stream type label.
   */
  public function getLabel();

}
