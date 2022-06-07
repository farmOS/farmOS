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
      'type' => 'entity_reference',
      'label' => $this->t('Laboratory'),
      'description' => $this->t('What laboratory performed this test?'),
      'target_type' => 'taxonomy_term',
      'target_bundle' => 'lab',
      'auto_create' => TRUE,
      'weight' => [
        'form' => -40,
        'view' => -40,
      ],
    ];
    $fields['lab'] = $this->farmFieldFactory->bundleFieldDefinition($options);

    // Date received.
    $options = [
      'type' => 'timestamp',
      'label' => $this->t('Date received'),
      'description' => $this->t('The date when the sample was received by the lab.'),
      'weight' => [
        'form' => -35,
        'view' => -35,
      ],
    ];
    $fields['lab_received_date'] = $this->farmFieldFactory->bundleFieldDefinition($options);

    // Date processed.
    $options = [
      'type' => 'timestamp',
      'label' => $this->t('Date processed'),
      'description' => $this->t('The date when the sample was processed by the lab.'),
      'weight' => [
        'form' => -34,
        'view' => -34,
      ],
    ];
    $fields['lab_processed_date'] = $this->farmFieldFactory->bundleFieldDefinition($options);

    return $fields;
  }

}
