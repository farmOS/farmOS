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
