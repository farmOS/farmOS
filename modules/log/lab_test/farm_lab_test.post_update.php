<?php

/**
 * @file
 * Post update hooks for the farm_lab_test module.
 */

/**
 * Add "Date received" and "Date processed" fields to lab test logs.
 */
function farm_lab_test_post_update_add_received_processed_date_fields(&$sandbox) {

  // Date received.
  $options = [
    'type' => 'timestamp',
    'label' => t('Date received'),
    'description' => t('The date when the sample was received by the lab.'),
    'weight' => [
      'form' => -35,
      'view' => -35,
    ],
  ];
  $field_definition = \Drupal::service('farm_field.factory')->bundleFieldDefinition($options);
  \Drupal::entityDefinitionUpdateManager()->installFieldStorageDefinition('lab_received_date', 'log', 'farm_lab_test', $field_definition);

  // Date processed.
  $options = [
    'type' => 'timestamp',
    'label' => t('Date processed'),
    'description' => t('The date when the sample was processed by the lab.'),
    'weight' => [
      'form' => -34,
      'view' => -34,
    ],
  ];
  $field_definition = \Drupal::service('farm_field.factory')->bundleFieldDefinition($options);
  \Drupal::entityDefinitionUpdateManager()->installFieldStorageDefinition('lab_processed_date', 'log', 'farm_lab_test', $field_definition);
}
