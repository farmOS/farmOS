<?php

namespace Drupal\farm_entity\Plugin\Log\LogType;

use Drupal\entity\BundlePlugin\BundlePluginInterface;

/**
 * Defines the interface for log types.
 */
interface LogTypeInterface extends BundlePluginInterface {

  /**
   * Gets the log type label.
   *
   * @return string
   *   The log type label.
   */
  public function getLabel();

  /**
   * Gets the log workflow ID.
   *
   * @return string
   *   The log workflow ID.
   */
  public function getWorkflowId();

}
