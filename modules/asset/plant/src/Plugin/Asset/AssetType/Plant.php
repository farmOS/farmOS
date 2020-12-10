<?php

namespace Drupal\farm_plant\Plugin\Asset\AssetType;

use Drupal\asset\Plugin\Asset\AssetType\AssetTypeBase;

/**
 * Provides the plant asset type.
 *
 * @AssetType(
 *   id = "plant",
 *   label = @Translation("Plant"),
 * )
 */
class Plant extends AssetTypeBase {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    return [];
  }

}
