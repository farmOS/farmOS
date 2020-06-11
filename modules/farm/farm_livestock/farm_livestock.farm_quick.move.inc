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
