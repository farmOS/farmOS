<?php

namespace Drupal\farm_entity\Plugin\Plan\PlanType;

use Drupal\entity\BundlePlugin\BundlePluginInterface;

/**
 * Defines the interface for plan types.
 */
interface PlanTypeInterface extends BundlePluginInterface {

  /**
   * Gets the plan type label.
   *
   * @return string
   *   The plan type label.
   */
  public function getLabel();

  /**
   * Gets the plan workflow ID.
   *
   * @return string
   *   The plan workflow ID.
   */
  public function getWorkflowId();

}
