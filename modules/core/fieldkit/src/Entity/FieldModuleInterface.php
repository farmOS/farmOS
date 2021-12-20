<?php

namespace Drupal\farm_fieldkit\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\Core\Entity\EntityDescriptionInterface;

/**
 * Provides an interface for defining FieldModule config entities.
 *
 * @ingroup farm
 */
interface FieldModuleInterface extends ConfigEntityInterface, EntityDescriptionInterface {

  /**
   * Returns the field module label.
   *
   * @return string
   *   The field module label.
   */
  public function getLabel();

  /**
   * Returns the field module library.
   *
   * @return string
   *   The field module library.
   */
  public function getLibrary();

}
