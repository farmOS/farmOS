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
    ],
    'file' => [
      'type' => 'file',
      'label' => t('Files'),
      'file_directory' => 'farm/asset/[date:custom:Y]-[date:custom:m]',
      'multiple' => TRUE,
    ],
    'image' => [
      'type' => 'image',
      'label' => t('Images'),
      'file_directory' => 'farm/asset/[date:custom:Y]-[date:custom:m]',
      'multiple' => TRUE,
    ],
    'notes' => [
      'type' => 'text_long',
      'label' => t('Notes'),
    ],
    'parent' => [
      'type' => 'entity_reference',
      'label' => t('Parents'),
      'description' => t('If this asset descends from other assets, they can referenced here to create a lineage relationship.'),
      'target_type' => 'asset',
      'multiple' => TRUE,
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
    'asset' => [
      'type' => 'entity_reference',
      'label' => t('Assets'),
      'description' => t('What assets do this log pertain to?'),
      'target_type' => 'asset',
      'multiple' => TRUE,
    ],
    'category' => [
      'type' => 'entity_reference',
      'label' => t('Log category'),
      'description' => t('Use this to organize your logs into categories for easier searching and filtering later.'),
      'target_type' => 'taxonomy_term',
      'multiple' => TRUE,
    ],
    'data' => [
      'type' => 'string_long',
      'label' => t('Data'),
    ],
    'file' => [
      'type' => 'file',
      'label' => t('Files'),
      'file_directory' => 'farm/log/[date:custom:Y]-[date:custom:m]',
      'multiple' => TRUE,
    ],
    'image' => [
      'type' => 'image',
      'label' => t('Images'),
      'file_directory' => 'farm/log/[date:custom:Y]-[date:custom:m]',
      'multiple' => TRUE,
    ],
    'notes' => [
      'type' => 'text_long',
      'label' => t('Notes'),
    ],
    'owner' => [
      'type' => 'entity_reference',
      'label' => t('Assigned to'),
      'description' => t('Optionally assign this task to one or more people.'),
      'target_type' => 'user',
      'multiple' => TRUE,
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
    case 'list_string':
      farm_field_base_field_definition_list_string($field, $options);
      break;
    case 'file':
    case 'image':
      farm_field_base_field_definition_file($field, $options);
      break;
    case 'string':
    case 'string_long':
    case 'text_long':
      break;
    default:
      throw new FieldException('Unsupported field type.');
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
      break;

    // Otherwise, throw an exception.
    default:
      throw new FieldException('Unsupported target_type.');
  }

  // Set the handler and handler settings.
  $field->setSetting('handler', $handler);
  $field->setSetting('handler_settings', $handler_settings);
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
}
