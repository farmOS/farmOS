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
    '#field_suffix' => 'inches',
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
    '#field_suffix' => 'inches',
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
 * Validate callback for milk quick form.
 */
function farm_livestock_milk_form_validate($form, &$form_state) {
  drupal_set_message('Validation not implemented yet.');
}

/**
 * Submit function for milk quick form.
 */
function farm_livestock_milk_form_submit($form, &$form_state) {
  drupal_set_message('Submission not implemented yet.');
}
