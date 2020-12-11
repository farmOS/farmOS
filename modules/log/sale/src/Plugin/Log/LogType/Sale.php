<?php

namespace Drupal\farm_sale\Plugin\Log\LogType;

use Drupal\farm_log\Plugin\Log\LogType\LogTypeBase;

/**
 * Provides the sale log type.
 *
 * @LogType(
 *   id = "sale",
 *   label = @Translation("Sale"),
 * )
 */
class Sale extends LogTypeBase {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = parent::buildFieldDefinitions();

    // Customer.
    $options = [
      'type' => 'string',
      'label' => 'Customer',
      'weight' => [
        'form' => 20,
        'view' => 20,
      ],
    ];
    $fields['customer'] = \Drupal::service('farm_field.factory')->bundleFieldDefinition($options);

    // Invoice number.
    $options = [
      'type' => 'string',
      'label' => 'Invoice number',
      'weight' => [
        'form' => 20,
        'view' => 20,
      ],
    ];
    $fields['invoice_number'] = \Drupal::service('farm_field.factory')->bundleFieldDefinition($options);

    return $fields;
  }

}
