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
    '#ajax' => array(
      'callback' => 'farm_soil_disturbance_form_area_size_ajax',
      'wrapper' => 'area-size',
    ),
  );

  // Alias $form_state['values']['disturbance'] for easier use.
  $form_values = array();
  if (!empty($form_state['values']['disturbance'])) {
    $form_values = &$form_state['values']['disturbance'];
  }

  // If the area name has been entered, attempt to load it.
  // If multiple areas are entered, only use the first one.
  $area = FALSE;
  if (!empty($form_values['area']['name'])) {
    $area_name = $form_values['area']['name'];
    $areas = farm_term_parse_names($area_name, 'farm_areas');
    $area = reset($areas);
  }

  // Measurement type.
  $form['disturbance']['area']['measurement'] = array(
    '#type' => 'radios',
    '#title' => t('Measurement type'),
    '#description' => t('Select how you would like to measure this area.'),
    '#options' => array(
      'total' => t('Total area'),
      'dimensions' => t('Length and width'),
    ),
    '#default_value' => 'total',
    '#ajax' => array(
      'callback' => 'farm_soil_disturbance_form_area_size_ajax',
      'wrapper' => 'area-size',
    ),
  );

  // Area size wrapper.
  $form['disturbance']['area']['size'] = array(
    '#prefix' => '<div id="area-size">',
    '#suffix' => '</div>',
  );

  // Display fields depending on the measurement type.
  // If the measurement type is "dimensions", show length and width fields.
  if (!empty($form_values['area']['measurement']) && $form_values['area']['measurement'] == 'dimensions') {

    // Load the default area and length units.
    $size = 'small';
    $area_units = farm_area_default_units('area', $size);
    $length_units = farm_area_default_units('length', $size);

    // Area length.
    $form['disturbance']['area']['size']['length'] = array(
      '#type' => 'textfield',
      '#title' => t('Area length'),
      '#description' => t('How long is the area in @units?', array('@units' => $length_units)),
      '#input_group' => TRUE,
      '#field_suffix' => $length_units,
      '#ajax' => array(
        'callback' => 'farm_soil_disturbance_form_area_size_ajax',
        'wrapper' => 'area-size'
      ),
    );

    // Area width.
    $form['disturbance']['area']['size']['width'] = array(
      '#type' => 'textfield',
      '#title' => t('Area width'),
      '#description' => t('How wide is the area in @units?', array('@units' => $length_units)),
      '#input_group' => TRUE,
      '#field_suffix' => $length_units,
      '#ajax' => array(
        'callback' => 'farm_soil_disturbance_form_area_size_ajax',
        'wrapper' => 'area-size'
      ),
    );

    // Auto-calculate total surface area and store it in a hidden field.
    $total_area = '';
    if (!empty($form_values['area']['size']['length']) && !empty($form_values['area']['size']['width'])) {
      $total_area = $form_values['area']['size']['length'] * $form_values['area']['size']['width'];
      unset($form_state['input']['disturbance']['area']['size']['total']);
      unset($form_state['input']['disturbance']['area']['size']['units']);
    }
    $form['disturbance']['area']['size']['total'] = array(
      '#type' => 'hidden',
      '#value' => $total_area,
    );
    $form['disturbance']['area']['size']['units'] = array(
      '#type' => 'hidden',
      '#value' => $area_units,
    );
  }

  // Otherwise, allow direct entry of the total surface area and units.
  else {

    // Attempt to auto-calculate the total surface area from the area polygon.
    $total_area = '';
    if (!empty($area)) {
      $total_area = farm_area_calculate_area($area->tid);
    }

    // If a total area was calculated, prepare the form values.
    if (!empty($total_area)) {

      // Get the relative area size.
      $size = farm_area_relative_size($total_area);

      // Determine the default units for the relative area size.
      $units = farm_area_default_units('area', $size);

      // Convert and format the value.
      $total_area = farm_area_format_calculated_area($total_area, FALSE);

      // Reset the $form_state input values so they can be overridden.
      unset($form_state['input']['disturbance']['area']['size']['total']);
      unset($form_state['input']['disturbance']['area']['size']['units']);
    }

    // Total surface area.
    $form['disturbance']['area']['size']['total'] = array(
      '#type' => 'textfield',
      '#title' => t('Area size'),
      '#default_value' => $total_area,
      '#required' => TRUE,
    );

    // Units.
    $form['disturbance']['area']['size']['units'] = array(
      '#type' => 'select',
      '#title' => t('Area size units'),
      '#options' => drupal_map_assoc(array(
        farm_area_default_units('area', 'big'),
        farm_area_default_units('area', 'small'),
      )),
      '#default_value' => !empty($units) ? $units : NULL,
      '#required' => TRUE,
    );
  }

  // Percentage of area disturbed.
  $form['disturbance']['area']['percentage'] = array(
    '#type' => 'textfield',
    '#title' => t('Percentage of area disturbed'),
    '#description' => t('What percentage of the area was disturbed by this activity?'),
    '#default_value' => '100',
    '#required' => TRUE,
    '#input_group' => TRUE,
    '#field_suffix' => '%',
  );

  // Disturbance information fieldset.
  $form['disturbance']['disturbance'] = array(
    '#type' => 'fieldset',
    '#title' => t('Disturbance information'),
  );

  // Activity.
  $form['disturbance']['disturbance']['activity'] = array(
    '#type' => 'textfield',
    '#title' => t('Activity'),
    '#description' => t('What activity was performed?'),
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
 * Soil disturbance quick form area size ajax callback.
 */
function farm_soil_disturbance_form_area_size_ajax($form, &$form_state) {
  return $form['disturbance']['area']['size'];
}

/**
 * Soil disturbance quick form validate.
 */
function farm_soil_disturbance_form_validate($form, &$form_state) {

  // Alias $form_state['values']['disturbance'] for easier use.
  $form_values = array();
  if (!empty($form_state['values']['disturbance'])) {
    $form_values = &$form_state['values']['disturbance'];
  }

  // If the area measurement type is "dimensions", length and width are
  // required.
  if (!empty($form_values['area']['measurement']) && $form_values['area']['measurement'] == 'dimensions') {
    if (empty($form_values['area']['size']['length']) || !is_numeric($form_values['area']['size']['length'])) {
      form_set_error('disturbance][area][size][length', t('Area length is required.'));
    }
    if (empty($form_values['area']['size']['width']) || !is_numeric($form_values['area']['size']['width'])) {
      form_set_error('disturbance][area][size][width', t('Area width is required.'));
    }
  }

  // Ensure that the area percentage is between 1 and 100.
  $percentage = $form_values['area']['percentage'];
  if ($percentage < 1 || $percentage > 100) {
    form_set_error('disturbance][area][percentage', t('Percentage must be between 1 and 100.'));
  }
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
  $areas = farm_term_parse_names($area_name, 'farm_areas', TRUE);

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

  // Add the total area size.
  $total_area = array(
    'measure' => 'area',
    'value' => $form_values['area']['size']['total'],
    'units' => $form_values['area']['size']['units'],
    'label' => t('total area size'),
  );
  $measurements[] = $total_area;

  // Add the percentage of total area amended.
  $percentage = $form_values['area']['percentage'];
  $measurements[] = array(
    'measure' => 'ratio',
    'value' => $percentage,
    'units' => '%',
    'label' => t('percentage of area disturbed'),
  );

  // Calculate and add the total area disturbed, rounded to 2 decimals (use
  // BCMath where available).
  $scale = 2;
  if (function_exists('bcmul') && function_exists('bcdiv')) {
    $total_area_disturbed = bcmul($total_area['value'], bcdiv($percentage, '100', $scale), $scale);
  }
  else {
    $total_area_disturbed = round($total_area['value'] * ($percentage / 100), $scale);
  }
  $measurements[] = array(
    'measure' => 'area',
    'value' => $total_area_disturbed,
    'units' => $total_area['units'],
    'label' => t('total area disturbed'),
  );

  // Set log name.
  $log_name_parts = array(
    entity_label('taxonomy_term', $area),
    check_plain($form_values['disturbance']['activity'])
  );
  $log_name = t('Soil disturbance') . ': ' . implode(' ', $log_name_parts);

  // Add the "Tillage" log category.
  $categories = array('Tillage');

  // Create a new farm quantity log.
  $log = farm_quantity_log_create($log_type, $log_name, $timestamp, TRUE, array(), $measurements, '', $categories);

  // Get the log entity wrapper.
  $log_wrapper = entity_metadata_wrapper('log', $log);

  // Add the area reference.
  $log_wrapper->field_farm_area[] = $area;

  // Add notes (activity, field condition, crops in field, other notes).
  $notes = array();
  if (!empty($form_values['disturbance']['activity'])) {
    $notes[] = t('Activity') . ': ' . check_plain($form_values['disturbance']['activity']);
  }
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

  // Add the log to $form_state['storage'] so that other submit functions
  // can work with it.
  $form_state['storage']['log'] = $log;
}
