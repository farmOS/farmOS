<?php
/**
 * @file
 * Farm soil compost quick forms.
 */

/**
 * Implements hook_farm_quick_forms().
 */
function farm_soil_compost_farm_quick_forms() {
  return array(
    'compost-temperature' => array(
      'label' => t('Compost temperature'),
      'permission' => 'create farm_observation log entities',
      'form' => 'farm_soil_compost_temperature_form',
      'file' => 'farm_soil_compost.farm_quick.inc',
    ),
  );
}

/**
 * Form for adding compost temperature logs.
 */
function farm_soil_compost_temperature_form($form, &$form_state) {

  // Wrapper fieldset.
  $form['temperature'] = array(
    '#type' => 'fieldset',
    '#title' => t('Record compost temperature'),
    '#description' => t('Use this form to record the temperature of a compost pile. An observation log will be created with standard details filled in.'),
  );

  // Load active compost assets.
  $asset_options = array();
  $result = db_query("SELECT a.id FROM {farm_asset} a WHERE a.type = 'compost' AND a.archived = 0");
  foreach ($result as $row) {
    if (!empty($row->id)) {

      // Load the asset.
      $asset = farm_asset_load($row->id);

      // If the asset didn't load, skip it.
      if (empty($asset)) {
        continue;
      }

      // Add an option.
      $entity_label = entity_label('farm_asset', $asset);
      $asset_options[$asset->id] = htmlspecialchars($entity_label);
    }
  }

  // If there are asset options, add a dropdown.
  if (!empty($asset_options)) {
    $form['temperature']['asset'] = array(
      '#type' => 'select',
      '#title' => t('Compost asset'),
      '#description' => t('Select the compost asset that this is an observation of.'),
      '#options' => $asset_options,
    );
  }

  // Otherwise, show some text about adding a compost asset, and return early.
  else {
    $form['temperature']['asset'] = array(
      '#type' => 'markup',
      '#markup' => t('<a href="!add_compost">Add a compost asset</a> before using this form.', array('!add_compost' => url('farm/asset/add/compost', array('query' => array('destination' => current_path()))))),
      '#prefix' => '<p>',
      '#suffix' => '</p>',
    );
    return $form;
  }

  // Determine the unit based on the system of measurement.
  // (F for US/Imperial, C for Metric).
  $system = farm_quantity_system_of_measurement();
  $unit = 'C';
  if ($system == 'us') {
    $unit = 'F';
  }

  // Quantity value.
  $form['temperature']['value'] = array(
    '#type' => 'textfield',
    '#title' => t('Temperature'),
    '#required' => TRUE,
    '#element_validate' => array('element_validate_number'),
    '#input_group' => TRUE,
    '#field_suffix' => $unit,
  );

  // Quantity unit.
  $form['temperature']['unit'] = array(
    '#type' => 'value',
    '#value' => $unit,
  );

  // Submit button.
  $form['temperature']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save log'),
  );

  // Return the form.
  return $form;
}

/**
 * Submit function for compost temperature quick form.
 */
function farm_soil_compost_temperature_form_submit($form, &$form_state) {

  // The log type will be an observation.
  $log_type = 'farm_observation';

  // Load the selected asset and get the name.
  $asset = farm_asset_load($form_state['values']['asset']);
  $asset_name = htmlspecialchars(entity_label('farm_asset', $asset));

  // Set log name.
  $log_name = t('@asset temperature: @value @unit', array('@asset' => $asset_name, '@value' => $form_state['values']['value'], '@unit' => $form_state['values']['unit']));

  // Assemble an array of measurements.
  $measurements = array(
    array(
      'measure' => 'temperature',
      'value' => $form_state['values']['value'],
      'units' => $form_state['values']['unit'],
      'label' => '',
    ),
  );

  // Create a new farm quantity log.
  $log = farm_quantity_log_create($log_type, $log_name, REQUEST_TIME, TRUE, array($asset), $measurements);

  // Link the log to the quick form.
  farm_quick_entity_link('farm_soil_compost_temperature_form', 'log', $log);
}
