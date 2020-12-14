<?php

namespace Drupal\farm_seeding\Plugin\Log\LogType;

use Drupal\farm_entity\Plugin\Log\LogType\FarmLogType;

/**
 * Provides the seeding log type.
 *
 * @LogType(
 *   id = "seeding",
 *   label = @Translation("Seeding"),
 * )
 */
class Seeding extends FarmLogType {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = parent::buildFieldDefinitions();

    // Lot number.
    $options = [
      'type' => 'string',
      'label' => $this->t('Lot number'),
      'description' => $this->t('If this harvest is part of a batch or lot, enter the lot number here.'),
      'weight' => [
        'form' => 20,
        'view' => 20,
      ],
    ];
    $fields['lot_number'] = \Drupal::service('farm_field.factory')->bundleFieldDefinition($options);

    // Purchase date.
    $options = [
      'type' => 'timestamp',
      'label' => $this->t('Purchase date'),
      'description' => $this->t('When was this input purchased (if applicable)?'),
      'weight' => [
        'form' => -35,
        'view' => -35,
      ],
    ];
    $fields['purchase_date'] = \Drupal::service('farm_field.factory')->bundleFieldDefinition($options);

    // Source.
    $options = [
      'type' => 'string',
      'label' => $this->t('Source'),
      'description' => $this->t('Where was this input obtained? Who manufactured it?'),
      'weight' => [
        'form' => -40,
        'view' => -40,
      ],
    ];
    $fields['source'] = \Drupal::service('farm_field.factory')->bundleFieldDefinition($options);

    return $fields;
  }

}
