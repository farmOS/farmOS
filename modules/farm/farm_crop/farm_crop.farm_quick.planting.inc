<?php

/**
 * @file
 * Farm planting quick form.
 */

/**
 * Planting quick form.
 */
function farm_crop_planting_quick_form($form, &$form_state) {

  // Wrapper fieldset.
  $form['planting'] = array(
    '#type' => 'fieldset',
    '#title' => t('Record a planting'),
    '#description' => t('Use this form to record a planting, along with optional logs associated with it.'),
    '#tree' => TRUE,
  );

  // Load the season that was used last time.
  // Default to the current year.
  $season = variable_get('farm_crop_planting_season', date('Y'));

  // Season.
  $form['planting']['season'] = array(
    '#type' => 'textfield',
    '#title' => t('Season'),
    '#description' => t('What season will this planting be part of? This is used for organizing plantings for future reference, and can be something like "@year" or "@year Summer". This will be prepended to the planting asset name.', array('@year' => date('Y'))),
    '#autocomplete_path' => 'taxonomy/autocomplete/field_farm_season',
    '#default_value' => $season,
    '#ajax' => array(
      'callback' => 'farm_crop_planting_quick_form_name_ajax',
      'wrapper' => 'planting-name',
    ),
    '#required' => TRUE,
  );

  // Number of crops/varieties.
  $crop_count_default = 1;
  $form['planting']['crop_count'] = array(
    '#type' => 'select',
    '#title' => t('If this is a mix, how many crops/varieties are included?'),
    '#options' => drupal_map_assoc(range(1, 15)),
    '#default_value' => $crop_count_default,
    '#ajax' => array(
      'callback' => 'farm_crop_planting_quick_form_crops_ajax',
      'wrapper' => 'planting-crops',
    ),
  );

  // Create a field for each crop/variety.
  $crop_count = $crop_count_default;
  if (!empty($form_state['values']['planting']['crop_count'])) {
    $crop_count = $form_state['values']['planting']['crop_count'];
  }
  for ($i=0; $i<$crop_count; $i++) {
    $form['planting']['crops'][$i] = array(
      '#type' => 'textfield',
      '#title' => t('Crop/variety') . ' ' . ($i+1),
      '#autocomplete_path' => 'taxonomy/autocomplete/field_farm_crop',
      '#ajax' => array(
        'callback' => 'farm_crop_planting_quick_form_name_ajax',
        'wrapper' => 'planting-name',
      ),
      '#required' => TRUE,
    );
  }

  // Add the 'planting-crops' wrapper div around the crops field(s), so that it
  // can be replaced via AJAX.
  $form['planting']['crops']['#prefix'] = '<div id="planting-crops">';
  $form['planting']['crops']['#suffix'] = '</div>';

  // Define the date format for logs.
  $date_format = 'Y-m-d';

  // Define an array of logs that can be created for this planting.
  $logs = array(
    'seeding' => array(
      'title' => t('Seeding'),
      'type' => 'farm_seeding',
      'enabled' => TRUE,
      'fields' => array('date', 'location', 'quantity', 'notes', 'done'),
      'quantity_measures' => array('count', 'length', 'weight', 'area', 'volume', 'ratio'),
    ),
    'transplanting' => array(
      'title' => t('Transplanting'),
      'type' => 'farm_transplanting',
      'enabled' => FALSE,
      'fields' => array('date', 'location', 'quantity', 'notes', 'done'),
      'quantity_measures' => array('count', 'length', 'weight', 'area', 'volume', 'ratio'),
    ),
    'harvest' => array(
      'title' => t('Harvest'),
      'type' => 'farm_harvest',
      'enabled' => FALSE,
      'fields' => array('date', 'quantity', 'notes', 'done'),
    ),
  );

  // Create a container for all log fields.
  $form['planting']['logs'] = array(
    '#type' => 'container',
    '#prefix' => '<div id="planting-logs">',
    '#suffix' => '</div>',
  );

  // Create fields for each log.
  foreach ($logs as $name => $info) {

    // Log checkbox, checked based on $form_state, with default.
    $enabled = !empty($info['enabled']) ? $info['enabled'] : FALSE;
    if (isset($form_state['values']['planting']['logs'][$name]['enabled'])) {
      $enabled = $form_state['values']['planting']['logs'][$name]['enabled'];
    }
    $form['planting']['logs'][$name]['enabled'] = array(
      '#type' => 'checkbox',
      '#title' => t('Add') . ' ' . $info['title'],
      '#default_value' => $enabled,
      '#ajax' => array(
        'callback' => 'farm_crop_planting_quick_form_logs_ajax',
        'wrapper' => 'planting-logs'
      ),
    );

    // If the log is not enabled, stop here.
    if (empty($enabled)) {
      continue;
    }

    // Create a fieldset for the log.
    $form['planting']['logs'][$name]['#type'] = 'fieldset';
    $form['planting']['logs'][$name]['#collapsible'] = TRUE;
    $form['planting']['logs'][$name]['#collapsed'] = FALSE;

    // Add a hidden value for the log type.
    $form['planting']['logs'][$name]['type'] = array(
      '#type' => 'value',
      '#value' => $info['type'],
    );

    // Filter the available quantity measures, if desired.
    $quantity_measure_options = farm_quantity_measure_options();
    $filtered_quantity_measure_options = $quantity_measure_options;
    if (!empty($info['quantity_measures'])) {
      $filtered_quantity_measure_options = array();
      foreach ($info['quantity_measures'] as $measure) {
        if (!empty($quantity_measure_options[$measure])) {
          $filtered_quantity_measure_options[$measure] = $quantity_measure_options[$measure];
        }
      }
    }

    // Create fields.
    $field_info = array(
      'date' => array(
        '#type' => 'date_select',
        '#title' => t('Date'),
        '#date_format' => $date_format,
        '#date_label_position' => 'within',
        '#date_year_range' => '-3:+10',
        '#default_value' => REQUEST_TIME,
        '#required' => TRUE,
      ),
      'done' => array(
        '#type' => 'checkbox',
        '#title' => t('Completed'),
      ),
      'location' => array(
        '#type' => 'textfield',
        '#title' => t( 'Location'),
        '#autocomplete_path' => 'taxonomy/autocomplete/field_farm_area',
        '#required' => TRUE,
      ),
      'quantity' => array(
        '#type' => 'fieldset',
        '#title' => t('Quantity'),
        '#collapsible' => TRUE,
        '#collapsed' => TRUE,
        'measure' => array(
          '#type' => 'select',
          '#title' => t('Measure'),
          '#options' => $filtered_quantity_measure_options,
          '#default_value' => 'weight',
        ),
        'value' => array(
          '#type' => 'textfield',
          '#title' => t('Value'),
        ),
        'units' => array(
          '#type' => 'textfield',
          '#title' => t('Units'),
          '#autocomplete_path' => 'taxonomy/autocomplete/field_farm_quantity_units',
        ),
      ),
      'notes' => array(
        '#type' => 'fieldset',
        '#title' => t('Notes'),
        '#collapsible' => TRUE,
        '#collapsed' => TRUE,
        'notes' => array(
          '#type' => 'text_format',
          '#title' => $info['title'] . ' ' . t('Notes'),
          '#format' => 'farm_format',
        ),
      ),
    );
    foreach ($info['fields'] as $field) {
      if (array_key_exists($field, $field_info)) {
        $form['planting']['logs'][$name][$field] = $field_info[$field];
      }
    }
  }

  // Alias $form_state['values']['planting'] for easier use.
  $form_values = array();
  if (!empty($form_state['values']['planting'])) {
    $form_values = &$form_state['values']['planting'];
  }

  // Get the season
  $season = '';
  if (!empty($form_values['season']) ) {
    $season = $form_values['season'];
  }

  // Get Crops
  $crop_tags = array();
  if (!empty($form_values['crops']) ) {
    $crop_tags = $form_values['crops'];
  }

  // The planting will be named based on the season and crop.
  $planting_name_parts = array(
    $season,
    implode(', ', $crop_tags),
  );

  // Planting asset name.
  $form['planting']['name'] = array(
    '#type' => 'textfield',
    '#title' => t('Planting asset name'),
    '#value' => implode(' ', $planting_name_parts),
    '#prefix' => '<div id="planting-name">',
    '#suffix' => '</div>',
  );

  // Submit button.
  $form['planting']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Create planting'),
  );

  // Return the form.
  return $form;
}

