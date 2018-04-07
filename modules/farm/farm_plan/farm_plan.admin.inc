<?php

/**
 * @file
 * Farm plan admin pages.
 */

/**
 * Generates the farm plan type editing form.
 *
 * @param array $form
 *   The form array.
 * @param array $form_state
 *   The form state array.
 * @param string $farm_plan_type
 *   The farm plan type.
 * @param string $op
 *   The operation being performed.
 *
 * @return array
 *   Returns a form array.
 */
function farm_plan_type_form(array $form, array &$form_state, $farm_plan_type, $op = 'edit') {

  if ($op == 'clone') {
    $farm_plan_type->label .= ' (cloned)';
    $farm_plan_type->type = '';
  }

  $form['label'] = array(
    '#title' => t('Label'),
    '#type' => 'textfield',
    '#default_value' => $farm_plan_type->label,
    '#description' => t('The human-readable name of this plan type.'),
    '#required' => TRUE,
    '#size' => 30,
  );

  // Machine-readable type name.
  $form['type'] = array(
    '#type' => 'machine_name',
    '#default_value' => !empty($farm_plan_type->type) ? $farm_plan_type->type : '',
    '#maxlength' => 32,
    '#disabled' => $farm_plan_type->isLocked() && $op != 'clone',
    '#machine_name' => array(
      'exists' => 'farm_plan_types',
      'source' => array('label'),
    ),
    '#description' => t('A unique machine-readable name for this plan type. It must only contain lowercase letters, numbers, and underscores.'),
  );

  $form['actions'] = array('#type' => 'actions');
  $form['actions']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save plan type'),
    '#weight' => 40,
  );

  if (!$farm_plan_type->isLocked() && $op != 'add' && $op != 'clone') {
    $form['actions']['delete'] = array(
      '#type' => 'submit',
      '#value' => t('Delete plan type'),
      '#weight' => 45,
      '#limit_validation_errors' => array(),
      '#submit' => array('farm_plan_type_form_submit_delete'),
    );
  }
  return $form;
}

/**
 * Submit handler for creating/editing plan types.
 *
 * @param array $form
 *   The form array.
 * @param array $form_state
 *   The form state array.
 */
function farm_plan_type_form_submit(array &$form, array &$form_state) {
  $farm_plan_type = entity_ui_form_submit_build_entity($form, $form_state);
  // Save and go back.
  farm_plan_type_save($farm_plan_type);

  // Redirect user back to list of plan types.
  $form_state['redirect'] = 'admin/config/farm/plan-types';
}

/**
 * Submit handler for deleting plan types.
 *
 * @param array $form
 *   The form array.
 * @param array $form_state
 *   The form state array.
 */
function farm_plan_type_form_submit_delete(array &$form, array &$form_state) {
  $form_state['redirect'] = 'admin/config/farm/plan-types/' . $form_state['farm_plan_type']->type . '/delete';
}

/**
 * Plan type delete form.
 *
 * @param array $form
 *   The form array.
 * @param array $form_state
 *   The form state array.
 * @param string $farm_plan_type
 *   The farm plan type.
 *
 * @return array
 *   Returns a form array.
 */
function farm_plan_type_form_delete_confirm(array $form, array &$form_state, $farm_plan_type) {
  $form['farm_plan_type'] = array(
    '#type' => 'value',
    '#value' => $farm_plan_type,
  );
  // Always provide entity id in the same form key as in the entity edit form.
  $form['farm_plan_type_id'] = array(
    '#type' => 'value',
    '#value' => entity_id('farm_plan_type', $farm_plan_type),
  );
  return confirm_form($form,
    t('Are you sure you want to delete plan type %title?', array('%title' => entity_label('farm_plan_type', $farm_plan_type))),
    'farm/plan/' . entity_id('farm_plan_type', $farm_plan_type),
    t('This action cannot be undone.'),
    t('Delete'),
    t('Cancel')
  );
}

/**
 * Plan type delete form submit handler.
 *
 * @param array $form
 *   The form array.
 * @param array $form_state
 *   The form state array.
 */
function farm_plan_type_form_delete_confirm_submit(array $form, array &$form_state) {
  $farm_plan_type = $form_state['values']['farm_plan_type'];
  farm_plan_type_delete($farm_plan_type);

  watchdog('farm_plan', '@type: deleted %title.', array('@type' => $farm_plan_type->type, '%title' => $farm_plan_type->label));
  drupal_set_message(t('@type %title has been deleted.', array('@type' => $farm_plan_type->type, '%title' => $farm_plan_type->label)));

  $form_state['redirect'] = 'admin/config/farm/plan-types';
}
