<?php

namespace Drupal\farm_map\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\Core\Entity\EntityDescriptionInterface;

/**
 * Provides an interface for defining MapBehavior config entities.
 */
interface MapBehaviorInterface extends ConfigEntityInterface, EntityDescriptionInterface {

  /**
   * Returns the behavior library.
   *
   * @return string
   *   The behavior library.
   */
  public function getLibrary();

  /**
   * Returns the behavior settings.
   *
   * @return mixed|null
   *   The behavior settings.
   */
  public function getSettings();

}