/**
 * Planting quick form crops ajax callback.
 */
function farm_crop_planting_quick_form_crops_ajax($form, &$form_state) {
  return $form['planting']['crops'];
}

/**
 * Planting quick form logs ajax callback.
 */
function farm_crop_planting_quick_form_logs_ajax($form, &$form_state) {
  return $form['planting']['logs'];
}

/**
 * Planting quick form name ajax callback.
 */
function farm_crop_planting_quick_form_name_ajax($form, &$form_state) {
  return $form['planting']['name'];
}

/**
 * Planting quick form validate.
 */
function farm_crop_planting_quick_form_validate($form, &$form_state) {

}

/**
 * Planting quick form submit.
 */
function farm_crop_planting_quick_form_submit($form, &$form_state) {

  // Remember the season for future reference.
  $season = $form_state['values']['planting']['season'];
  variable_set('farm_crop_planting_season', $season);

  // Load/create the season term.
  $season_term = farm_term($season, 'farm_season');

  // Get the crop(s) and load/create terms for each.
  $crops = array();
  $crop_tags = $form_state['values']['planting']['crops'];
  foreach ($crop_tags as $tag) {
    $term = farm_term($tag, 'farm_crops');
    if (!empty($term)) {
      $crops[] = $term;
    }
  }

  // The planting will be named based on the season and crop.
  $planting_name_parts = array(
    $season,
    implode(', ', $crop_tags),
  );

  // Create a new planting asset.
  $values = array(
    'type' => 'planting',
    'name' => implode(' ', $planting_name_parts),
  );
  $planting_asset = entity_create('farm_asset', $values);
  $planting_wrapper = entity_metadata_wrapper('farm_asset', $planting_asset);

  // Add the season.
  $planting_wrapper->field_farm_season = $season_term;

  // Add the crop(s).
  foreach ($crops as $crop) {
    $planting_wrapper->field_farm_crop[] = $crop;
  }

  // Save the planting.
  $planting_wrapper->save();

  // Link the asset to this quick form.
  if (function_exists('farm_quick_entity_link')) {
    farm_quick_entity_link('farm_crop_planting_form', 'farm_asset', $planting_asset);
  }

  // Set a message.
  $label = entity_label('farm_asset', $planting_asset);
  $uri = entity_uri('farm_asset', $planting_asset);
  drupal_set_message('Planting created: ' . l($label, $uri['path']));

  // Iterate through the logs.
  foreach ($form_state['values']['planting']['logs'] as $name => $log_values) {

    // If the log is not enabled, skip it.
    if (empty($log_values['enabled'])) {
      continue;
    }

    // Convert the date to a timestamp.
    $timestamp = strtotime($log_values['date']);

    // If the location is available, load areas.
    $areas = array();
    if (!empty($log_values['location'])) {
      $areas = farm_area_parse_names($log_values['location'], TRUE);
    }

    // Build quantity measurements.
    $measurements = array();
    if (!empty($log_values['quantity']['value'])) {
      $measurements[] = $log_values['quantity'];
    }

    // Mark the log as done or not done.
    $done = FALSE;
    if (!empty($log_values['done'])) {
      $done = TRUE;
    }

    // If there is a location, create a movement log.
    if (!empty($log_values['location'])) {
      $log = farm_movement_create($planting_asset, $areas, $timestamp, $log_values['type'], $done);
    }

    // Otherwise, create a normal log (without movement location).
    else {
      $log = farm_log_create($log_values['type'], '', $timestamp, $done, array($planting_asset));
    }

    // If there are quantity measurements, add them to the log.
    if (!empty($measurements)) {
      farm_quantity_log_add_measurements($log, $measurements);
    }

    // Create an entity metadata wrapper for the log.
    $log_wrapper = entity_metadata_wrapper('log', $log);

    // Set the log notes, if available.
    if (!empty($log_values['notes']['notes']['value'])) {
      $log_wrapper->field_farm_notes = $log_values['notes']['notes'];
    }

    // Save the log.
    $log_wrapper->save();

    // Link the log to the quick form.
    if (function_exists('farm_quick_entity_link')) {
      farm_quick_entity_link('farm_crop_planting_form', 'log', $log);
    }
  }
}
