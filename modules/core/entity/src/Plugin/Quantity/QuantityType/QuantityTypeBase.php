<?php

namespace Drupal\farm_entity\Plugin\Quantity\QuantityType;

use Drupal\farm_entity\FarmEntityTypeBase;

/**
 * Provides the base quantity type class.
 */
abstract class QuantityTypeBase extends FarmEntityTypeBase implements QuantityTypeInterface {

  /**
   * {@inheritdoc}
   */
  public function getLabel() {
    return $this->pluginDefinition['label'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    return [];
  }

}
