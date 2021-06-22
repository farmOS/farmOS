<?php

namespace Drupal\quantity\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\Core\Entity\EntityDescriptionInterface;
use Drupal\Core\Entity\RevisionableEntityBundleInterface;

/**
 * Provides an interface for defining quantity type entities.
 */
interface QuantityTypeInterface extends ConfigEntityInterface, EntityDescriptionInterface, RevisionableEntityBundleInterface {

  /**
   * Get the quantity type's default measure.
   *
   * @return string
   *   The default measure, or null if none is specified.
   */
  public function getDefaultMeasure();

}
