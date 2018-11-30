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
    '#required' => TRUE,
  );

  // Crops.
  $form['planting']['crops'] = array(
    '#type' => 'textfield',
    '#title' => t('Crops/varieties'),
    '#description' => t('What crops/varieties are included in this planting?'),
    '#autocomplete_path' => 'taxonomy/autocomplete/field_farm_crop',
    '#required' => TRUE,
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
}
