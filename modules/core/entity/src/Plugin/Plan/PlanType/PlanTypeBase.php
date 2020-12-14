<?php

namespace Drupal\farm_entity\Plugin\Plan\PlanType;

use Drupal\farm_entity\FarmEntityTypeBase;

/**
 * Provides the base plan type class.
 */
abstract class PlanTypeBase extends FarmEntityTypeBase implements PlanTypeInterface {

  /**
   * {@inheritdoc}
   */
  public function getLabel() {
    return $this->pluginDefinition['label'];
  }

  /**
   * {@inheritdoc}
   */
  public function getWorkflowId() {
    return $this->pluginDefinition['workflow'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    return [];
  }

}
