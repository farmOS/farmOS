<?php

declare(strict_types=1);

namespace Drupal\farm_syntropic\Plugin\Asset\AssetType;

use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\farm_entity\Attribute\AssetType;
use Drupal\farm_entity\Plugin\Asset\AssetType\FarmAssetType;

/**
 * Provides the tree planting asset type.
 */
#[AssetType(
  id: 'tree_planting',
  label: new TranslatableMarkup('Tree Planting'),
)]
class TreePlanting extends FarmAssetType {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = parent::buildFieldDefinitions();
    return $fields;
  }

}
