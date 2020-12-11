<?php

namespace Drupal\farm_input\Plugin\Log\LogType;

use Drupal\farm_log\Plugin\Log\LogType\LogTypeBase;

/**
 * Provides the input log type.
 *
 * @LogType(
 *   id = "input",
 *   label = @Translation("Input"),
 * )
 */
class Input extends LogTypeBase {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = parent::buildFieldDefinitions();

    // Lot number.
    $options = [
      'type' => 'string',
      'label' => 'Lot number',
      'description' => 'If this harvest is part of a batch or lot, enter the lot number here.',
      'weight' => [
        'form' => -45,
        'view' => -45,
      ],
    ];
    $fields['lot_number'] = \Drupal::service('farm_field.factory')->bundleFieldDefinition($options);

    // Material.
    $options = [
      'type' => 'entity_reference',
      'label' => 'Material',
      'description' => 'What materials are being applied?',
      'target_type' => 'taxonomy_term',
      'target_bundle' => 'material',
      'multiple' => TRUE,
      'weight' => [
        'form' => -50,
        'view' => -50,
      ],
    ];
    $fields['material'] = \Drupal::service('farm_field.factory')->bundleFieldDefinition($options);

    // Method.
    $options = [
      'type' => 'string',
      'label' => 'Method',
      'description' => 'How was this input applied?',
      'weight' => [
        'form' => -30,
        'view' => -30,
      ],
    ];
    $fields['method'] = \Drupal::service('farm_field.factory')->bundleFieldDefinition($options);

    // Purchase date.
    $options = [
      'type' => 'timestamp',
      'label' => 'Purchase date',
      'description' => 'When was this input purchased (if applicable)?',
      'weight' => [
        'form' => -35,
        'view' => -35,
      ],
    ];
    $fields['purchase_date'] = \Drupal::service('farm_field.factory')->bundleFieldDefinition($options);

    // Source.
    $options = [
      'type' => 'string',
      'label' => 'Source',
      'description' => 'Where was this input obtained? Who manufactured it?',
      'weight' => [
        'form' => -40,
        'view' => -40,
      ],
    ];
    $fields['source'] = \Drupal::service('farm_field.factory')->bundleFieldDefinition($options);

    return $fields;
  }

}
