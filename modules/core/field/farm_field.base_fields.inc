<?php

/**
 * @file
 * Code for creating common farmOS entity base field definitions.
 */

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Field\FieldException;
use Drupal\Core\Field\FieldStorageDefinitionInterface;

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
        'view' => 0,
      ],
    ],
  ];
  $fields = [];
  foreach ($field_info as $name => $info) {
    $fields[$name] = farm_field_base_field_definition($info);
  }
  return $fields;
}

/**
 * Define common log base fields.
 */
function farm_field_log_base_fields() {
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
        'form' => 0,
        'view' => 0,
      ],
    ],
  ];
  $fields = [];
  foreach ($field_info as $name => $info) {
    $fields[$name] = farm_field_base_field_definition($info);
  }
  return $fields;
}

/**
 * Helper function for generating a base field definition with farmOS opinions.
 *
 * @param array $options
 *   An array of options.
 *
 * @return \Drupal\Core\Field\BaseFieldDefinition
 *   Returns a base field definition.
 */
function farm_field_base_field_definition(array $options = []) {

  // Create a new base field definition.
  $field = BaseFieldDefinition::create($options['type']);

  // Set label.
  if (!empty($options['label'])) {
    $field->setLabel($options['label']);
  }

  // Set description.
  if (!empty($options['description'])) {
    $field->setDescription($options['description']);
  }

  // Make the field revisionable, unless told otherwise.
  if (empty($options['revisionable'])) {
    $field->setRevisionable(TRUE);
  }
  else {
    $field->setRevisionable(FALSE);
  }

  // Set cardinality, if specified.
  if (!empty($options['cardinality'])) {
    $field->setCardinality($options['cardinality']);
  }

  // Or, if `multiple` is set, set it to unlimited.
  elseif (!empty($options['multiple'])) {
    $field->setCardinality(FieldStorageDefinitionInterface::CARDINALITY_UNLIMITED);
  }

  // Otherwise, set cardinality to 1.
  else {
    $field->setCardinality(1);
  }

  // Only make the field translatable if specified.
  if (empty($options['translatable'])) {
    $field->setTranslatable(FALSE);
  }
  else {
    $field->setTranslatable(TRUE);
  }

  // Delegate to per-type helper functions to fill in more details.
  switch ($options['type']) {
    case 'entity_reference':
      farm_field_base_field_definition_entity_reference($field, $options);
      break;
    case 'string':
    case 'string_long':
      farm_field_base_field_definition_string($field, $options);
      break;
    case 'list_string':
      farm_field_base_field_definition_list_string($field, $options);
      break;
    case 'text_long':
      farm_field_base_field_definition_text($field, $options);
      break;
    case 'file':
    case 'image':
      farm_field_base_field_definition_file($field, $options);
      break;
    case 'geofield':
      farm_field_base_field_definition_geofield($field, $options);
      break;
    default:
      throw new FieldException('Unsupported field type.');
  }

  // Hide the field in form and view displays, if specified.
  if (!empty($options['hidden'])) {
    $display_options = [
      'region' => 'hidden',
    ];
    $field->setDisplayOptions('form', $display_options);
    $field->setDisplayOptions('view', $display_options);
  }

  // Make the form and view displays configurable.
  $field->setDisplayConfigurable('form', TRUE);
  $field->setDisplayConfigurable('view', TRUE);

  return $field;
}

/**
 * Entity reference base field modifier.
 *
 * @param \Drupal\Core\Field\BaseFieldDefinition &$field
 *   A base field definition object.
 * @param array $options
 *   An array of options.
 */
