<?php

namespace Drupal\farm_land\Plugin\Asset\AssetType;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\farm_entity\Plugin\Asset\AssetType\FarmAssetType;

/**
 * Provides the land asset type.
 *
 * @AssetType(
 *   id = "land",
 *   label = @Translation("Land"),
 * )
 */
class Land extends FarmAssetType {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = [];

    // Land type field.
    $options = [
      'type' => 'list_string',
      'label' => $this->t('Land type'),
      'allowed_values_function' => 'farm_land_type_field_allowed_values',
      'weight' => [
        'form' => -50,
        'view' => -50,
      ],
    ];
    $fields['land_type'] = $this->farmFieldFactory->bundleFieldDefinition($options);

    return $fields;
  }

}
