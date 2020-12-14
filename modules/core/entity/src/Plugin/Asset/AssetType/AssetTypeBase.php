<?php

namespace Drupal\farm_entity\Plugin\Asset\AssetType;

use Drupal\farm_entity\FarmEntityTypeBase;

/**
 * Provides the base asset type class.
 */
abstract class AssetTypeBase extends FarmEntityTypeBase implements AssetTypeInterface {

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
