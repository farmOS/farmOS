<?php

namespace Drupal\farm_entity\Plugin\Plan\PlanType;

use Drupal\Core\Plugin\PluginBase;

/**
 * Provides the base plan type class.
 */
abstract class PlanTypeBase extends PluginBase implements PlanTypeInterface {

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
