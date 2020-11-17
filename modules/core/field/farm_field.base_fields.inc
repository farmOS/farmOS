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
  return [];
}

/**
 * Define common log base fields.
 */
function farm_field_log_base_fields() {
  return [];
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
