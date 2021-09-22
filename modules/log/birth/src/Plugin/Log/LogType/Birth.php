<?php

namespace Drupal\farm_birth\Plugin\Log\LogType;

use Drupal\farm_entity\Plugin\Log\LogType\FarmLogType;

/**
 * Provides the birth log type.
 *
 * @LogType(
 *   id = "birth",
 *   label = @Translation("Birth"),
 * )
 */
class Birth extends FarmLogType {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = parent::buildFieldDefinitions();

    // Mother.
    $options = [
      'type' => 'entity_reference',
      'label' => $this->t('Mother'),
      'target_type' => 'asset',
      'target_bundle' => 'animal',
      'weight' => [
        'form' => 45,
        'view' => -15,
      ],
    ];
    $fields['mother'] = $this->farmFieldFactory->bundleFieldDefinition($options);

    // Veterinarian.
    $options = [
      'type' => 'string',
      'label' => $this->t('Veterinarian'),
      'description' => $this->t('If a veterinarian was involved, enter their name here.'),
      'weight' => [
        'form' => -40,
        'view' => -40,
      ],
    ];
    $fields['vet'] = $this->farmFieldFactory->bundleFieldDefinition($options);

    return $fields;
  }

}
