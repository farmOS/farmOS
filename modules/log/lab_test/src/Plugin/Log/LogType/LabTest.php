<?php

namespace Drupal\farm_lab_test\Plugin\Log\LogType;

use Drupal\farm_entity\Plugin\Log\LogType\FarmLogType;

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

    // Lab test type.
    $options = [
      'type' => 'list_string',
      'label' => $this->t('Test type'),
      'allowed_values_function' => 'farm_lab_test_type_field_allowed_values',
      'weight' => [
        'form' => -50,
        'view' => -50,
      ],
    ];
    $fields['lab_test_type'] = $this->farmFieldFactory->bundleFieldDefinition($options);

    // Lab.
    $options = [
      'type' => 'string',
      'label' => $this->t('Laboratory'),
      'description' => $this->t('What laboratory performed this test?'),
      'weight' => [
        'form' => -40,
        'view' => -40,
      ],
    ];
    $fields['lab'] = $this->farmFieldFactory->bundleFieldDefinition($options);

    return $fields;
  }

}
