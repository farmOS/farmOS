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
    '#required' => TRUE,
  );

  // Area reference.
  $form['move']['area']['name'] = array(
    '#type' => 'textfield',
    '#title' => t('Moving to'),
    '#description' => t('Enter the name of the area that animals are moving to. A list of existing area options will appear as you type. If the area does not exist, a new one will be created.'),
    '#autocomplete_path' => 'taxonomy/autocomplete/field_farm_area',
    '#required' => TRUE,
  );

  // Geometry
  // Add a farmOS map instance with the WKT and drawing controls.
  $wkt = '';
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
    '#wkt' => $wkt,
    '#edit' => TRUE,
  );
  $form['move']['area']['geometry']['data'] = array(
    '#type' => 'textarea',
    '#title' => t('Data'),
    '#default_value' => $wkt,
  );

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
  );
  $form['move']['observations']['post']['forage_quality'] = array(
    '#type' => 'textfield',
    '#title' => t('Relative forage quality'),
    '#description' => t('Give the forage quality a rating. This can be any number, but using a consistent scale (eg: 1-10) helps in future comparisons.'),
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
  );
  $form['move']['observations']['pre']['forage_quality'] = array(
    '#type' => 'textfield',
    '#title' => t('Relative forage quality'),
    '#description' => t('Give the forage quality a rating. This can be any number, but using a consistent scale (eg: 1-10) helps in future comparisons.'),
  );

  // Submit button.
  $form['move']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save'),
  );

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

  // If the location is available, load areas.
  $areas = array();
  if (!empty($form_state['values']['move']['area']['name'])) {
    $areas = farm_term_parse_names($form_state['values']['move']['area']['name'], 'farm_areas', TRUE);
  }

  // Create activity log with asset movement to areas.
  $movement_log = farm_movement_create($asset, $areas, $timestamp, 'farm_activity', TRUE);

  // Add special geometry if provided.
  if (!empty($form_state['values']['move']['area']['geometry']['data'])) {
    $geom = $form_state['values']['move']['area']['geometry']['data'];
    farm_map_geofield_populate($movement_log, array($geom));
  }

  // Save the movement log.
  $movement_log->save();
}
