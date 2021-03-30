<?php

namespace Drupal\farm_sensor\Plugin\Asset\AssetType;

use Drupal\farm_entity\Plugin\Asset\AssetType\FarmAssetType;

/**
 * Provides the sensor asset type.
 *
 * @AssetType(
 *   id = "sensor",
 *   label = @Translation("Sensor"),
 * )
 */
class Sensor extends FarmAssetType {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = parent::buildFieldDefinitions();

    // Data stream field.
    $options = [
      'type' => 'entity_reference',
      'label' => $this->t('Data stream'),
      'description' => $this->t('Data streams provided by this sensor.'),
      'target_type' => 'data_stream',
      'multiple' => TRUE,
    ];
    $fields['data_stream'] = $this->farmFieldFactory->bundleFieldDefinition($options);

    return $fields;
  }

}
