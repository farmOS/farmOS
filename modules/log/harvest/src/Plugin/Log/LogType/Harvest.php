<?php

namespace Drupal\farm_harvest\Plugin\Log\LogType;

use Drupal\farm_entity\Plugin\Log\LogType\FarmLogType;

/**
 * Provides the harvest log type.
 *
 * @LogType(
 *   id = "harvest",
 *   label = @Translation("Harvest"),
 * )
 */
class Harvest extends FarmLogType {

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
    $fields['lot_number'] = $this->farmFieldFactory->bundleFieldDefinition($options);

    return $fields;
  }

}
