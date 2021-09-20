<?php

namespace Drupal\farm_sensor\Plugin\Asset\AssetType;

use Drupal\data_stream\Entity\DataStream;
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
      'weight' => [
        'form' => 4,
        'view' => 4,
      ],
    ];
    $fields['data_stream'] = $this->farmFieldFactory->bundleFieldDefinition($options);

    // Private key field.
    $options = [
      'type' => 'string',
      'label' => $this->t('Private key'),
      'description' => $this->t('Private key for the sensor.'),
      'default_value_callback' => DataStream::class . '::createUniqueKey',
      'weight' => [
        'form' => 3,
      ],
      'hidden' => 'view',
    ];
    $fields['private_key'] = $this->farmFieldFactory->bundleFieldDefinition($options);

    // Public field.
    $options = [
      'type' => 'boolean',
      'label' => $this->t('Public'),
      'description' => $this->t('Whether or not data from this sensor can be read publicly without the private key.'),
      'default' => FALSE,
      'weight' => [
        'form' => 2,
        'view' => 2,
      ],
    ];
    $fields['public'] = $this->farmFieldFactory->bundleFieldDefinition($options);

    return $fields;
  }

}
