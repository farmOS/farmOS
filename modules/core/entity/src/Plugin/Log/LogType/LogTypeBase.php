<?php

namespace Drupal\farm_entity\Plugin\Log\LogType;

use Drupal\farm_entity\FarmEntityTypeBase;

/**
 * Provides the base log type class.
 */
abstract class LogTypeBase extends FarmEntityTypeBase implements LogTypeInterface {

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
