<?php

namespace Drupal\farm_entity\Plugin\Asset\AssetType;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\farm_location\AssetGeometryItemList;

/**
 * Provides a farmOS asset type base class.
 */
class FarmAssetType extends AssetTypeBase {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = [];

    // Current geometry computed field.
    $options = [
      'type' => 'geofield',
      'label' => $this->t('Current geometry'),
      'computed' => AssetGeometryItemList::class,
      'hidden' => 'form',
      'weight' => [
        'view' => 95,
      ],
    ];
    $fields['current_geometry'] = $this->farmFieldFactory->bundleFieldDefinition($options);

    return $fields;
  }

}
