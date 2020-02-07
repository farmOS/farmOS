<?php
/**
 * @file
 * Farm livestock milk quick form.
 */

/**
 * Form for adding milk harvest logs.
 */
function farm_livestock_milk_form($form, &$form_state) {

  // Determine the default quantity measure and units based on the system of
  // measurement (Weight in Lbs for US/Imperial, Volume in Liters for Metric).
  $system = farm_quantity_system_of_measurement();
  $default_measure = 'volume';
  $default_units = 'liters';
  if ($system == 'us') {
    $default_measure = 'weight';
    $default_units = 'lbs';
  }

  // We also remember what the measure and units were from the previous
  // submission. So if those are set, override the defaults.
  $default_measure = variable_get('farm_livestock_milk_measure', $default_measure);
  $default_units = variable_get('farm_livestock_milk_units', $default_units);

  // Wrapper fieldset.
  $form['milk'] = array(
    '#type' => 'fieldset',
    '#title' => t('Record a milk harvest'),
    '#description' => t('Use this form to record a milk harvest. A harvest log will be created with standard details filled in.'),
  );

  // Animal/group select.
  $form['milk']['asset'] = array(
    '#type' => 'textfield',
    '#title' => t('Group/animal'),
    '#description' => t('Select the group/animal that this milk came from.'),
    '#autocomplete_path' => 'farm_asset/autocomplete/animal+group',
    '#required' => TRUE,
  );

  // Quantity measure.
  $form['milk']['measure'] = array(
    '#type' => 'select',
    '#title' => t('Measure'),
    '#description' => t('Is this harvest measured in weight or in volume?'),
    '#options' => array(
      'weight' => t('Weight'),
      'volume' => t('Volume'),
    ),
    '#default_value' => $default_measure,
    '#required' => TRUE,
  );

  // Quantity value.
  $form['milk']['quantity'] = array(
    '#type' => 'textfield',
    '#title' => t('Quantity'),
    '#required' => TRUE,
    '#element_validate' => array('element_validate_number'),
  );

  // Quantity units.
  $form['milk']['units'] = array(
    '#type' => 'textfield',
    '#title' => t('Unit of measure'),
    '#description' => t('Specify what units this harvest is measured in. This will remember what you entered previously, so if you use standard units you only need to enter them the first time you use this form.'),
    '#autocomplete_path' => 'taxonomy/autocomplete/field_farm_quantity_units',
    '#default_value' => $default_units,
    '#required' => TRUE,
  );

  // Submit button.
  $form['milk']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save log'),
  );

  // Return the form.
  return $form;
}

/**
 * Validate callback for milk quick form.
 */
function farm_livestock_milk_form_validate($form, &$form_state) {

  // Validate the animal/group asset.
  if (!empty($form_state['values']['asset'])) {

    // Extract asset ID.
    $id = 0;
    $matches = array();
    $result = preg_match('/\\[id: ([0-9]+)\\]$/', $form_state['values']['asset'], $matches);
    if (!empty($matches[$result])) {
      $id = $matches[$result];
    }

    // If an ID couldn't be extracted, throw an error.
    if (empty($id)) {
      form_set_error('asset', t('Could not load the animal/group record. Make sure the asset ID is included. For example: "My animal [id: 123]"'));
    }

    // Load the asset.
    $asset = farm_asset_load($id);

    // If the asset didn't load, throw an error.
    if (empty($asset)) {
      form_set_error('asset', t('Could not load the animal/group record. Make sure the asset name and ID are correct.'));
    }

    // Save the asset to the form state.
    $form_state['storage']['asset'] = $asset;
  }
}

/**
 * Submit function for milk quick form.
 */
function farm_livestock_milk_form_submit($form, &$form_state) {

  // Remember what was entered for measure and units. This will be used as the
  // default the next time the form is loaded.
  $measure = check_plain($form_state['values']['measure']);
  $units = check_plain($form_state['values']['units']);
  variable_set('farm_livestock_milk_measure', $measure);
  variable_set('farm_livestock_milk_units', $units);

  // Get the asset and wrap it in an array.
  $asset = $form_state['storage']['asset'];
  $assets = array($asset);

  // The log type will be a harvest.
  $log_type = 'farm_harvest';

  // Set log name.
  $log_name = t('Milk @asset: @qty @units', array('@asset' => entity_label('farm_asset', $asset), '@qty' => $form_state['values']['quantity'], '@units' => $form_state['values']['units']));

  // Assemble an array of measurements.
  $measurements = array(
    array(
      'measure' => $measure,
      'value' => $form_state['values']['quantity'],
      'units' => $units,
      'label' => t('milk'),
    ),
  );

  // Create a new farm quantity log.
  $log = farm_quantity_log_create($log_type, $log_name, REQUEST_TIME, TRUE, $assets, $measurements);

  // Link the log to the quick form.
  if (function_exists('farm_quick_entity_link')) {
    farm_quick_entity_link('farm_livestock_milk_form', 'log', $log);
  }
}
