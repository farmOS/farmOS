<?php

/**
 * @file
 * Feeds Tamper plugin for converting strings to booleans with a default value.
 */

$plugin = array(
  'form' => 'farm_import_boolean_default_form',
  'callback' => 'farm_import_boolean_default_callback',
  'name' => 'Convert to boolean (with default)',
  'multi' => 'direct',
  'category' => 'Text',
);

function farm_import_boolean_default_form($importer, $element_key, $settings) {
  $form = array();
  $form['default_value'] = array(
    '#type' => 'radios',
    '#title' => t('Default value'),
    '#options' => array(
      0 => 'False',
      1 => 'True',
    ),
    '#default_value' => !empty($settings['default_value']) ? 1 : 0,
  );
  return $form;
}

function farm_import_boolean_default_callback($result, $item_key, $element_key, &$field, $settings, $source) {

  // Get the value.
  $value = $field;

  // Get the default value (default to TRUE).
  $default_value = isset($settings['default_value']) ? $settings['default_value'] : TRUE;

  // Convert to lowercase.
  $value = drupal_strtolower($value);

  // Define allowed true/false values.
  $allowed_values = array(
    0 => array(
      'false',
      'f',
      'no',
      'n',
      '0',
    ),
    1 => array(
      'true',
      't',
      'yes',
      'y',
      '1',
    ),
  );

  // Iterate through allowed values to see if a match is found.
  foreach ($allowed_values as $boolean => $options) {
    foreach ($options as $option) {
      if ($value == $option) {
        $field = $boolean;
        return;
      }
    }
  }

  // If a match was not found, use the default value.
  $field = $default_value;
}
