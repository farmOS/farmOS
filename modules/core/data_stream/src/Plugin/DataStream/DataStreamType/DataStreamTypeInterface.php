<?php

namespace Drupal\data_stream\Plugin\DataStream\DataStreamType;

use Drupal\entity\BundlePlugin\BundlePluginInterface;

/**
 * Defines the interface for data stream types.
 */
interface DataStreamTypeInterface extends BundlePluginInterface {

  /**
   * Gets the data stream type label.
   *
   * @return string
   *   The data stream type label.
   */
  public function getLabel();

  /**
   * Returns views data for the data stream type.
   *
   * @see \Drupal\views\EntityViewsData::getViewsData()
   *
   * @return array
   *   Views data in the format of hook_views_data().
   */
  public function getViewsData();

}
