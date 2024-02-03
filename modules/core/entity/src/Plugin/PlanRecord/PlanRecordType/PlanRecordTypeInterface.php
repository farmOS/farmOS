<?php

namespace Drupal\farm_entity\Plugin\PlanRecord\PlanRecordType;

use Drupal\entity\BundlePlugin\BundlePluginInterface;

/**
 * Defines the interface for plan record relationship types.
 */
interface PlanRecordTypeInterface extends BundlePluginInterface {

  /**
   * Gets the plan record relationship type label.
   *
   * @return string
   *   The plan record relationship type label.
   */
  public function getLabel();

}
