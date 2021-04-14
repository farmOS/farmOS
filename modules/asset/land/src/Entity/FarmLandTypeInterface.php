<?php

namespace Drupal\farm_land\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface for defining FarmLandType config entities.
 *
 * @ingroup farm
 */
interface FarmLandTypeInterface extends ConfigEntityInterface {

  /**
   * Returns the land type label.
   *
   * @return string
   *   The land type label.
   */
  public function getLabel();

}
