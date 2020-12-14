<?php

namespace Drupal\farm_sale\Plugin\Log\LogType;

use Drupal\farm_entity\Plugin\Log\LogType\FarmLogType;

/**
 * Provides the sale log type.
 *
 * @LogType(
 *   id = "sale",
 *   label = @Translation("Sale"),
 * )
 */
class Sale extends FarmLogType {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = parent::buildFieldDefinitions();

    // Customer.
    $options = [
      'type' => 'string',
      'label' => $this->t('Customer'),
      'weight' => [
        'form' => 20,
        'view' => 20,
      ],
    ];
    $fields['customer'] = $this->farmFieldFactory->bundleFieldDefinition($options);

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

    return $fields;
  }

}
