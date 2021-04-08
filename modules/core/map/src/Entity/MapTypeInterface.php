<?php

namespace Drupal\farm_map\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\Core\Entity\EntityDescriptionInterface;

/**
 * Provides an interface for defining MapType config entities.
 */
interface MapTypeInterface extends ConfigEntityInterface, EntityDescriptionInterface {

  /**
   * Returns behaviors to add to the map.
   *
   * @return string[]
   *   The list of map behaviors.
   */
  public function getMapBehaviors();

  /**
   * Returns the options to pass to farmOS-map.
   *
   * @return mixed|null
   *   The map options.
   */
  public function getMapOptions();

}
