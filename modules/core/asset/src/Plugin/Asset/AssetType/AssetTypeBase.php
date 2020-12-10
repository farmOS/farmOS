<?php

namespace Drupal\asset\Plugin\Asset\AssetType;

use Drupal\Core\Plugin\PluginBase;

/**
 * Provides the base asset type class.
 */
abstract class AssetTypeBase extends PluginBase implements AssetTypeInterface {

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
