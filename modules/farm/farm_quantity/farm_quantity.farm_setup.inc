<?php

/**
 * @file
 * Farm Setup hooks implemented by the Farm Quantity module.
 */

/**
 * Implements hook_farm_setup_forms().
 */
function farm_quantity_farm_setup_forms() {
  return array(
    'quantity' => array(
      'label' => t('Quantity'),
      'form' => 'farm_quantity_settings_form',
      'include_file' => 'farm_quantity.farm_setup',
      'weight' => 1,
    ),
  );
}

/**
 * Quantity settings form.
 */
function farm_quantity_settings_form($form, &$form_state) {
  $form = array();

  // Metric or US/Imperial.
  $form['farm_quantity_unit_system'] = array(
    '#type' => 'radios',
    '#title' => t('System of measurement'),
    '#description' => t('Select the system of measurement you would like to use in farmOS. Changing this setting after data has been entered is not recommended.'),
    '#options' => array(
      'metric' => t('Metric'),
      'us' => t('US/Imperial'),
    ),
    '#default_value' => farm_quantity_system_of_measurement(),
  );

  return $form;
}

/**
 * Quantity settings form submit handler.
 */
function farm_quantity_settings_form_submit($form, &$form_state) {
  $value = $form_state['values']['farm_quantity_unit_system'];

  variable_set('farm_quantity_unit_system', $value);
  drupal_set_message('Quantity settings saved.');
}
