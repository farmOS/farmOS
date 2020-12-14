<?php

namespace Drupal\farm_purchase\Plugin\Log\LogType;

use Drupal\farm_entity\Plugin\Log\LogType\FarmLogType;

/**
 * Provides the purchase log type.
 *
 * @LogType(
 *   id = "purchase",
 *   label = @Translation("Purchase"),
 * )
 */
class Purchase extends FarmLogType {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = parent::buildFieldDefinitions();

    // Invoice number.
    $options = [
      'type' => 'string',
      'label' => $this->t('Invoice number'),
      'weight' => [
        'form' => 20,
        'view' => 20,
      ],
    ];
    $fields['invoice_number'] = $this->farmFieldFactory->bundleFieldDefinition($options);

    // Seller.
    $options = [
      'type' => 'string',
      'label' => $this->t('Seller'),
      'weight' => [
        'form' => 20,
        'view' => 20,
      ],
    ];
    $fields['seller'] = $this->farmFieldFactory->bundleFieldDefinition($options);

    return $fields;
  }

}
