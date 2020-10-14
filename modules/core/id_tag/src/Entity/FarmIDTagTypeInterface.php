<?php

namespace Drupal\farm_id_tag\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface for defining FarmIDTagType config entities.
 *
 * @ingroup farm
 */
interface FarmIDTagTypeInterface extends ConfigEntityInterface {

  /**
   * Returns the ID tag type label.
   *
   * @return string
   *   The ID tag type label.
   */
  public function getLabel();

}
