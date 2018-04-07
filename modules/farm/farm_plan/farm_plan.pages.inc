<?php

/**
 * @file
 * Farm plan pages.
 */

/**
 * Plan view callback.
 *
 * @param FarmPlan $farm_plan
 *   The farm plan entity.
 *
 * @return array
 *   Returns the entity render array.
 */
function farm_plan_view(FarmPlan $farm_plan) {

  // Set the page title.
  drupal_set_title(entity_label('farm_plan', $farm_plan));

  // Build the plan's render array.
  $build = entity_view('farm_plan', array(entity_id('farm_plan', $farm_plan) => $farm_plan), 'full');

  // Return the render array.
  return $build;
}

/**
 * Page to select plan type to add new plan.
 */
function farm_plan_add_types_page() {
  $items = array();
  foreach (farm_plan_types() as $farm_plan_type_key => $farm_plan_type) {
    if (farm_plan_access('create', $farm_plan_type)) {
      $items[] = l(entity_label('farm_plan_type', $farm_plan_type), 'farm/plan/add/' . $farm_plan_type_key);
    }
  }
  return array(
    'list' => array(
      '#theme' => 'item_list',
      '#items' => $items,
      '#title' => t('Select a type of plan to create.'),
    ),
  );
}

/**
 * Add new plan page callback.
 *
 * @param string $type
 *   The farm plan type.
 *
 * @return array
 *   Returns a form array.
 */
function farm_plan_add($type) {
  $farm_plan_type = farm_plan_types($type);

  $farm_plan = entity_create('farm_plan', array('type' => $type));
  drupal_set_title(t('Add @name', array('@name' => entity_label('farm_plan_type', $farm_plan_type))));

  $output = drupal_get_form('farm_plan_form', $farm_plan);

  return $output;
}

/**
 * Plan form.
 *
 * @param array $form
 *   The form array.
 * @param array $form_state
 *   The form state array.
 * @param FarmPlan $farm_plan
 *   The farm plan entity.
 *
 * @return array
 *   Returns a form array.
 */
function farm_plan_form(array $form, array &$form_state, FarmPlan $farm_plan) {

  $form['farm_plan'] = array(
    '#type' => 'value',
    '#value' => $farm_plan,
  );

  // Load the plan type.
  $farm_plan_type = farm_plan_type_load($farm_plan->type);

  // Plan name.
  $form['name'] = array(
    '#type' => 'textfield',
    '#title' => t('Name'),
    '#description' => t('Give this %type a name.', array('%type' => $farm_plan_type->label)),
    '#default_value' => $farm_plan->name,
    '#required' => TRUE,
    '#weight' => -100,
  );

  // Additional settings (vertical tabs at the bottom of the form).
  $form['additional_settings'] = array(
    '#type' => 'vertical_tabs',
    '#weight' => 99,
  );

  // Plan active/inactive (new plans are active by default).
  if (empty($farm_plan->id)) {
    $farm_plan->active = TRUE;
  }
  $form['plan_status'] = array(
    '#type' => 'fieldset',
    '#title' => t('Plan status'),
    '#description' => t('Mark this plan as active/inactive. Inactive plans will not show in most lists, but will be visible in archives.'),
    '#collapsible' => TRUE,
    '#group' => 'additional_settings'
  );
  $form['plan_status']['active'] = array(
    '#type' => 'checkbox',
    '#title' => t('Active'),
    '#default_value' => $farm_plan->active,
  );

  // Plan user id.
  $form['uid'] = array(
    '#type' => 'value',
    '#value' => $farm_plan->uid,
  );

  field_attach_form('farm_plan', $farm_plan, $form, $form_state);

  $submit = array();
  if (!empty($form['#submit'])) {
    $submit += $form['#submit'];
  }

  $form['actions'] = array(
    '#weight' => 100,
  );

  $form['actions']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save'),
    '#submit' => $submit + array('farm_plan_form_submit'),
  );

  // Show Delete button if allowed.
  $farm_plan_id = entity_id('farm_plan', $farm_plan);
  if (!empty($farm_plan_id) && farm_plan_access('delete', $farm_plan)) {

    // Get the destination query parameter. If it is the current path, change
    // to <front> (because the current path won't exist once the plan is
    // deleted).
    $destination = drupal_get_destination();
    if ($destination['destination'] == current_path()) {
      $destination['destination'] = '<front>';
    }
    $form['actions']['delete'] = array(
      '#type' => 'markup',
      '#markup' => l(t('Delete'), 'farm/plan/' . $farm_plan_id . '/delete', array('query' => $destination)),
    );
  }

  return $form;
}

/**
 * Plan validate handler.
 *
 * @param array $form
 *   The form array.
 * @param array $form_state
 *   The form state array.
 */
function farm_plan_form_validate(array $form, array &$form_state) {

}

/**
 * Plan submit handler.
 *
 * @param array $form
 *   The form array.
 * @param array $form_state
 *   The form state array.
 */
function farm_plan_form_submit(array $form, array &$form_state) {
  $farm_plan = $form_state['values']['farm_plan'];

  entity_form_submit_build_entity('farm_plan', $farm_plan, $form, $form_state);

  farm_plan_save($farm_plan);

  $farm_plan_uri = entity_uri('farm_plan', $farm_plan);

  $form_state['redirect'] = $farm_plan_uri['path'];

  drupal_set_message(t('Plan saved: <a href="@uri">%title</a>', array('@uri' => url($farm_plan_uri['path']), '%title' => entity_label('farm_plan', $farm_plan))));
}

/**
 * Delete confirmation form.
 *
 * @param array $form
 *   The form array.
 * @param array $form_state
 *   The form state array.
 * @param FarmPlan $farm_plan
 *   The farm plan entity.
 *
 * @return array
 *   Returns a form array.
 */
function farm_plan_delete_form(array $form, array &$form_state, FarmPlan $farm_plan) {
  $form['farm_plan'] = array(
    '#type' => 'value',
    '#value' => $farm_plan,
  );
  // Always provide entity id in the same form key as in the entity edit form.
  $form['farm_plan_type_id'] = array(
    '#type' => 'value',
    '#value' => entity_id('farm_plan', $farm_plan));
  $farm_plan_uri = entity_uri('farm_plan', $farm_plan);
  return confirm_form($form,
    t('Are you sure you want to delete %title?', array('%title' => entity_label('farm_plan', $farm_plan))),
    $farm_plan_uri['path'],
    t('This action cannot be undone.'),
    t('Delete'),
    t('Cancel')
  );
}

/**
 * Delete form submit handler.
 *
 * @param array $form
 *   The form array.
 * @param array $form_state
 *   The form state array.
 */
function farm_plan_delete_form_submit(array $form, array &$form_state) {
  $farm_plan = $form_state['values']['farm_plan'];
  farm_plan_delete($farm_plan);

  drupal_set_message(t('%title was deleted.', array('%title' => entity_label('farm_plan', $farm_plan))));

  $form_state['redirect'] = '<front>';
}
