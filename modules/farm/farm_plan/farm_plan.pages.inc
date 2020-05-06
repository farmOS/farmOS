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
    '#group' => 'additional_settings',
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
  $plan_id = entity_id('farm_plan', $farm_plan);
  $form['farm_plan_type_id'] = array(
    '#type' => 'value',
    '#value' => $plan_id,
  );

  // List the entities that are currently linked to the plan, and make it clear
  // that they will only be unlinked, not deleted.
  $relationships = farm_plan_record_relationships();
  $entities = array();
  foreach ($relationships as $name => $info) {
    $records = farm_plan_linked_records($name, $plan_id);
    if (!empty($records)) {
      foreach ($records as $record) {
        $entities[$info['entity_type']][] = $record;
      }
    }
  }
  if (!empty($entities)) {

    // Create an array of links to the entities, with their entity type.
    $links = array();
    foreach ($entities as $entity_type => $ids) {
      $loaded_entities = entity_load($entity_type, $ids);
      foreach ($loaded_entities as $id => $entity) {
        $entity_label = entity_label($entity_type, $entity);
        $entity_uri = entity_uri($entity_type, $entity);
        $links[] = l($entity_label, $entity_uri['path']);
      }
    }
    $item_list = theme('item_list', array('items' => $links));

    // Assemble a message.
    $message = '<p>';
    $message .= t('The following records are currently linked to this plan. Deleting the plan will not delete these records, but they will no longer be linked to the plan.');
    $message .= '</p>';
    $message .= $item_list;
    drupal_set_message($message, 'warning');
  }

  // Return a Drupal confirmation form.
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

/**
 * Form for removing a record from a plan. See farm_plan_record_unlink_form()
 * for details.
 */
function farm_plan_record_remove_form($form, &$form_state, $plan, $record_type, $record_id) {
  return farm_plan_record_unlink_form($form, $form_state, $plan, $record_type, $record_id, FALSE);
}

/**
 * Form for deleting a record from a plan. See farm_plan_record_unlink_form()
 * for details.
 */
function farm_plan_record_delete_form($form, &$form_state, $plan, $record_type, $record_id) {
  return farm_plan_record_unlink_form($form, $form_state, $plan, $record_type, $record_id, TRUE);
}

/**
 * Generic form for removing a record from a plan, and optionally deleting it.
 *
 * @param array $form
 *   The form array.
 * @param array $form_state
 *   The form state array.
 * @param $plan
 *   The plan entity.
 * @param string $record_type
 *   The record type (see farm_plan_record_relationships()).
 * @param int $record_id
 *   The record ID.
 * @param bool $delete
 *   If this is set to TRUE, a "Delete" checkbox will be displayed, with a
 *   default value of TRUE, which will cause the record entity to be deleted.
 *   Defaults to FALSE.
 *
 * @return array
 *   Returns a Drupal confirmation form.
 */
