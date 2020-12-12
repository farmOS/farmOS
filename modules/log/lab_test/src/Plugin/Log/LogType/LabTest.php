<?php

namespace Drupal\farm_lab_test\Plugin\Log\LogType;

use Drupal\farm_field\Plugin\Log\LogType\FarmLogType;

/**
 * Provides the lab test log type.
 *
 * @LogType(
 *   id = "lab_test",
 *   label = @Translation("LabTest"),
 * )
 */
class LabTest extends FarmLogType {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = parent::buildFieldDefinitions();

    // Lab.
    $options = [
      'type' => 'string',
      'label' => 'Laboratory',
      'description' => 'What laboratory performed this test?',
      'weight' => [
        'form' => -40,
        'view' => -40,
      ],
    ];
    $fields['lab'] = \Drupal::service('farm_field.factory')->bundleFieldDefinition($options);

    // Lab test type.
    $options = [
      'type' => 'list_string',
      'label' => 'Test type',
      'allowed_values_function' => 'farm_lab_test_type_field_allowed_values',
      'weight' => [
        'form' => -50,
        'view' => -50,
      ],
    ];
    $fields['lab_test_type'] = \Drupal::service('farm_field.factory')->bundleFieldDefinition($options);

    return $fields;
  }

}
