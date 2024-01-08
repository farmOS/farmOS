<?php

namespace Drupal\farm_entity\Plugin\PlanRecord\PlanRecordType;

use Drupal\farm_entity\FarmEntityTypeBase;

/**
 * Provides the base plan record relationship type class.
 */
abstract class PlanRecordTypeBase extends FarmEntityTypeBase implements PlanRecordTypeInterface {

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
