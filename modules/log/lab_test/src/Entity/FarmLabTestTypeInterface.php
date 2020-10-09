<?php

namespace Drupal\farm_lab_test\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface for defining FarmLabTestType config entities.
 *
 * @ingroup farm
 */
interface FarmLabTestTypeInterface extends ConfigEntityInterface {

  /**
   * Returns the lab test type label.
   *
   * @return string
   *   The lab test type label.
   */
  public function getLabel();

}
