<?php
/**
 * @file
 * Farm livestock move quick form.
 */

/**
 * Form for adding animal movement logs.
 */
function farm_livestock_move_form($form, &$form_state) {

  // Wrapper fieldset.
  $form['move'] = array(
    '#type' => 'fieldset',
    '#title' => t('Record animal move'),
    '#description' => t('Use this form to record the movement of animals to an area. An activity log will be created with standard details filled in. You can also specify before/after observations of the area(s) that the animals are moving to/from.'),
  );

  // Make the form a tree.
  $form['move']['#tree'] = TRUE;

  // Define the date format for logs.
  $date_format = 'Y-m-d';

  // Movement date.
  $form['move']['date'] = array(
    '#type' => 'date_select',
    '#title' => t('Movement Date'),
    '#date_format' => $date_format,
    '#date_label_position' => 'within',
    '#date_year_range' => '-10:+3',
    '#default_value' => REQUEST_TIME,
    '#required' => TRUE,
  );

  // Animal/group select.
  $form['move']['asset'] = array(
    '#type' => 'textfield',
    '#title' => t('Group/animal'),
    '#description' => t('Select the group/animal that is being moved.'),
    '#autocomplete_path' => 'farm_asset/autocomplete/animal+group',
    '#ajax' => array(
      'callback' => 'farm_livestock_move_form_current_location_ajax',
    ),
    '#required' => TRUE,
  );

  // Load the animal or group asset
  // if an asset name has been entered.
  if (!empty($form_state['values']['move']['asset'])) {

    // Extract an ID from the text field.
    $asset_id = 0;
    $matches = array();
    $result = preg_match('/\\[id: ([0-9]+)\\]/', $form_state['values']['move']['asset'], $matches);
    if (!empty($matches[$result])) {
      $asset_id = $matches[$result];
    }
  }

  // If an asset ID was found, load the asset.
  $asset = NULL;
  if (!empty($asset_id)) {
    $asset = farm_asset_load($asset_id);
  }

  $form['move']['current_location'] = array(
    '#type' => 'hidden',
    '#value' => '',
    '#prefix' => '<div id="current-location">',
    '#suffix' => '</div>',
  );

  // If a valid asset was supplied load its
  // current location and save WKT to hidden field.
  if (!empty($asset)) {
    $areas = farm_movement_asset_location($asset);

    // Get WKT geometry.
    $area_ids = array();
    foreach($areas as $area) {
      $area_ids[] = $area->tid;
    }
    $geom = farm_area_extract_geoms($area_ids);

    // Update current location field with WKT.
    $form['move']['current_location']['#value'] = $geom;
  }

  // Area reference.
  $form['move']['area']['name'] = array(
    '#type' => 'textfield',
    '#title' => t('Moving to'),
    '#description' => t('Enter the name of the area that animals are moving to. A list of existing area options will appear as you type. If the area does not exist, a new one will be created.'),
    '#autocomplete_path' => 'taxonomy/autocomplete/field_farm_area',
    '#ajax' => array(
      'callback' => 'farm_livestock_move_form_next_location_ajax',
    ),
    '#required' => TRUE,
  );

  // Geometry
  // Add a farmOS map instance.
  $form['move']['area']['geometry'] = array(
    '#type' => 'fieldset',
    '#title' => t('Geometry'),
    '#description' => t('This field allows you to optionally specify a more precise geometry for the new location of assets. If you leave it blank, the geometry will be copied from the areas that assets are moving to (if available).'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['move']['area']['geometry']['map'] = array(
    '#type' => 'farm_map',
    '#map_name' => 'farm_movement',
  );
  $form['move']['area']['geometry']['data'] = array(
    '#prefix' => '<div id="movement-geometry">',
    '#suffix' => '</div>',
    '#type' => 'textarea',
    '#title' => t('Data'),
    '#default_value' => '',
  );

  // If provided, load the next location WKT
  // and update the geometry textarea.
  if (!empty($form_state['values']['move']['area']['name'])) {
    $areas = farm_term_parse_names($form_state['values']['move']['area']['name'], 'farm_areas', FALSE);

    // Load WKT geometry.
    $area_ids = array();
    foreach($areas as $area) {
      $area_ids[] = $area->tid;
    }
    $geom = farm_area_extract_geoms($area_ids);

    // Update geometry textarea with WKT.
    // Because textarea values cannot be an array we
    // use the first index as the string to display.
    if (!empty($geom[0])) {
      $form['move']['area']['geometry']['data']['#default_value'] = $geom[0];
      unset($form_state['input']['move']['area']['geometry']['data']);
    }
  }

  // Observations
  $form['move']['observations'] = array(
    '#type' => 'fieldset',
    '#title' => t('Observations'),
    '#description' => t('Optionally provide information about the area(s) that animals are moving out of, as well as the area(s) they are moving into.'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  // Determine the unit based on the system of measurement.
  // (inches for US/Imperial, cm for Metric).
  $system = farm_quantity_system_of_measurement();
  $unit = 'cm';
  if ($system == 'us') {
    $unit = 'inches';
  }

  // Save the unit for later.
  $form['move']['observations']['pasture_height_units'] = array(
    '#type' => 'value',
    '#value' => $unit,
  );

  // Post grazing.
  $form['move']['observations']['post'] = array(
    '#type' => 'fieldset',
    '#title' => t('Post grazing in last area'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['move']['observations']['post']['pasture_height'] = array(
    '#type' => 'textfield',
    '#title' => t('Pasture height'),
    '#input_group' => TRUE,
    '#field_suffix' => $unit,
    '#element_validate' => array('element_validate_number'),
  );
  $form['move']['observations']['post']['forage_quality'] = array(
    '#type' => 'textfield',
    '#title' => t('Relative forage quality'),
    '#description' => t('Give the forage quality a rating. This can be any number, but using a consistent scale (eg: 1-10) helps in future comparisons.'),
    '#element_validate' => array('element_validate_number'),
  );
  $form['move']['observations']['post']['photos'] = array(
    '#type' => 'file',
    '#title' => t('Photos'),
    '#name' => 'files[]',
    '#attributes' => array('multiple' => 'multiple'),
  );

  // Pre grazing
  $form['move']['observations']['pre'] = array(
    '#type' => 'fieldset',
    '#title' => t('Pre grazing in next area'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['move']['observations']['pre']['pasture_height'] = array(
    '#type' => 'textfield',
    '#title' => t('Pasture height'),
    '#input_group' => TRUE,
    '#field_suffix' => $unit,
    '#element_validate' => array('element_validate_number'),
  );
  $form['move']['observations']['pre']['forage_quality'] = array(
    '#type' => 'textfield',
    '#title' => t('Relative forage quality'),
    '#description' => t('Give the forage quality a rating. This can be any number, but using a consistent scale (eg: 1-10) helps in future comparisons.'),
    '#element_validate' => array('element_validate_number'),
  );
  $form['move']['observations']['pre']['photos'] = array(
    '#type' => 'file',
    '#title' => t('Photos'),
    '#name' => 'files[]',
    '#attributes' => array('multiple' => 'multiple'),
  );

  // Submit button.
  $form['move']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save'),
  );

  // Add javascript.
  drupal_add_js(drupal_get_path('module', 'farm_livestock') . '/js/farm_livestock.farm_quick.move.js');

  // Return the form.
  return $form;
}

/**
 * Validate callback for movement quick form.
 */
function farm_livestock_move_form_validate($form, &$form_state) {

  // Validate animal or group asset.
  // Extract asset ID.
  $id = 0;
  $matches = array();
  $result = preg_match('/\\[id: ([0-9]+)\\]/', $form_state['values']['move']['asset'], $matches);
  if (!empty($matches[$result])) {
    $id = $matches[$result];
  }

  // If an ID couldn't be extracted, throw an error.
  if (empty($id)) {
    form_set_error('move][asset', t('Could not load the animal asset. Make sure the animal asset ID is included. For example: "My animal [id: 123]"'));
  }

  // Load the asset.
  $asset = farm_asset_load($id);

  // If the asset didn't load, throw an error.
  if (empty($asset)) {
    form_set_error('move][asset', t('Could not load the animal asset. Make sure the animal name and ID are correct.'));
  }

  // Save the asset to the form state.
  $form_state['storage']['asset'] = $asset;
}

/**
 * Ajax callback for farm_livestock_move_form().
 */
function farm_livestock_move_form_current_location_ajax($form, $form_state) {

  // Get the "wkt" form element and CSS selector.
  $element = $form['move']['current_location'];
  $selector = '#current-location';

  // Assemble commands...
  $commands = array();

  // Replace the hidden field.
  $commands[] = ajax_command_replace($selector, render($element));

  // Execute Javascript to add WKT to the map.
  $commands[] = array('command' => 'previewCurrentLocation');

  // Return ajax commands.
  return array('#type' => 'ajax', '#commands' => $commands);
}

/**
 * Ajax callback for farm_livestock_move_form().
 */
function farm_livestock_move_form_next_location_ajax($form, $form_state) {

  // Get the "wkt" form element and CSS selector.
  $element = $form['move']['area']['geometry']['data'];
  $selector = '#movement-geometry';

  // Assemble commands...
  $commands = array();

  // Replace the hidden field.
  $commands[] = ajax_command_replace($selector, render($element));

  // Execute Javascript to add WKT to the map.
  $commands[] = array('command' => 'updateMovementLayer');

  // Return ajax commands.
  return array('#type' => 'ajax', '#commands' => $commands);
}

/**
 * Submit function for movement quick form.
 */
function farm_livestock_move_form_submit($form, &$form_state) {

  // Get the movement timestamp.
  $timestamp = strtotime($form_state['values']['move']['date']);

  // Load the asset.
  $asset = $form_state['storage']['asset'];
  if (empty($asset)) {
    return;
  }

  // Save the pasture height units.
  $pasture_height_units = $form_state['values']['move']['observations']['pasture_height_units'];

  // Build post grazing measurements.
  $post_grazing_measurements = array();

  // Add post grazing pasture height measurement.
  if (!empty($form_state['values']['move']['observations']['post']['pasture_height'])) {
    $post_grazing_measurements[] = array(
      'measure' => 'length',
      'value' => $form_state['values']['move']['observations']['post']['pasture_height'],
      'units' => $pasture_height_units,
      'label' => 'Pasture Height',
    );
  }

  // Add post grazing forage quality measurement.
  if (!empty($form_state['values']['move']['observations']['post']['forage_quality'])) {
    $post_grazing_measurements[] = array(
      'measure' => 'rating',
      'value' => $form_state['values']['move']['observations']['post']['forage_quality'],
      'label' => 'Forage Quality',
    );
  }

  // Create post grazing observation log if measurements are provided.
  // Do this before creating a movement log for the asset.
  if (!empty($post_grazing_measurements)) {
    $post_grazing_log = farm_quantity_log_create('farm_observation', 'Post grazing observation', $timestamp, TRUE, array($asset), $post_grazing_measurements);

    // Link the post grazing log to the quick form.
    if (function_exists('farm_quick_entity_link')) {
      farm_quick_entity_link('farm_livestock_move_form', 'log', $post_grazing_log);
    }

    // Link post grazing logs to the area(s) animals are moving from.
    $previous_areas = farm_movement_asset_location($asset);
    if (!empty($previous_areas)) {

      // Create an entity metadata wrapper for the log.
      $log_wrapper = entity_metadata_wrapper('log', $post_grazing_log);

      // Add areas to log.
      foreach($previous_areas as $area) {
        $log_wrapper->field_farm_area[] = $area;
      }

      // Save the log.
      $log_wrapper->save();
    }
  }

  // If the location is available, load areas.
  $areas = array();
  if (!empty($form_state['values']['move']['area']['name'])) {
    $areas = farm_term_parse_names($form_state['values']['move']['area']['name'], 'farm_areas', TRUE);
  }

  // Get the geometry.
  $geom = '';
  if (!empty($form_state['values']['move']['area']['geometry']['data'])) {
    $geom = $form_state['values']['move']['area']['geometry']['data'];
  }

  // Create activity log with asset movement to areas and specific geometry.
  $movement_log = farm_movement_create($asset, $areas, $timestamp, 'farm_activity', TRUE, $geom);

  // Save the movement log.
  log_save($movement_log);

  // Link the movement log to the quick form.
  if (function_exists('farm_quick_entity_link')) {
    farm_quick_entity_link('farm_livestock_move_form', 'log', $movement_log);
  }

  // Build pre grazing measurements.
  $pre_grazing_measurements = array();

  // Add pre grazing pasture height measurement.
  if (!empty($form_state['values']['move']['observations']['pre']['pasture_height'])) {
    $pre_grazing_measurements[] = array(
      'measure' => 'length',
      'value' => $form_state['values']['move']['observations']['pre']['pasture_height'],
      'units' => $pasture_height_units,
      'label' => 'Pasture Height',
    );
  }

  // Add pre grazing forage quality measurement.
  if (!empty($form_state['values']['move']['observations']['pre']['forage_quality'])) {
    $pre_grazing_measurements[] = array(
      'measure' => 'rating',
      'value' => $form_state['values']['move']['observations']['pre']['forage_quality'],
      'label' => 'Forage Quality',
    );
  }

  // Create pre grazing observation log if measurements are provided.
  if (!empty($pre_grazing_measurements)) {
    $pre_grazing_log = farm_quantity_log_create('farm_observation', 'Pre grazing observation', $timestamp, TRUE, array($asset), $pre_grazing_measurements);

    // Link the pre grazing log to the quick form.
    if (function_exists('farm_quick_entity_link')) {
      farm_quick_entity_link('farm_livestock_move_form', 'log', $pre_grazing_log);
    }

    // Link pre grazing logs to the area animals are moving to.
    if (!empty($areas)) {
      // Create an entity metadata wrapper for the log.
      $log_wrapper = entity_metadata_wrapper('log', $pre_grazing_log);

      // Add areas to log.
      foreach($areas as $area) {
        $log_wrapper->field_farm_area[] = $area;
      }

      // Save the log.
      $log_wrapper->save();
    }
  }
}
