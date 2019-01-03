<?php

/**
 * @file
 * Farm soil disturbance quick form.
 */

/**
 * Soil disturbance quick form.
 */
function farm_soil_disturbance_form($form, &$form_state) {

  // Wrapper fieldset.
  $form['disturbance'] = array(
    '#type' => 'fieldset',
    '#title' => t('Record a soil disturbance'),
    '#description' => t('Use this form to record disturbances to your soil. This can include tillage, compaction, or any other action that causes soil breakdown. A new activity log will be created.'),
    '#tree' => TRUE,
  );

  // Date select (default to now).
  $form['disturbance']['timestamp'] = array(
    '#type' => 'date_select',
    '#title' => t('Date'),
    '#date_format' => 'M j Y H:i',
    '#date_type' => DATE_FORMAT_UNIX,
    '#date_year_range' => '-10:+3',
    '#default_value' => REQUEST_TIME,
    '#required' => TRUE,
  );

  // Area information fieldset.
  $form['disturbance']['area'] = array(
    '#type' => 'fieldset',
    '#title' => t('Area information'),
  );

  // Area reference.
  $form['disturbance']['area']['name'] = array(
    '#type' => 'textfield',
    '#title' => t('Area name'),
    '#description' => t('Enter the name of the area that was disturbed. A list of existing area options will appear as you type. If the area does not exist, a new one will be created.'),
    '#autocomplete_path' => 'taxonomy/autocomplete/field_farm_area',
    '#required' => TRUE,
  );

  // Notes fieldset.
  $form['disturbance']['notes'] = array(
    '#type' => 'fieldset',
    '#title' => t('Notes'),
  );

  // Field condition.
  $form['disturbance']['notes']['condition'] = array(
    '#type' => 'textfield',
    '#title' => t('Field condition'),
    '#description' => t('Briefly describe the field conditions of this area when the action was taken.'),
  );

  // Crops in field.
  $form['disturbance']['notes']['crops'] = array(
    '#type' => 'textfield',
    '#title' => t('Crops in field'),
    '#description' => t('List the crops that are in this area, or will be in this area during/after this action.'),
  );

  // Other notes.
  $form['disturbance']['notes']['other'] = array(
    '#type' => 'text_format',
    '#title' => t('Other notes'),
    '#description' => t('Include any other notes that are relevant to this soil disturbance for future reference.'),
    '#format' => 'farm_format',
  );

  // Submit button.
  $form['disturbance']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Create log'),
  );

  // Return the form.
  return $form;
}

/**
 * Soil disturbance quick form validate.
 */
function farm_soil_disturbance_form_validate($form, &$form_state) {

}

/**
 * Soil disturbance quick form submit.
 */
function farm_soil_disturbance_form_submit($form, &$form_state) {

  // Alias $form_state['values']['disturbance'] for easier use.
  $form_values = array();
  if (!empty($form_state['values']['disturbance'])) {
    $form_values = &$form_state['values']['disturbance'];
  }

  // Get the disturbance timestamp.
  $timestamp = strtotime($form_values['timestamp']);

  // Parse the area name, create new one if it doesn't exist.
  $area_name = $form_values['area']['name'];
  $areas = farm_area_parse_names($area_name, TRUE);

  // If no areas were found/created, bail with an error.
  if (empty($areas)) {
    drupal_set_message(t('An error occurred while creating/loading areas.'), 'error');
    return;
  }

  // We assume only one area is being amended.
  $area = reset($areas);

  // The log type will be an activity.
  $log_type = 'farm_activity';

  // Initialize an empty measurements array.
  $measurements = array();

  // Set log name.
  $log_name = t('Soil disturbance: @area', array('@area' => entity_label('taxonomy_term', $area)));

  // Add the "Tillage" log category.
  $categories = array('Tillage');

  // Create a new farm quantity log.
  $log = farm_quantity_log_create($log_type, $log_name, $timestamp, TRUE, array(), $measurements, '', $categories);

  // Get the log entity wrapper.
  $log_wrapper = entity_metadata_wrapper('log', $log);

  // Add the area reference.
  $log_wrapper->field_farm_area[] = $area;

  // Add notes (field condition, crops in field, other notes).
  $notes = array();
  if (!empty($form_values['notes']['condition'])) {
    $notes[] = t('Field condition') . ': ' . check_plain($form_values['notes']['condition']);
  }
  if (!empty($form_values['notes']['crops'])) {
    $notes[] = t('Crops in field') . ': ' . check_plain($form_values['notes']['crops']);
  }
  if (!empty($form_values['notes']['other']['value'])) {
    $notes[] = check_plain($form_values['notes']['other']['value']);
  }
  if (!empty($notes)) {
    $log_wrapper->field_farm_notes->value->set(implode("\n\n", $notes));
    $log_wrapper->field_farm_notes->format->set($form_values['notes']['other']['format']);
  }

  // Save the log (via its wrapper).
  $log_wrapper->save();

  // Link the log to the quick form.
  if (function_exists('farm_quick_entity_link')) {
    farm_quick_entity_link('farm_soil_disturbance_form', 'log', $log);
  }
}