function farm_plan_record_unlink_form($form, &$form_state, $plan, $record_type, $record_id, $delete = FALSE) {

  // If any of the arguments are not set, bail.
  if (empty($plan) || empty($record_type) || empty($record_id)) {
    return array();
  }

  // Save the plan, record type, and record ID in the form values.
  $form['plan'] = array(
    '#type' => 'value',
    '#value' => $plan,
  );
  $form['record_type'] = array(
    '#type' => 'value',
    '#value' => $record_type,
  );
  $form['record_id'] = array(
    '#type' => 'value',
    '#value' => $record_id,
  );

  // Get information about available plan record relationships.
  $relationships = farm_plan_record_relationships();

  // If the record type is not defined, bail.
  if (empty($relationships[$record_type])) {
    return array();
  }

  // Get the entity type. Bail if it isn't available.
  if (empty($relationships[$record_type]['entity_type'])) {
    return array();
  }
  $entity_type = $relationships[$record_type]['entity_type'];

  // Save the entity type to the form values.
  $form['entity_type'] = array(
    '#type' => 'value',
    '#value' => $entity_type,
  );

  // Load the record entity.
  $records = entity_load($entity_type, array($record_id));
  $record = reset($records);

  // If the record didn't load, bail.
  if (empty($record)) {
    return array();
  }

  // Add the record to the form values.
  $form['record'] = array(
    '#type' => 'value',
    '#value' => $record,
  );

  // If the record is an asset, we'll do some special checks and give the user
  // more options for cleaning things up.
  if ($entity_type == 'farm_asset') {

    // Load a list of logs that reference the asset.
    $query = farm_log_asset_query($record_id, 0, NULL, NULL, FALSE);
    $query->addField('ss_log', 'id');
    $result = $query->execute();
    $log_ids = array();
    foreach ($result as $row) {
      if (!empty($row->id)) {
        $log_ids[] = $row->id;
      }
    }

    // Add the log IDs to the form values.
    $form['log_ids'] = array(
      '#type' => 'value',
      '#value' => $log_ids,
    );

    // Show a list of links to the logs.
    $log_links = array();
    foreach ($log_ids as $log_id) {
      $log = log_load($log_id);
      $log_label = entity_label('log', $log);
      $log_uri = entity_uri('log', $log);
      $log_links[] = l($log_label, $log_uri['path']);
    }
    if (!empty($log_links)) {
      $form['log_links'] = array(
        '#type' => 'markup',
        '#markup' => '<p>' . t('The following logs will also be removed.') . '</p>' . theme('item_list', array('items' => $log_links)),
      );
    }

    // We also need to check constraints, for both the asset, and all the logs
    // we found above - because it's possible that other things are referencing
    // them. It is also possible that the logs reference multiple assets. It can
    // get complicated quickly, so we take a simple approach with this form. If
    // constraints exist beyond the log-asset references we know about, we will
    // prevent deletion and summarize them, so the user can make a decision and
    // clean them up manually if they want.
    $constraints = array();

    // Check to see if any constraints exist on the asset. Filter out the logs
    // we found above because we will handle those automatically in the submit
    // function. Also filter out the link between the plan and the asset.
    $asset_constraints = farm_constraint_list('farm_asset', $record->type, $record_id);
    foreach ($asset_constraints as $constraint) {

      // If the constraint type is "field_reference" and it is a log that we
      // already found above, skip it.
      if (!empty($constraint['constraint']) && !empty($constraint['field']) && !empty($constraint['entity_type']) && !empty($constraint['entity_id'])) {
        if ($constraint['constraint'] == 'field_reference' && $constraint['field'] == 'field_farm_asset' && $constraint['entity_type'] == 'log' && in_array($constraint['entity_id'], $log_ids)) {
          continue;
        }
      }

      // If the constraint type is "table_reference" and it's the table that
      // defines the relationship between the plan and asset, skip it.
      if (!empty($constraint['constraint']) && !empty($constraint['table'])) {
        if ($constraint['constraint'] == 'table_reference' && $constraint['table'] == $relationships[$record_type]['table']) {
          continue;
        }
      }

      // Otherwise, add the constraint to the filtered list.
      $constraints[] = $constraint;
    }

    // Check to see if any constraints exist on the logs. Filter out any links
    // between the plan and the log.
    foreach ($log_ids as $log_id) {
      $log = log_load($log_id);
      $log_constraints = farm_constraint_list('log', $log->type, $log_id);
      foreach ($log_constraints as $constraint) {

        // If the constraint type is "table_reference" and the table is
        // "farm_plan_log" OR the same table as the asset relationship table
        // (indicating that it is a single table that references multiple
        // different types of records), skip it.
        if (!empty($constraint['constraint']) && !empty($constraint['table'])) {
          if ($constraint['constraint'] == 'table_reference' && in_array($constraint['table'], array('farm_plan_log', $relationships[$record_type]['table']))) {
            continue;
          }
        }

        // Otherwise, add the constraint to the filtered list.
        $constraints[] = $constraint;
      }

      // Also check to see if the log references other assets. If so, add a
      // simple boolean constraint.
      if (!empty($log->field_farm_asset[LANGUAGE_NONE]) && count($log->field_farm_asset[LANGUAGE_NONE]) > 1) {
        $constraints[] = TRUE;
      }
    }

    // If constraints do exist, prevent deletion and add explain why.
    if ($constraints) {
      $delete = FALSE;
      $form['no_delete'] = array(
        '#type' => 'markup',
        '#markup' => '<p>' . t('The record(s) will be unlinked from this plan, but they will not be deleted because they are referenced by other records.') . '</p>',
      );
    }
  }

  // Get the title of the plan and record.
  $plan_name = entity_label('farm_plan', $plan);
  $record_name = entity_label($entity_type, $record);

  // Get the path to the plan.
  $plan_uri = entity_uri('farm_plan', $plan);
  $plan_path = $plan_uri['path'];

  // Add a checkbox to delete the entity.
  if (!empty($delete)) {
    $form['delete'] = array(
      '#type' => 'checkbox',
      '#title' => t('Delete the record(s)'),
      '#description' => t('If this is checked, the record(s) will be permanently deleted. Otherwise, they will just be unlinked from the plan.'),
      '#default_value' => TRUE,
    );
  } else {
    $form['delete'] = array(
      '#type' => 'value',
      '#value' => FALSE,
    );
  }

  // Explicitly add the submit handler, because this form function may be called
  // by farm_plan_record_remove_form() or farm_plan_record_delete_form() above.
  $form['#submit'][] = 'farm_plan_record_unlink_form_submit';

  // Return a Drupal confirmation form.
  return confirm_form($form,
    t('Are you sure you want to remove %record from the plan %plan?', array('%record' => $record_name, '%plan' => $plan_name)),
    $plan_path,
    '',
    t('Remove'),
    t('Cancel')
  );
}

