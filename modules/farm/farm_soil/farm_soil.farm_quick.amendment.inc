<?php

/**
 * @file
 * Farm soil amendment quick form.
 */

/**
 * Soil amendment quick form.
 */
function farm_soil_amendment_form($form, &$form_state) {

  // Wrapper fieldset.
  $form['amendment'] = array(
    '#type' => 'fieldset',
    '#title' => t('Record a soil amendment'),
    '#description' => t('Use this form to record amendments that were made to your soil. A new input log will be created.'),
    '#tree' => TRUE,
  );

  // Date select (default to now).
  $form['amendment']['timestamp'] = array(
    '#type' => 'date_select',
    '#title' => t('Date'),
    '#date_format' => 'M j Y H:i',
    '#date_type' => DATE_FORMAT_UNIX,
    '#date_year_range' => '-10:+3',
    '#default_value' => REQUEST_TIME,
    '#required' => TRUE,
  );

  // Area information fieldset.
  $form['amendment']['area'] = array(
    '#type' => 'fieldset',
    '#title' => t('Area information'),
  );

  // Area reference.
  $form['amendment']['area']['name'] = array(
    '#type' => 'textfield',
    '#title' => t('Area name'),
    '#description' => t('Enter the name of the area that this amendment was applied to. A list of existing area options will appear as you type. If the area does not exist, a new one will be created.'),
    '#autocomplete_path' => 'taxonomy/autocomplete/field_farm_area',
    '#required' => TRUE,
    '#ajax' => array(
      'callback' => 'farm_soil_amendment_form_area_size_ajax',
      'wrapper' => 'area-size',
    ),
  );

  // Alias $form_state['values']['amendment'] for easier use.
  $form_values = array();
  if (!empty($form_state['values']['amendment'])) {
    $form_values = &$form_state['values']['amendment'];
  }

  // If the area name has been entered, attempt to load it.
  // If multiple areas are entered, only use the first one.
  $area = FALSE;
  if (!empty($form_values['area']['name'])) {
    $area_name = $form_values['area']['name'];
    $areas = farm_area_parse_names($area_name);
    $area = reset($areas);
  }

  // Measurement type.
  $form['amendment']['area']['measurement'] = array(
    '#type' => 'radios',
    '#title' => t('Measurement type'),
    '#description' => t('Select how you would like to measure this area.'),
    '#options' => array(
      'total' => t('Total area'),
      'dimensions' => t('Length and width'),
    ),
    '#default_value' => 'total',
    '#ajax' => array(
      'callback' => 'farm_soil_amendment_form_area_size_ajax',
      'wrapper' => 'area-size',
    ),
  );

  // Area size wrapper.
  $form['amendment']['area']['size'] = array(
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
    $form['amendment']['area']['size']['length'] = array(
      '#type' => 'textfield',
      '#title' => t('Area length'),
      '#description' => t('How long is the area in @units?', array('@units' => $length_units)),
      '#input_group' => TRUE,
      '#field_suffix' => $length_units,
      '#ajax' => array(
        'callback' => 'farm_soil_amendment_form_area_size_ajax',
        'wrapper' => 'area-size'
      ),
    );

    // Area width.
    $form['amendment']['area']['size']['width'] = array(
      '#type' => 'textfield',
      '#title' => t('Area width'),
      '#description' => t('How wide is the area in @units?', array('@units' => $length_units)),
      '#input_group' => TRUE,
      '#field_suffix' => $length_units,
      '#ajax' => array(
        'callback' => 'farm_soil_amendment_form_area_size_ajax',
        'wrapper' => 'area-size'
      ),
    );

    // Auto-calculate total surface area and store it in a hidden field.
    $total_area = '';
    if (!empty($form_values['area']['size']['length']) && !empty($form_values['area']['size']['width'])) {
      $total_area = $form_values['area']['size']['length'] * $form_values['area']['size']['width'];
      unset($form_state['input']['amendment']['area']['size']['total']);
      unset($form_state['input']['amendment']['area']['size']['units']);
    }
    $form['amendment']['area']['size']['total'] = array(
      '#type' => 'hidden',
      '#value' => $total_area,
    );
    $form['amendment']['area']['size']['units'] = array(
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
      unset($form_state['input']['amendment']['area']['size']['total']);
      unset($form_state['input']['amendment']['area']['size']['units']);
    }

    // Total surface area.
    $form['amendment']['area']['size']['total'] = array(
      '#type' => 'textfield',
      '#title' => t('Area size'),
      '#default_value' => $total_area,
      '#required' => TRUE,
    );

    // Units.
    $form['amendment']['area']['size']['units'] = array(
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

  // Amendment information fieldset.
  $form['amendment']['amendment'] = array(
    '#type' => 'fieldset',
    '#title' => t('Amendment information'),
  );

  // Name of amendment.
  $form['amendment']['amendment']['material'] = array(
    '#type' => 'textfield',
    '#title' => t('Amendment name'),
    '#description' => t('Enter the name of the amendment that was applied to this area. A list of existing amendment options will appear as you type.'),
    '#autocomplete_path' => 'taxonomy/autocomplete/field_farm_material',
    '#required' => TRUE,
  );

  // Nutrient analysis.
  $form['amendment']['amendment']['nutrients'] = array(
    '#type' => 'fieldset',
    '#title' => t('Nutrient analysis'),
  );
  $form['amendment']['amendment']['nutrients']['n'] = array(
    '#type' => 'textfield',
    '#title' => t('Percentage N'),
    '#element_validate' => array('element_validate_number'),
    '#input_group' => TRUE,
    '#field_suffix' => '%',
  );
  $form['amendment']['amendment']['nutrients']['p'] = array(
    '#type' => 'textfield',
    '#title' => t('Percentage P'),
    '#element_validate' => array('element_validate_number'),
    '#input_group' => TRUE,
    '#field_suffix' => '%',
  );
  $form['amendment']['amendment']['nutrients']['k'] = array(
    '#type' => 'textfield',
    '#title' => t('Percentage K'),
    '#element_validate' => array('element_validate_number'),
    '#input_group' => TRUE,
    '#field_suffix' => '%',
  );

  // Source.
  $form['amendment']['amendment']['source'] = array(
    '#type' => 'textfield',
    '#title' => t('Source/manufacturer'),
    '#description' => t('Where was this input obtained? Who manufactured it?'),
    '#ajax' => array(
      'callback' => 'farm_soil_amendment_form_amendment_source_ajax',
      'wrapper' => 'source-extra',
    ),
  );

  // Wrap extra source fields so they can be conditionally displayed via AJAX.
  $form['amendment']['amendment']['source_extra'] = array(
    '#prefix' => '<div id="source-extra">',
    '#suffix' => '</div>',
  );

  // If a value has been entered for the source, display extra fields.
  if (!empty($form_values['amendment']['source'])) {

    // Lot number.
    $form['amendment']['amendment']['source_extra']['lot'] = array(
      '#type' => 'textfield',
      '#title' => t('Lot number'),
      '#description' => t('If the manufacturer assigned this input a lot number, enter it here.'),
    );

    // Date of purchase.
    $form['amendment']['amendment']['source_extra']['purchase_date'] = array(
      '#type' => 'date_select',
      '#title' => t('Date of purchase'),
      '#date_format' => 'M j Y',
      '#date_type' => DATE_FORMAT_UNIX,
      '#date_year_range' => '-10:+3',
    );
  }

  // Application information fieldset.
  $form['amendment']['application'] = array(
    '#type' => 'fieldset',
    '#title' => t('Application information'),
  );

  // Method of application.
  $form['amendment']['application']['method'] = array(
    '#type' => 'select',
    '#title' => t('Method of application'),
    '#options' => drupal_map_assoc(array(
      t('Broadcast'),
      t('Sidedress'),
      t('Foliar'),
      t('Other'),
    )),
  );

  // Quantity measure.
  $measure = 'weight';
  if (!empty($form_values['application']['quantity']['measure'])) {
    $measure = $form_values['application']['quantity']['measure'];
  }
  $form['amendment']['application']['quantity']['measure'] = array(
    '#type' => 'radios',
    '#title' => t('Measure'),
    '#title_display' => 'invisible',
    '#options' => array(
      'weight' => t('Weight'),
      'volume' => t('Volume'),
    ),
    '#default_value' => $measure,
    '#required' => TRUE,
    '#ajax' => array(
      'callback' => 'farm_soil_amendment_form_quantity_units_ajax',
      'wrapper' => 'quantity-units',
    ),
  );

  // Quantity value.
  $form['amendment']['application']['quantity']['value'] = array(
    '#type' => 'textfield',
    '#title' => t('Quantity'),
    '#element_validate' => array('element_validate_number'),
    '#required' => TRUE,
    '#ajax' => array(
      'callback' => 'farm_soil_amendment_form_rate_ajax',
      'wrapper' => 'application-rate',
    ),
  );

  // Quantity units.
  $quantity_units = farm_quantity_units($measure);
  $form['amendment']['application']['quantity']['units'] = array(
    '#type' => 'select',
    '#title' => t('Quantity units'),
    '#options' => drupal_map_assoc($quantity_units),
    '#required' => TRUE,
    '#ajax' => array(
      'callback' => 'farm_soil_amendment_form_rate_ajax',
      'wrapper' => 'application-rate',
    ),
    '#prefix' => '<div id="quantity-units">',
    '#suffix' => '</div>',
  );

  // Percentage of area amended.
  $form['amendment']['application']['percentage'] = array(
    '#type' => 'textfield',
    '#title' => t('Percentage of area amended'),
    '#description' => t('What percentage of the area had amendments applied to it?'),
    '#default_value' => '100',
    '#required' => TRUE,
    '#input_group' => TRUE,
    '#field_suffix' => '%',
    '#ajax' => array(
      'callback' => 'farm_soil_amendment_form_rate_ajax',
      'wrapper' => 'application-rate',
    ),
  );

  // Calculate the rate of application based on the total area size, percentage
  // of area amended, and total quantity applied.
  $rate = array();
  $rate_markup = '<strong>Enter total area size, percentage of area amended, and total quantity applied above to automatically calculate the rate of application.</strong>';
  if (!empty($form_values['application']['quantity']['value']) && !empty($form_values['application']['quantity']['units']) && !empty($form_values['application']['percentage']) && !empty($form_values['area']['size']['total']) && !empty($form_values['area']['size']['units'])) {
    $qty_value = $form_values['application']['quantity']['value'];
    $qty_units = $form_values['application']['quantity']['units'];
    $percentage = $form_values['application']['percentage'];
    $area_value = $form_values['area']['size']['total'];
    $area_units = $form_values['area']['size']['units'];

    // Use BCMath to calculate the rate, where available (round to 2 decimals).
    $scale = 2;
    if (function_exists('bcdiv') && function_exists('bcmul')) {
      $percentage_decimal = bcdiv($percentage, '100', $scale);
      $actual_area = bcmul($area_value, $percentage_decimal, $scale);
      $rate_value = bcdiv($qty_value, $actual_area, $scale);
    }
    else {
      $rate_value = round($qty_value / ($area_value * ($percentage / 100)), $scale);
    }

    // Simply build the rate units from the quantity and area units.
    $rate_units = $qty_units . '/' . $area_units;

    // Set the rate value for storage in the form.
    $rate = array(
      'measure' => 'ratio',
      'value' => $rate_value,
      'units' => $rate_units,
    );

    // Build the rate markup for display in the form.
    $rate_markup = '<p><strong>' . $rate['value'] . ' ' . $rate['units'] . '</strong></p>';
  }

  // Calculated rate of application fieldset, display, and value.
  $form['amendment']['application']['rate'] = array(
    '#type' => 'fieldset',
    '#title' => t('Rate of application'),
    '#description' => t('This summarizes the calculated rate of application, based on the information entered above.'),
    '#prefix' => '<div id="application-rate">',
    '#suffix' => '</div>',
  );
  $form['amendment']['application']['rate']['display'] = array(
    '#type' => 'markup',
    '#markup' => $rate_markup,
  );
  $form['amendment']['application']['rate']['value'] = array(
    '#type' => 'markup',
    '#value' => $rate,
  );
  $form['amendment']['application']['rate']['recalculate'] = array(
    '#type' => 'button',
    '#value' => t('Recalculate'),
    '#ajax' => array(
      'callback' => 'farm_soil_amendment_form_rate_ajax',
      'wrapper' => 'application-rate',
    ),
  );

  // Notes fieldset.
  $form['amendment']['notes'] = array(
    '#type' => 'fieldset',
    '#title' => t('Notes'),
  );

  // Field condition.
  $form['amendment']['notes']['condition'] = array(
    '#type' => 'textfield',
    '#title' => t('Field condition'),
    '#description' => t('Briefly describe the field conditions of this area at the time of application.'),
  );

  // Crops going into field.
  $form['amendment']['notes']['crops'] = array(
    '#type' => 'textfield',
    '#title' => t('Crops in field'),
    '#description' => t('List the crops that are in this area, or will be in this area during/after this amendment.'),
  );

  // Other notes.
  $form['amendment']['notes']['other'] = array(
    '#type' => 'text_format',
    '#title' => t('Other notes'),
    '#description' => t('Include any other notes that are relevant to this amendment application for future reference.'),
    '#format' => 'farm_format',
  );

  // Submit button.
  $form['amendment']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Create input log'),
  );

  // Return the form.
  return $form;
}

/**
 * Soil amendment quick form area size ajax callback.
 */
function farm_soil_amendment_form_area_size_ajax($form, &$form_state) {
  return $form['amendment']['area']['size'];
}

/**
 * Soil amendment quick form amendment source ajax callback.
 */
function farm_soil_amendment_form_amendment_source_ajax($form, &$form_state) {
  return $form['amendment']['amendment']['source_extra'];
}

/**
 * Soil amendment quick form quantity units ajax callback.
 */
function farm_soil_amendment_form_quantity_units_ajax($form, &$form_state) {
  return $form['amendment']['application']['quantity']['units'];
}

/**
 * Soil amendment quick form rate ajax callback.
 */
function farm_soil_amendment_form_rate_ajax($form, &$form_state) {
  return $form['amendment']['application']['rate'];
}

/**
 * Soil amendment quick form validate.
 */
function farm_soil_amendment_form_validate($form, &$form_state) {

  // Alias $form_state['values']['amendment'] for easier use.
  $form_values = array();
  if (!empty($form_state['values']['amendment'])) {
    $form_values = &$form_state['values']['amendment'];
  }

  // If the area measurement type is "dimensions", length and width are
  // required.
  if (!empty($form_values['area']['measurement']) && $form_values['area']['measurement'] == 'dimensions') {
    if (empty($form_values['area']['size']['length']) || !is_numeric($form_values['area']['size']['length'])) {
      form_set_error('amendment][area][size][length', t('Area length is required.'));
    }
    if (empty($form_values['area']['size']['width']) || !is_numeric($form_values['area']['size']['width'])) {
      form_set_error('amendment][area][size][width', t('Area width is required.'));
    }
  }

  // Ensure that the area percentage is between 1 and 100.
  $percentage = $form_values['application']['percentage'];
  if ($percentage < 1 || $percentage > 100) {
    form_set_error('amendment][application][percentage', t('Percentage must be between 1 and 100.'));
  }

  // If the broadcast method is "Other", require notes.
  $method = $form_values['application']['method'];
  $notes = $form_values['notes']['other']['value'];
  if ($method == 'Other' && empty($notes)) {
    form_set_error('amendment][notes][other][value', t('Describe the method of application in the "Other notes" field.'));
  }
}

/**
 * Soil amendment quick form submit.
 */
function farm_soil_amendment_form_submit($form, &$form_state) {

  // Alias $form_state['values']['amendment'] for easier use.
  $form_values = array();
  if (!empty($form_state['values']['amendment'])) {
    $form_values = &$form_state['values']['amendment'];
  }

  // Get the amendment timestamp.
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

  // The log type will be an input.
  $log_type = 'farm_input';

  // Initialize an empty measurements array.
  $measurements = array();

  // Add the total quantity of amendment applied.
  $total_amendment = array(
    'measure' => $form_values['application']['quantity']['measure'],
    'value' => $form_values['application']['quantity']['value'],
    'units' => $form_values['application']['quantity']['units'],
    'label' => t('total amendment applied'),
  );
  $measurements[] = $total_amendment;

  // Add the total area size.
  $total_area = array(
    'measure' => 'area',
    'value' => $form_values['area']['size']['total'],
    'units' => $form_values['area']['size']['units'],
    'label' => t('total area size'),
  );
  $measurements[] = $total_area;

  // Add the percentage of total area amended.
  $percentage = $form_values['application']['percentage'];
  $measurements[] = array(
    'measure' => 'ratio',
    'value' => $form_values['application']['percentage'],
    'units' => '%',
    'label' => t('percentage of area amended'),
  );

  // Calculate and add the total area amended, rounded to 2 decimals (use
  // BCMath where available).
  $scale = 2;
  if (function_exists('bcmul') && function_exists('bcdiv')) {
    $total_area_amended = bcmul($total_area['value'], bcdiv($percentage, '100', $scale), $scale);
  }
  else {
    $total_area_amended = round($total_area['value'] * ($percentage / 100), $scale);
  }
  $measurements[] = array(
    'measure' => 'area',
    'value' => $total_area_amended,
    'units' => $total_area['units'],
    'label' => t('total area amended'),
  );

  // Add the nutrient analysis for N, P, and K.
  $nutrients = array('n' => 'N', 'p' => 'P', 'k' => 'K');
  foreach ($nutrients as $nutrient => $label) {
    if (!empty($form_values['amendment']['nutrients'][$nutrient])) {
      $measurements[] = array(
        'measure' => 'ratio',
        'value' => $form_values['amendment']['nutrients'][$nutrient],
        'units' => '%',
        'label' => $label,
      );
    }
  }

  // Get the amendment name for inclusion in the log name.
  $material = $form_values['amendment']['material'];

  // Set log name.
  $log_name = t('Soil amendment: @qty @units @material into @area', array('@qty' => $total_amendment['value'], '@units' => $total_amendment['units'], '@material' => $material, '@area' => entity_label('taxonomy_term', $area)));

  // Create a new farm quantity log.
  $log = farm_quantity_log_create($log_type, $log_name, $timestamp, TRUE, array(), $measurements);

  // Get the log entity wrapper.
  $log_wrapper = entity_metadata_wrapper('log', $log);

  // Add the area reference.
  $log_wrapper->field_farm_area[] = $area;

  // Create/load amendment material and add it to the log.
  $material_term = farm_term($material, 'farm_materials');
  $log_wrapper->field_farm_material[] = $material_term;

  // Set the purpose to "Soil amendment".
  $log_wrapper->field_farm_input_purpose->set(t('Soil amendment'));

  // Add method of application.
  $log_wrapper->field_farm_input_method->set(check_plain($form_values['application']['method']));

  // Add source/manufacturer, if it exists.
  if (!empty($form_values['amendment']['source'])) {
    $log_wrapper->field_farm_input_source->set(check_plain($form_values['amendment']['source']));
  }

  // Add lot number, if it exists.
  if (!empty($form_values['amendment']['source_extra']['lot'])) {
    $log_wrapper->field_farm_lot_number->set(check_plain($form_values['amendment']['source_extra']['lot']));
  }

  // Add date of purchase, if it exists.
  if (!empty($form_values['amendment']['source_extra']['purchase_date'])) {
    $purchase_date = strtotime($form_values['amendment']['source_extra']['purchase_date']);
    $log_wrapper->field_farm_date_purchase->set($purchase_date);
  }

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
    farm_quick_entity_link('farm_soil_amendment_form', 'log', $log);
  }
}
