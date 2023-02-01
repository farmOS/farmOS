<?php

namespace Drupal\farm_structure\Plugin\Asset\AssetType;

use Drupal\farm_entity\Plugin\Asset\AssetType\FarmAssetType;

/**
 * Provides the structure asset type.
 *
 * @AssetType(
 *   id = "structure",
 *   label = @Translation("Structure"),
 * )
 */
class Structure extends FarmAssetType {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = [];

    // Structure type field.
    $options = [
      'type' => 'list_string',
      'label' => $this->t('Structure type'),
      'allowed_values_function' => 'farm_structure_type_field_allowed_values',
      'required' => TRUE,
      'weight' => [
        'form' => -90,
        'view' => -50,
      ],
    ];
    $fields['structure_type'] = $this->farmFieldFactory->bundleFieldDefinition($options);

    return $fields;
  }

}
