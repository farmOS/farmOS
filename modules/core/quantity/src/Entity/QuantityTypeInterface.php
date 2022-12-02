<?php

namespace Drupal\quantity\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\Core\Entity\EntityDescriptionInterface;
use Drupal\Core\Entity\RevisionableEntityBundleInterface;

/**
 * Provides an interface for defining quantity type entities.
 */
interface QuantityTypeInterface extends ConfigEntityInterface, EntityDescriptionInterface, RevisionableEntityBundleInterface {

}
