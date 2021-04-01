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
      'description' => $this->t('If the seed is part of a batch or lot, enter the lot number here.'),
      'weight' => [
        'form' => 20,
        'view' => 20,
      ],
    ];
    $fields['lot_number'] = $this->farmFieldFactory->bundleFieldDefinition($options);

    // Purchase date.
    $options = [
      'type' => 'timestamp',
      'label' => $this->t('Purchase date'),
      'description' => $this->t('When was the seed purchased (if applicable)?'),
      'weight' => [
        'form' => -35,
        'view' => -35,
      ],
    ];
    $fields['purchase_date'] = $this->farmFieldFactory->bundleFieldDefinition($options);

    // Source.
    $options = [
      'type' => 'string',
      'label' => $this->t('Source'),
      'description' => $this->t('Where was the seed obtained? Who supplied it?'),
      'weight' => [
        'form' => -40,
        'view' => -40,
      ],
    ];
    $fields['source'] = $this->farmFieldFactory->bundleFieldDefinition($options);

    return $fields;
  }

}
