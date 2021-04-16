<?php

namespace Drupal\farm_map\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface for defining LayerStyle config entities.
 */
interface LayerStyleInterface extends ConfigEntityInterface {

  /**
   * Returns the layer style conditions.
   *
   * @return mixed|null
   *   The layer style conditions.
   */
  public function getConditions();

}
