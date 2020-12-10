<?php

namespace Drupal\farm_equipment\Plugin\Asset\AssetType;

use Drupal\asset\Plugin\Asset\AssetType\AssetTypeBase;

/**
 * Provides the equipment asset type.
 *
 * @AssetType(
 *   id = "equipment",
 *   label = @Translation("Equipment"),
 * )
 */
class Equipment extends AssetTypeBase {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    return [];
  }

}
