<?php

namespace Drupal\farm_client\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\Core\Entity\EntityDescriptionInterface;

/**
 * Provides an interface for defining ClientModule config entities.
 *
 * @ingroup farm
 */
interface ClientModuleInterface extends ConfigEntityInterface, EntityDescriptionInterface {

  /**
   * Returns the client module label.
   *
   * @return string
   *   The client module label.
   */
  public function getLabel();

  /**
   * Returns the client module library.
   *
   * @return string
   *   The client module library.
   */
  public function getLibrary();

}
