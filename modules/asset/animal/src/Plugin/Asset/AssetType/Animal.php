<?php

namespace Drupal\farm_animal\Plugin\Asset\AssetType;

use Drupal\asset\Plugin\Asset\AssetType\AssetTypeBase;

/**
 * Provides the animal asset type.
 *
 * @AssetType(
 *   id = "animal",
 *   label = @Translation("Animal"),
 * )
 */
class Animal extends AssetTypeBase {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    return [];
  }

}