function farm_field_base_field_definition_entity_reference(BaseFieldDefinition &$field, array $options = []) {

  // If a target type is not specified, throw an exception.
  if (empty($options['target_type'])) {
    throw new FieldException('No target_type was specified.');
  }

  // Set the target type.
  $field->setSetting('target_type', $options['target_type']);

  // Build additional settings based on the target type.
  switch ($options['target_type']) {

    // Asset reference.
    case 'asset':
      $handler = 'views';
      $handler_settings = [
        'view' => [
          'view_name' => 'farm_asset_reference',
          'display_name' => 'entity_reference',
        ],
      ];
      $form_display_options = [
        'type' => 'entity_reference_autocomplete',
        'weight' => $options['weight']['form'] ?? 0,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'match_limit' => '10',
          'size' => '60',
          'placeholder' => '',
        ],
      ];
      $view_display_options = [
        'label' => 'inline',
        'type' => 'entity_reference_label',
        'weight' => $options['weight']['view'] ?? 0,
        'settings' => [
          'link' => TRUE,
        ],
      ];
      break;

    // Term reference.
    case 'taxonomy_term':
      $handler = 'default:taxonomy_term';
      $handler_settings = [
        'target_bundles' => [
          'log_category' => 'log_category',
        ],
        'sort' => [
          'field' => 'name',
          'direction' => 'asc',
        ],
        'auto_create' => FALSE,
        'auto_create_bundle' => '',
      ];
      $form_display_options = [
        'type' => 'entity_reference_autocomplete',
        'weight' => $options['weight']['form'] ?? 0,
      ];
      $view_display_options = [
        'label' => 'inline',
        'type' => 'entity_reference_label',
        'weight' => $options['weight']['view'] ?? 0,
        'settings' => [
          'link' => FALSE,
        ],
      ];
      break;

    // User reference.
    case 'user':
      $handler = 'default:user';
      $handler_settings = [
        'include_anonymous' => FALSE,
        'filter' => [
          'type' => '_none',
        ],
        'target_bundles' => NULL,
        'sort' => [
          'field' => '_none',
        ],
        'auto_create' => FALSE,
      ];
      $form_display_options = [
        'type' => 'options_select',
        'weight' => $options['weight']['form'] ?? 0,
      ];
      $view_display_options = [
        'label' => 'inline',
        'type' => 'entity_reference_label',
        'weight' => $options['weight']['view'] ?? 0,
        'settings' => [
          'link' => TRUE,
        ],
      ];
      break;

    // Otherwise, throw an exception.
    default:
      throw new FieldException('Unsupported target_type.');
  }

  // Set the handler and handler settings.
  $field->setSetting('handler', $handler);
  $field->setSetting('handler_settings', $handler_settings);

  // Set form and view display options.
  $field->setDisplayOptions('form', $form_display_options);
  $field->setDisplayOptions('view', $view_display_options);
}

/**
 * String base field modifier.
 *
 * @param \Drupal\Core\Field\BaseFieldDefinition &$field
 *   A base field definition object.
 * @param array $options
 *   An array of options.
 */
function farm_field_base_field_definition_string(BaseFieldDefinition $field, array $options = []) {

  // Build form and view display settings.
  $field->setDisplayOptions('form', [
    'type' => $options['type'],
    'weight' => $options['weight']['form'] ?? 0,
  ]);
  $field->setDisplayOptions('view', [
    'label' => 'inline',
    'type' => $options['type'],
    'weight' => $options['weight']['view'] ?? 0,
  ]);
}

/**
 * List string base field modifier.
 *
 * @param \Drupal\Core\Field\BaseFieldDefinition &$field
 *   A base field definition object.
 * @param array $options
 *   An array of options.
 */
function farm_field_base_field_definition_list_string(BaseFieldDefinition &$field, array $options = []) {

  // Set the allowed values, if specified.
  if (!empty($options['allowed_values'])) {
    $field->setSetting('allowed_values', $options['allowed_values']);
  }

  // Set the allowed values function, if specified.
  if (!empty($options['allowed_values_function'])) {
    $field->setSetting('allowed_values_function', $options['allowed_values_function']);
  }

  // Build form and view display settings.
  $field->setDisplayOptions('form', [
    'type' => 'options_buttons',
    'weight' => $options['weight']['form'] ?? 0,
  ]);
  $field->setDisplayOptions('view', [
    'label' => 'inline',
    'type' => 'list_default',
    'weight' => $options['weight']['view'] ?? 0,
  ]);
}

/**
 * Text base field modifier.
 *
 * @param \Drupal\Core\Field\BaseFieldDefinition &$field
 *   A base field definition object.
 * @param array $options
 *   An array of options.
 */
