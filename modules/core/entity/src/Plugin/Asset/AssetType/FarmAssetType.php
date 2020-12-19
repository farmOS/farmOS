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
    // @todo Make it possible to hide this from the entity edit form?
    $options = [
      'type' => 'geofield',
      'label' => $this->t('Current geometry'),
      'description' => $this->t('The assets current location geometry.'),
      'computed' => AssetGeometryItemList::class,
      'weight' => [
        'form' => 95,
        'view' => 95,
      ],
    ];
    $fields['current_geometry'] = \Drupal::service('farm_field.factory')->bundleFieldDefinition($options);
    return $fields;
  }

}
