<?php

/**
 * @file
 * Code for creating common farmOS entity base field definitions.
 */

/**
 * Define common asset base fields.
 */
function farm_field_asset_base_fields() {
  $field_info = [
    'data' => [
      'type' => 'string_long',
      'label' => t('Data'),
      'hidden' => TRUE,
    ],
    'flag' => [
      'type' => 'list_string',
      'label' => t('Flags'),
      'description' => t('Add flags to enable better sorting and filtering of records.'),
      'allowed_values_function' => 'farm_flag_field_allowed_values',
      'multiple' => TRUE,
      'weight' => [
        'form' => -75,
        'view' => -75,
      ],
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
    'id_tag' => [
      'type' => 'id_tag',
      'label' => t('ID tags'),
      'description' => t('List any identification tags that this asset has. Use the fields below to describe the type, location, and ID of each.'),
      'multiple' => TRUE,
      'weight' => [
        'form' => 50,
        'view' => 50,
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
        'view' => 0,
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
        'view' => 40,
      ],
      'third_party_settings' => [
        'entity_reference_validators' => [
          'circular_reference' => TRUE,
          'circular_reference_deep' => TRUE,
          'duplicate_reference' => TRUE,
        ],
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
 * Define common log base fields.
 */
function farm_field_log_base_fields() {
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
        'form' => 10,
        'view' => 80,
      ],
    ],
    'data' => [
      'type' => 'string_long',
      'label' => t('Data'),
      'hidden' => TRUE,
    ],
    'flag' => [
      'type' => 'list_string',
      'label' => t('Flags'),
      'description' => t('Add flags to enable better sorting and filtering of records.'),
      'allowed_values_function' => 'farm_flag_field_allowed_values',
      'multiple' => TRUE,
      'weight' => [
        'form' => -75,
        'view' => -75,
      ],
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
        'view' => 0,
      ],
    ],
    'owner' => [
      'type' => 'entity_reference',
      'label' => t('Assigned to'),
      'description' => t('Optionally assign this task to one or more people.'),
      'target_type' => 'user',
      'multiple' => TRUE,
      'weight' => [
        'form' => -70,
        'view' => -70,
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
function farm_field_plan_base_fields() {
  $field_info = [
    'data' => [
      'type' => 'string_long',
      'label' => t('Data'),
      'hidden' => TRUE,
    ],
    'flag' => [
      'type' => 'list_string',
      'label' => t('Flags'),
      'description' => t('Add flags to enable better sorting and filtering of records.'),
      'allowed_values_function' => 'farm_flag_field_allowed_values',
      'multiple' => TRUE,
      'weight' => [
        'form' => -75,
        'view' => -75,
      ],
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
        'view' => 0,
      ],
    ],
  ];
  $fields = [];
  foreach ($field_info as $name => $info) {
    $fields[$name] = \Drupal::service('farm_field.factory')->baseFieldDefinition($info);
  }
  return $fields;
}
