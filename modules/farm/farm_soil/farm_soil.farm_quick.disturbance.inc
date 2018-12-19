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

  // Return the form.
  return $form;
}

/**
 * Soil disturbance quick form validate.
 */
function farm_soil_disturbance_form_validate($form, &$form_state) {

}

/**
 * Soil disturbance quick form submit.
 */
function farm_soil_disturbance_form_submit($form, &$form_state) {

}
