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
        'form' => 0,
        'view' => 10,
      ],
    ],
    'parent' => [
      'type' => 'entity_reference',
      'label' => t('Parents'),
      'description' => t('If this asset descends from other assets, they can referenced here to create a lineage relationship.'),
      'target_type' => 'asset',
      'multiple' => TRUE,
      'weight' => [
        'form' => 40,
        'view' => 0,
      ],
    ],
  ];
  /** @var \Drupal\Core\Field\BaseFieldDefinition[] $fields */
  $fields = [];
  foreach ($field_info as $name => $info) {
    $fields[$name] = \Drupal::service('farm_field.factory')->baseFieldDefinition($info);
  }

  // Add entity_reference_validators constraints to parent field.
  // See entity_reference_validators_entity_base_field_info_alter.
  $fields['parent']->addConstraint('CircularReference', ['deep' => TRUE]);
  $fields['parent']->addConstraint('DuplicateReference');

  return $fields;
}

/**
 * Define common log base fields.
 */
function farm_entity_fields_log_base_fields() {
  $field_info = [
    'asset' => [
      'type' => 'entity_reference',
      'label' => t('Assets'),
      'description' => t('What assets do this log pertain to?'),
      'target_type' => 'asset',
      'multiple' => TRUE,
      'weight' => [
        'form' => 50,
        'view' => -10,
      ],
    ],
    'category' => [
      'type' => 'entity_reference',
      'label' => t('Log category'),
      'description' => t('Use this to organize your logs into categories for easier searching and filtering later.'),
      'target_type' => 'taxonomy_term',
      'target_bundle' => 'log_category',
      'multiple' => TRUE,
      'weight' => [
        'view' => 80,
      ],
      'form_display_options' => [
        'type' => 'options_select',
        'weight' => 10,
      ],
    ],
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
        'form' => 0,
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
        'form' => 0,
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
