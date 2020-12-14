<?php

namespace Drupal\farm_equipment\Plugin\Asset\AssetType;

use Drupal\farm_entity\Plugin\Asset\AssetType\FarmAssetType;

/**
 * Provides the equipment asset type.
 *
 * @AssetType(
 *   id = "equipment",
 *   label = @Translation("Equipment"),
 * )
 */
class Equipment extends FarmAssetType {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = parent::buildFieldDefinitions();
    $field_info = [
      'manufacturer' => [
        'type' => 'string',
        'label' => $this->t('Manufacturer'),
        'weight' => [
          'form' => -20,
          'view' => -50,
        ],
      ],
      'model' => [
        'type' => 'string',
        'label' => $this->t('Model'),
        'weight' => [
          'form' => -15,
          'view' => -40,
        ],
      ],
      'serial_number' => [
        'type' => 'string',
        'label' => $this->t('Serial number'),
        'weight' => [
          'form' => -10,
          'view' => -30,
        ],
      ],
    ];
    foreach ($field_info as $name => $info) {
      $fields[$name] = $this->farmFieldFactory->bundleFieldDefinition($info);
    }
    return $fields;
  }

}
