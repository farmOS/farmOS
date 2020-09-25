<?php

namespace Drupal\farm_flag\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface for defining FarmFlag config entities.
 *
 * @ingroup farm
 */
interface FarmFlagInterface extends ConfigEntityInterface {

  /**
   * Returns the flag label.
   *
   * @return string
   *   The flag label.
   */
  public function getLabel();

}
