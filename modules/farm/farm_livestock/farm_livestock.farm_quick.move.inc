<?php
/**
 * @file
 * Farm livestock move quick form.
 */

/**
 * Form for adding animal movement logs.
 */
function farm_livestock_move_form($form, &$form_state) {

  // Planned log fieldset.
  $form['planned'] = array(
    '#type' => 'fieldset',
    '#title' => t('Planned Movements'),
    '#description' => t('Modify future movements that have been created with this quick form.'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  // Query pending activity logs linked to this quick form.
  $query = db_select('farm_quick_entity', 'fqe');
  $query->addField('fqe', 'entity_id');
  $query->condition('fqe.entity_type', 'log');
  $query->condition('fqe.quick_form_id', 'farm_livestock_move_form');
  $log_alias = $query->join('log', 'l', 'fqe.entity_id = l.id');
  $query->condition($log_alias . '.type', 'farm_activity');
  $query->condition($log_alias . '.done', 0);
  $log_ids = $query->execute()->fetchCol();

  // Load logs.
  $logs = log_load_multiple($log_ids);

  // Build array of planned movement logs.
  $planned_movements = array();

  // Build select options.
  foreach ($logs as $log) {
    $timestamp = $log->timestamp;
    $label = entity_label('log', $log);
    $option = $label . ' (' . t('Planned for @date', array('@date' => strftime('%m/%d/%Y', $timestamp))) . ')';
    $planned_movements[$log->id] = check_plain($option);
  }

  $form['planned']['movement_log'] = array(
    '#type' => 'select',
    '#title' => t('Planned Logs'),
    '#options' => $planned_movements,
    '#empty_option' => '',
    '#empty_value' => '',
    '#ajax' => array(
      'callback' => 'farm_livestock_move_form_planned_movement_ajax',
    ),
  );

  // Movement fieldset.
  $form['move'] = array(
    '#prefix' => '<div id="movement">',
    '#suffix' => '</div>',
    '#type' => 'fieldset',
    '#title' => t('Record animal move'),
    '#description' => t('Use this form to record the movement of animals to an area. An activity log will be created with standard details filled in. You can also specify before/after observations of the area(s) that the animals are moving to/from.'),
  );

  // Make the form a tree.
  $form['move']['#tree'] = TRUE;

  // Start array of defaults to populate form fields.
  $defaults = array(
    'done' => TRUE,
    'date' => REQUEST_TIME,
    'assets' => NULL,
    'area' => NULL,
    'geom' => NULL,
  );

  // Update default done value to match form state.
  if (isset($form_state['values']['move']['done'])) {
    $defaults['done'] = $form_state['values']['move']['done'];
  }

  // Load a planned movement log.
  $movement_log = NULL;
  if (!empty($form_state['values']['movement_log'])) {

    // Load the log.
    $log_id = $form_state['values']['movement_log'];
    $movement_log = log_load($log_id);
  }

  $form['move']['log'] = array(
    '#value' => $movement_log,
  );

  // If a planned log was just selected, update the form with values from the log.
  if (!empty($movement_log) && isset($form_state['triggering_element']) && $form_state['triggering_element']['#name'] == 'movement_log') {

    // Update movement status.
    $defaults['done'] = $movement_log->done;
    unset($form_state['input']['move']['done']);

    // Update movement date.
    $defaults['date'] = strftime('%Y-%m-%d', $movement_log->timestamp);
    unset($form_state['input']['move']['date']);

    $log_wrapper = entity_metadata_wrapper('log', $movement_log);

    // Update default assets.
    $assets = $log_wrapper->field_farm_asset->value();

    // Build list of asset names with ids
    // eg "Name [id: 5]"
    $asset_names = array();
    foreach ($assets as $asset) {
      $asset_label = entity_label('farm_asset', $asset);
      $asset_value = $asset_label . ' [id: ' . $asset->id . ']';
      $asset_names[] = check_plain($asset_value);
    }
    $asset_string = implode(', ', $asset_names);

    // Update form asset name textfield.
    $defaults['assets'] = $asset_string;
    $form_state['values']['move']['assets'] = $asset_string;
    unset($form_state['input']['move']['assets']);

    // Update the movement info.
    if (!empty($log_wrapper->field_farm_movement)) {

      $movement_wrapper = $log_wrapper->field_farm_movement;

      // Update movement areas.
      $areas = $movement_wrapper->field_farm_move_to->value();
      if (isset($areas[0])) {
        $defaults['area'] = $areas[0]->name;
        unset($form_state['input']['move']['area']['name']);
      }

      // Update movement geometry.
      $geom = $movement_wrapper->field_farm_geofield->value();
      if (isset($geom[0])) {
        $defaults['geom'] = $geom[0]['geom'];
        unset($form_state['input']['move']['area']['geometry']['data']);
      }
    }
  }

  // Movement status.
  $form['move']['done'] = array(
    '#type' => 'checkbox',
    '#title' => t('Completed'),
    '#default_value' => $defaults['done'],
    '#ajax' => array(
      'callback' => 'farm_livestock_move_form_movement_status_ajax',
      'wrapper' => 'observations',
    ),
  );

  // Define the date format for logs.
  $date_format = 'Y-m-d H:i';

  // Movement date.
  $form['move']['date'] = array(
    '#type' => 'date_select',
    '#title' => t('Movement Date'),
    '#date_format' => $date_format,
    '#date_label_position' => 'within',
    '#date_year_range' => '-10:+3',
    '#default_value' => $defaults['date'],
    '#required' => TRUE,
  );

  // Animal/group select.
  $form['move']['assets'] = array(
    '#type' => 'textfield',
    '#title' => t('Group/animal'),
    '#description' => t('Select the group/animal that is being moved.'),
    '#autocomplete_path' => 'farm_asset/autocomplete/animal+group',
    '#default_value' => $defaults['assets'],
    '#ajax' => array(
      'callback' => 'farm_livestock_move_form_current_location_ajax',
    ),
    '#required' => TRUE,
  );

  // Load the animal or group asset
  // if an asset name has been entered.
  $assets = array();
  if (!empty($form_state['values']['move']['assets'])) {

    // Extract asset IDs and load assets.
    $asset_names = drupal_explode_tags($form_state['values']['move']['assets']);
    foreach ($asset_names as $asset_name) {
      $id = 0;
      $matches = array();
      $result = preg_match('/\\[id: ([0-9]+)\\]/', $asset_name, $matches);
      if (!empty($matches[$result])) {
        $id = $matches[$result];
      }

      // Load the asset.
      $asset = farm_asset_load($id);

      // If asset was loaded add to list of assets to save.
      if (!empty($asset)) {
        $assets[$id] = $asset;
      }
    }
  }

  $form['move']['current_location'] = array(
    '#type' => 'hidden',
    '#value' => '',
    '#prefix' => '<div id="current-location">',
    '#suffix' => '</div>',
  );

  // If a valid asset was supplied load its
  // current location and save WKT to hidden field.
  if (!empty($assets)) {

    // Load current location geometry of all assets.
    $geoms = array();
    foreach ($assets as $asset) {
      $geoms[] = farm_movement_asset_geometry($asset);
    }

    // Combine geometries.
    $combined_geometry = farm_map_combine_geoms($geoms);

    // Update current location field with WKT.
    if (!empty($combined_geometry)) {
      $form['move']['current_location']['#value'] = $combined_geometry->out('wkt');
    }
  }

  // Area reference.
  $form['move']['area']['name'] = array(
    '#type' => 'textfield',
    '#title' => t('Moving to'),
    '#description' => t('Enter the name of the area that animals are moving to. A list of existing area options will appear as you type. If the area does not exist, a new one will be created.'),
    '#autocomplete_path' => 'taxonomy/autocomplete/field_farm_area',
    '#default_value' => $defaults['area'],
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
    '#collapsed' => empty($defaults['geom']),
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
    '#default_value' => $defaults['geom'],
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
    '#prefix' => '<div id="observations">',
    '#suffix' => '</div>',
    '#type' => 'fieldset',
    '#title' => t('Observations'),
    '#description' => t('Movement must be marked as completed to record observations.'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  // Only display observation fields if the movement is marked as done.
  if ($defaults['done']) {

    // Update description text.
    $form['move']['observations']['#description'] = t('Optionally provide information about the area(s) that animals are moving out of, as well as the area(s) they are moving into.');

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

    // Load Observation Log field file directory from field info.
    $config = field_info_instance('log', 'field_farm_images', 'farm_observation');
    $file_directory = $config['settings']['file_directory'];

    // Load supported image file extensions.
    $extensions = farm_fields_file_types('image');

    // Build an upload location path.
    $scheme = variable_get('file_default_scheme', 'public');
    $upload_location = $scheme . '://' . $file_directory;

    $form['move']['observations']['post']['photos'] = array(
      '#type' => 'managed_file',
      '#title' => t('Photos'),
      '#description' => t('Optionally attach a photo to this observation log. If multiple photos need to be attached, edit the log that is created by this form.'),
      '#name' => 'files[]',
      '#progress_indicator' => 'bar',
      '#upload_validators' => array(
        'file_validate_extensions' => array($extensions),
      ),
      '#upload_location' => $upload_location,
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
      '#type' => 'managed_file',
      '#title' => t('Photos'),
      '#description' => t('Optionally attach a photo to this observation log. If multiple photos need to be attached, edit the log that is created by this form.'),
      '#name' => 'files[]',
      '#progress_indicator' => 'bar',
      '#upload_validators' => array(
        'file_validate_extensions' => array($extensions),
      ),
      '#upload_location' => $upload_location,
    );
  }

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
  // Extract asset IDs and load assets.
  $assets = array();
  $asset_names = drupal_explode_tags($form_state['values']['move']['assets']);
  foreach ($asset_names as $asset_name) {
    $id = 0;
    $matches = array();
    $result = preg_match('/\\[id: ([0-9]+)\\]/', $asset_name, $matches);
    if (!empty($matches[$result])) {
      $id = $matches[$result];
    }

    // If an ID couldn't be extracted, throw an error.
    if (empty($id)) {
      form_set_error('move][asset', t('Could not load the animal asset. Make sure the animal asset ID is included. For example: "My animal [id: 123]"'));
    }

    // Load the asset.
    $asset = farm_asset_load($id);

    // If asset was loaded add to list of assets to save.
    if (!empty($asset)) {
      $assets[$id] = $asset;
    }

    // If the asset didn't load, throw an error.
    if (empty($asset)) {
      form_set_error('move][asset', t('Could not load the animal asset. Make sure the animal name and ID are correct.'));
    }
  }

  // Save the asset to the form state.
  $form_state['storage']['assets'] = $assets;
}

/**
 * Ajax callback for farm_livestock_move_form().
 */
function farm_livestock_move_form_planned_movement_ajax($form, $form_state) {

  // Get the movement form element and CSS selector.
  $element = $form['move'];
  $selector = '#movement';

  // Build list of commands.
  $commands = array();

  // Replace the hidden field.
  $commands[] = ajax_command_replace($selector, render($element));

  // Add current location commands
  $commands = array_merge($commands, farm_livestock_move_form_current_location_commands($form, $form_state));

  // Add update movement commands
  $commands = array_merge($commands, farm_livestock_move_form_update_movement_commands($form, $form_state));

  // Return ajax commands.
  return array('#type' => 'ajax', '#commands' => $commands);
}

/**
 * Ajax callback for farm_livestock_move_form().
 */
function farm_livestock_move_form_movement_status_ajax($form, $form_state) {
  return $form['move']['observations'];
}

/**
 * Ajax callback for farm_livestock_move_form().
 */
function farm_livestock_move_form_current_location_ajax($form, $form_state) {

  // Load the current location commands.
  $commands = farm_livestock_move_form_current_location_commands($form, $form_state);

  // Return ajax commands.
  return array('#type' => 'ajax', '#commands' => $commands);
}

/**
 * Ajax callback for farm_livestock_move_form().
 */
function farm_livestock_move_form_next_location_ajax($form, $form_state) {

  // Load the update movement commands.
  $commands = farm_livestock_move_form_update_movement_commands($form, $form_state);

  // Return ajax commands.
  return array('#type' => 'ajax', '#commands' => $commands);
}

/**
 * Helper function to build current location ajax commands.
 */
function farm_livestock_move_form_current_location_commands($form, $form_state) {

  // Get the "wkt" form element and CSS selector.
  $element = $form['move']['current_location'];
  $selector = '#current-location';

  // Assemble commands...
  $commands = array();

  // Replace the hidden field.
  $commands[] = ajax_command_replace($selector, render($element));

  // Execute Javascript to add WKT to the map.
  $commands[] = array('command' => 'previewCurrentLocation');

  return $commands;
}

/**
 * Helper function to build update movement ajax commands.
 */
function farm_livestock_move_form_update_movement_commands($form, $form_state) {

  // Get the "wkt" form element and CSS selector.
  $element = $form['move']['area']['geometry']['data'];
  $selector = '#movement-geometry';

  // Assemble commands...
  $commands = array();

  // Replace the hidden field.
  $commands[] = ajax_command_replace($selector, render($element));

  // Execute Javascript to add WKT to the map.
  $commands[] = array('command' => 'updateMovementLayer');

  return $commands;
}

/**
 * Submit function for movement quick form.
 */
function farm_livestock_move_form_submit($form, &$form_state) {

  // Get the movement status.
  $done = $form_state['values']['move']['done'];

  // Get the movement timestamp.
  $timestamp = strtotime($form_state['values']['move']['date']);

  // Load the asset.
  $assets = $form_state['storage']['assets'];
  if (empty($assets)) {
    return;
  }

  // Create post grazing observation log if values were submitted.
  if (isset($form_state['values']['move']['observations']['post'])) {

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

    // Save the photo file id.
    $post_grazing_file_id = $form_state['values']['move']['observations']['post']['photos'];

    // Create post grazing observation log if measurements or photos are provided.
    // Do this before creating a movement log for the asset.
    if (!empty($post_grazing_measurements) || !empty($post_grazing_file_id)) {
      $post_grazing_log = farm_quantity_log_create('farm_observation', 'Post grazing observation', $timestamp, TRUE, $assets, $post_grazing_measurements);

      // Link the post grazing log to the quick form.
      if (function_exists('farm_quick_entity_link')) {
        farm_quick_entity_link('farm_livestock_move_form', 'log', $post_grazing_log);
      }

      // Create an entity metadata wrapper for the log.
      $log_wrapper = entity_metadata_wrapper('log', $post_grazing_log);

      // Check if a file was uploaded.
      if (!empty($post_grazing_file_id)) {

        // Load file.
        $file = file_load($post_grazing_file_id);

        // Make the storage of the file permanent.
        $file->status = FILE_STATUS_PERMANENT;

        // Save the file information.
        file_save($file);

        // Save the image with the log.
        $log_wrapper->field_farm_images[] = array('fid' => $post_grazing_file_id);
      }

      // Load current locations of all assets.
      $previous_areas = array();
      foreach ($assets as $asset) {
        $previous_areas = array_merge($previous_areas, farm_movement_asset_location($asset));
      }

      // Link post grazing logs to the area(s) animals are moving from.
      if (!empty($previous_areas)) {

        // Add areas to log.
        foreach ($previous_areas as $area) {
          $log_wrapper->field_farm_area[] = $area;
        }
      }

      // Load current location geometry of all assets.
      $geoms = array();
      foreach ($assets as $asset) {
        $geoms[] = farm_movement_asset_geometry($asset);
      }

      // Combine geometries.
      $combined_geometry = farm_map_combine_geoms($geoms);

      // Add to log.
      if (!empty($combined_geometry)) {
        $log_wrapper->field_farm_geofield->set(array(array('geom' => $combined_geometry->out('wkt'))));
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

  // Edit planned movement log.
  if (!empty($form['move']['log']['#value'])) {

    // Get the movement log.
    $movement_log = $form['move']['log']['#value'];

    // Update status.
    $movement_log->done = $done;

    // Update timestamp.
    $movement_log->timestamp = $timestamp;

    // Update asset references.
    $movement_log->field_farm_asset[LANGUAGE_NONE] = array();
    foreach ($assets as $asset) {
      $movement_log->field_farm_asset[LANGUAGE_NONE][] = array('target_id' => $asset->id);
    }

    // Update the movement info.
    $log_wrapper = entity_metadata_wrapper('log', $movement_log);
    if (!empty($log_wrapper->field_farm_movement)) {

      // Get the movement wrapper.
      $movement_wrapper = $log_wrapper->field_farm_movement;

      // Update values.
      $movement_wrapper->field_farm_move_to->set($areas);
      $movement_wrapper->field_farm_geofield->set(array(array('geom' => $geom)));
      $movement_wrapper->save();
    }

    // Save the wrapper.
    $log_wrapper->save();

    // Update log name.
    $movement_log->name = '';
    farm_movement_populate_name($movement_log);

    // Display "Updated log" status message.
    $log_name = entity_label('log', $movement_log);
    $log_uri = entity_uri('log', $movement_log);
    $log_url = url($log_uri['path']);
    drupal_set_message(
      t('Log updated: <a href="@log_url">@log_name</a>',
        array('@log_url' => $log_url, '@log_name' => $log_name))
    );

  }
  else {

    // Create activity log with asset movement to areas and specific geometry.
    $movement_log = farm_movement_create($assets, $areas, $timestamp, 'farm_activity', $done, $geom);
  }

  // Save the movement log.
  log_save($movement_log);

  // Link the movement log to the quick form.
  if (function_exists('farm_quick_entity_link')) {
    farm_quick_entity_link('farm_livestock_move_form', 'log', $movement_log);
  }

  // Create pre grazing observation log if values were submitted.
  if (isset($form_state['values']['move']['observations']['pre'])) {

    // Save the pasture height units.
    $pasture_height_units = $form_state['values']['move']['observations']['pasture_height_units'];

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

    // Save the photo file id.
    $pre_grazing_file_id = $form_state['values']['move']['observations']['pre']['photos'];

    // Create pre grazing observation log if measurements or photos are provided.
    if (!empty($pre_grazing_measurements) || !empty($pre_grazing_file_id)) {
      $pre_grazing_log = farm_quantity_log_create('farm_observation', 'Pre grazing observation', $timestamp, TRUE, $assets, $pre_grazing_measurements);

      // Link the pre grazing log to the quick form.
      if (function_exists('farm_quick_entity_link')) {
        farm_quick_entity_link('farm_livestock_move_form', 'log', $pre_grazing_log);
      }

      // Create an entity metadata wrapper for the log.
      $log_wrapper = entity_metadata_wrapper('log', $pre_grazing_log);

      // Check if a file was uploaded.
      if (!empty($pre_grazing_file_id)) {

        // Load file.
        $file = file_load($pre_grazing_file_id);

        // Make the storage of the file permanent.
        $file->status = FILE_STATUS_PERMANENT;

        // Save the file information.
        file_save($file);

        // Save the image with the log.
        $log_wrapper->field_farm_images[] = array('fid' => $pre_grazing_file_id);
      }

      // Link pre grazing logs to the area animals are moving to.
      if (!empty($areas)) {

        // Add areas to log.
        foreach ($areas as $area) {
          $log_wrapper->field_farm_area[] = $area;
        }
      }

      // Save custom geometry to pre-grazing logs.
      if (!empty($geom)) {
        $log_wrapper->field_farm_geofield->set(array(array('geom' => $geom)));
      }

      // Save the log wrapper.
      $log_wrapper->save();
    }
  }
}
