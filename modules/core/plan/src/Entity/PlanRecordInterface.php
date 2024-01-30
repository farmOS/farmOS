<?php

namespace Drupal\plan\Entity;

use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Provides an interface for defining plan record relationship entities.
 */
interface PlanRecordInterface extends ContentEntityInterface {

  /**
   * Gets the label of the plan record relationship type.
   *
   * @return string
   *   The label of the plan record relationship type.
   */
  public function getBundleLabel();

}
