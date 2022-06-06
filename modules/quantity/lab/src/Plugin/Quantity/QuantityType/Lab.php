<?php

namespace Drupal\farm_quantity_lab\Plugin\Quantity\QuantityType;

use Drupal\farm_entity\Plugin\Quantity\QuantityType\FarmQuantityType;

/**
 * Provides the lab quantity type.
 *
 * @QuantityType(
 *   id = "lab",
 *   label = @Translation("Lab measurement"),
 * )
 */
class Lab extends FarmQuantityType {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {

    // Inherit default quantity fields.
    $fields = parent::buildFieldDefinitions();

    // Lab method.
    $options = [
      'type' => 'string',
      'label' => $this->t('Lab method'),
      'description' => $this->t('What lab method was used to obtain this measurement?'),
      'weight' => [
        'form' => 25,
        'view' => 25,
      ],
    ];
    $fields['lab_method'] = $this->farmFieldFactory->bundleFieldDefinition($options);

    return $fields;
  }

}