/**
 * Unlink record form submit handler.
 *
 * @param array $form
 *   The form array.
 * @param array $form_state
 *   The form state array.
 */
function farm_plan_record_unlink_form_submit(array $form, array &$form_state) {

  // Get the plan from the submitted values.
  $plan = $form_state['values']['plan'];

  // Get the record type and ID from the submitted values.
  $record_type = $form_state['values']['record_type'];
  $record_id = $form_state['values']['record_id'];

  // Unlink the record from the plan.
  farm_plan_unlink_record($record_type, $plan->id, $record_id);

  // Delete the record, if that was explicitly requested.
  if (!empty($form_state['values']['delete'])) {
    $entity_type = $form_state['values']['entity_type'];
    entity_delete($entity_type, $record_id);
  }

  // If log IDs were also marked for removal, unlink and optionally delete them
  // as well.
  if (!empty($form_state['values']['log_ids'])) {

    // Get information about available plan record relationships.
    $relationships = farm_plan_record_relationships();

    // Figure out which record types link to logs.
    $log_record_types = array();
    foreach ($relationships as $record_type => $info) {
      if ($info['entity_type'] == 'log') {
        $log_record_types[] = $record_type;
      }
    }

    // Iterate through the log IDs.
    foreach ($form_state['values']['log_ids'] as $log_id) {

      // Unlink the log from the plan.
      foreach ($log_record_types as $record_type) {
        farm_plan_unlink_record($record_type, $plan->id, $log_id);
      }

      // Delete the record, if that was explicitly requested.
      if (!empty($form_state['values']['delete'])) {
        entity_delete('log', $log_id);
      }
    }
  }

  // Get the path to the plan.
  $plan_uri = entity_uri('farm_plan', $plan);
  $plan_path = $plan_uri['path'];

  // Redirect to the plan.
  $form_state['redirect'] = $plan_path;
}
