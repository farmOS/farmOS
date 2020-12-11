<?php

namespace Drupal\farm_log\Plugin\Log\LogType;

use Drupal\Core\Plugin\PluginBase;

/**
 * Provides the base log type class.
 */
abstract class LogTypeBase extends PluginBase implements LogTypeInterface {

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
