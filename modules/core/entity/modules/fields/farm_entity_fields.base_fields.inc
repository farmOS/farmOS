<?php

/**
 * @file
 * Code for creating common farmOS entity base field definitions.
 */

/**
 * Define common asset base fields.
 */
function farm_entity_fields_asset_base_fields() {
  $field_info = [
    'data' => [
      'type' => 'string_long',
      'label' => t('Data'),
      'hidden' => TRUE,
    ],
    'file' => [
      'type' => 'file',
      'label' => t('Files'),
      'file_directory' => 'farm/asset/[date:custom:Y]-[date:custom:m]',
      'multiple' => TRUE,
      'weight' => [
        'form' => 90,
        'view' => 90,
      ],
    ],
    'image' => [
      'type' => 'image',
      'label' => t('Images'),
      'file_directory' => 'farm/asset/[date:custom:Y]-[date:custom:m]',
      'multiple' => TRUE,
      'weight' => [
        'form' => 89,
        'view' => 89,
      ],
    ],
    'notes' => [
      'type' => 'text_long',
      'label' => t('Notes'),
      'weight' => [
        'form' => 95,
        'view' => 10,
      ],
    ],
  ];
  /** @var \Drupal\Core\Field\BaseFieldDefinition[] $fields */
  $fields = [];
  foreach ($field_info as $name => $info) {
    $fields[$name] = \Drupal::service('farm_field.factory')->baseFieldDefinition($info);
  }

  return $fields;
}

/**
 * Define common log base fields.
 */
function farm_entity_fields_log_base_fields() {
  $field_info = [
    'data' => [
      'type' => 'string_long',
      'label' => t('Data'),
      'hidden' => TRUE,
    ],
    'file' => [
      'type' => 'file',
      'label' => t('Files'),
      'file_directory' => 'farm/log/[date:custom:Y]-[date:custom:m]',
      'multiple' => TRUE,
      'weight' => [
        'form' => 90,
        'view' => 90,
      ],
    ],
    'image' => [
      'type' => 'image',
      'label' => t('Images'),
      'file_directory' => 'farm/log/[date:custom:Y]-[date:custom:m]',
      'multiple' => TRUE,
      'weight' => [
        'form' => 89,
        'view' => 89,
      ],
    ],
    'notes' => [
      'type' => 'text_long',
      'label' => t('Notes'),
      'weight' => [
        'form' => 95,
        'view' => 10,
      ],
    ],
  ];
  $fields = [];
  foreach ($field_info as $name => $info) {
    $fields[$name] = \Drupal::service('farm_field.factory')->baseFieldDefinition($info);
  }
  return $fields;
}

/**
 * Define common plan base fields.
 */
function farm_entity_fields_plan_base_fields() {
  $field_info = [
    'data' => [
      'type' => 'string_long',
      'label' => t('Data'),
      'hidden' => TRUE,
    ],
    'file' => [
      'type' => 'file',
      'label' => t('Files'),
      'file_directory' => 'farm/plan/[date:custom:Y]-[date:custom:m]',
      'multiple' => TRUE,
      'weight' => [
        'form' => 90,
        'view' => 90,
      ],
    ],
    'image' => [
      'type' => 'image',
      'label' => t('Images'),
      'file_directory' => 'farm/plan/[date:custom:Y]-[date:custom:m]',
      'multiple' => TRUE,
      'weight' => [
        'form' => 89,
        'view' => 89,
      ],
    ],
    'notes' => [
      'type' => 'text_long',
      'label' => t('Notes'),
      'weight' => [
        'form' => 95,
        'view' => 10,
      ],
    ],
  ];
  $fields = [];
  foreach ($field_info as $name => $info) {
    $fields[$name] = \Drupal::service('farm_field.factory')->baseFieldDefinition($info);
  }
  return $fields;
}