function farm_field_base_field_definition_text(BaseFieldDefinition $field, array $options = []) {

  // Build form and view display settings.
  $field->setDisplayOptions('form', [
    'type' => 'text_textarea',
    'settings' => [
      'rows' => '5',
      'placeholder' => '',
    ],
    'weight' => $options['weight']['form'] ?? 0,
  ]);
  $field->setDisplayOptions('view', [
    'label' => 'inline',
    'type' => 'text_default',
    'weight' => $options['weight']['view'] ?? 0,
  ]);
}

/**
 * File base field modifier.
 *
 * @param \Drupal\Core\Field\BaseFieldDefinition &$field
 *   A base field definition object.
 * @param array $options
 *   An array of options.
 */
function farm_field_base_field_definition_file(BaseFieldDefinition &$field, array $options = []) {

  // Determine the upload directory.
  $file_directory = 'farm/[date:custom:Y]-[date:custom:m]';
  if (!empty($options['file_directory'])) {
    $file_directory = $options['file_directory'];
  }

  // Set field settings.
  $settings = [
    'file_directory' => $file_directory,
    'max_filesize' => '',
    'handler' => 'file',
    'handler_settings' => [],
  ];
  switch ($options['type']) {

    case 'file':
      $settings['file_extensions'] = 'csv doc docx gz kml kmz logz mp3 odp ods odt ogg pdf ppt pptx tar txt wav xls xlsx zip';
      $settings['description_field'] = TRUE;
      break;

    case 'image':
      $settings['file_extensions'] = 'png gif jpg jpeg';
      $settings['max_resolution'] = '';
      $settings['min_resolution'] = '';
      $settings['alt_field'] = FALSE;
      $settings['alt_field_required'] = FALSE;
      $settings['title_field'] = FALSE;
      $settings['title_field_required'] = FALSE;
      $settings['default_image'] = [
        'uuid' => '',
        'alt' => '',
        'title' => '',
        'width' => NULL,
        'height' => NULL,
      ];
      break;

  }
  $field->setSettings($settings);

  // Build form and view display settings.
  switch ($options['type']) {
    case 'file':
      $form_display_options = [
        'type' => 'file_generic',
        'settings' => [
          'progress_indicator' => 'throbber',
        ],
        'weight' => $options['weight']['form'] ?? 0,
      ];
      $view_display_options = [
        'type' => 'file_table',
        'label' => 'visually_hidden',
        'settings' => [
          'use_description_as_link_text' => TRUE,
        ],
        'weight' => $options['weight']['view'] ?? 0,
      ];
      break;
    case 'image':
      $form_display_options = [
        'type' => 'image_image',
        'settings' => [
          'preview_image_style' => 'medium',
          'progress_indicator' => 'throbber',
        ],
        'weight' => $options['weight']['form'] ?? 0,
      ];
      $view_display_options = [
        'type' => 'image',
        'label' => 'visually_hidden',
        'settings' => [
          'image_style' => 'large',
          'image_link' => 'file',
        ],
        'weight' => $options['weight']['view'] ?? 0,
      ];
      break;
  }
  $field->setDisplayOptions('form', $form_display_options);
  $field->setDisplayOptions('view', $view_display_options);
}

/**
 * Geofield base field modifier.
 *
 * @param \Drupal\Core\Field\BaseFieldDefinition &$field
 *   A base field definition object.
 * @param array $options
 *   An array of options.
 */
function farm_field_base_field_definition_geofield(BaseFieldDefinition &$field, array $options = []) {

  // Set the geofield backend.
  $field->setSetting('backend', 'geofield_backend_default');

  // Build form and view display settings.
  $field->setDisplayOptions('form', [
    'type' => 'geofield_default',
    'weight' => $options['weight']['form'] ?? 0,
  ]);
  $field->setDisplayOptions('view', [
    'label' => 'visually_hidden',
    'type' => 'geofield_default',
    'settings' => [
      'output_format' => 'wkt',
      'output_escape' => TRUE,
    ],
    'weight' => $options['weight']['view'] ?? 0,
  ]);
}
