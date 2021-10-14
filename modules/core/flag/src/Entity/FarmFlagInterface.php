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

  /**
   * Returns the entity types and bundles that this flag applies to.
   *
   * @return array
   *   An array of arrays, keyed by entity type machine name, listing bundles
   *   (or `all`) that this flag applies to.
   */
  public function getEntityTypes();

}
