<?php
/**
 * @file
 * Farm livestock quick forms.
 */

/**
 * Implements hook_farm_quick_forms().
 */
function farm_livestock_farm_quick_forms() {
  return array(
    'birth' => array(
      'label' => t('Birth'),
      'permission' => 'create farm_birth log entities',
      'form' => 'farm_livestock_birth_form',
      'file' => 'farm_livestock.farm_quick.inc',
    ),
  );
}

/**
 * Birth quick form.
 */
function farm_livestock_birth_form($form, &$form_state) {

  // Wrapper fieldset.
  $form['birth'] = array(
    '#type' => 'fieldset',
    '#title' => t('Record an animal birth'),
    '#description' => t('Use this form to record the birth of one or more animals. A new birth log will be created, along with the new child animal records.'),
    '#tree' => TRUE,
  );

  // Date select (default to now).
  $form['birth']['timestamp'] = array(
    '#type' => 'date_select',
    '#title' => t('Date'),
    '#date_format' => 'M j Y H:i',
    '#date_type' => DATE_FORMAT_UNIX,
    '#date_year_range' => '-10:+3',
    '#default_value' => REQUEST_TIME,
    '#required' => TRUE,
  );

  // Mother animal reference. Required because we need to be able to get the
  // species/breed from at least on of the parents.
  $form['birth']['mother'] = array(
    '#type' => 'textfield',
    '#title' => t('Mother'),
    '#description' => t('Select the mother animal.'),
    '#autocomplete_path' => 'farm_asset/autocomplete/animal',
    '#required' => TRUE,
  );

  // Father animal reference.
  $form['birth']['father'] = array(
    '#type' => 'textfield',
    '#title' => t('Father'),
    '#description' => t('Select the father animal (optional).'),
    '#autocomplete_path' => 'farm_asset/autocomplete/animal',
  );

  // Number of children.
  $form['birth']['children'] = array(
    '#type' => 'select',
    '#title' => t('How many children were born?'),
    '#options' => drupal_map_assoc(range(1, 15)),
    '#default_value' => 1,
    '#ajax' => array(
      'callback' => 'farm_livestock_birth_form_ajax',
      'wrapper' => 'farm-livestock-birth-children',
    ),
  );

  // Create a wrapper around all child fields, for AJAX replacement.
  $form['birth']['child'] = array(
    '#prefix' => '<div id="farm-livestock-birth-children">',
    '#suffix' => '</div>',
  );

  // Add fields for each child.
  $children = 1;
  if (!empty($form_state['values']['birth']['children'])) {
    $children = $form_state['values']['birth']['children'];
  }
  for ($i = 0; $i < $children; $i++) {

    // Fieldset for the child.
    $form['birth']['child'][$i] = array(
      '#type' => 'fieldset',
      '#title' => t('Child @number', array('@number' => $i + 1)),
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
    );

    // Animal name.
    $form['birth']['child'][$i]['name'] = array(
      '#type' => 'textfield',
      '#title' => t('Name'),
      '#required' => TRUE,
    );

    // Tag ID.
    $form['birth']['child'][$i]['tag_id'] = array(
      '#type' => 'textfield',
      '#title' => t('Tag ID'),
    );

    // Male or female.
    $form['birth']['child'][$i]['sex'] = array(
      '#type' => 'radios',
      '#title' => t('Sex'),
      '#options' => array(
        'F' => t('Female'),
        'M' => t('Male'),
      ),
    );

    // Animal description.
    $form['birth']['child'][$i]['description'] = array(
      '#type' => 'text_format',
      '#title' => t('Description'),
      '#format' => 'farm_format',
    );

    // Survived.
    $form['birth']['child'][$i]['survived'] = array(
      '#type' => 'checkbox',
      '#title' => t('Survived birth'),
      '#description' => t('Uncheck this if the child did not survive. The child animal record will still be created, but will be immediately archived.'),
      '#default_value' => TRUE,
    );
  }

  // Birth notes.
  $form['birth']['notes'] = array(
    '#type' => 'text_format',
    '#title' => t('Birth notes'),
    '#format' => 'farm_format',
  );

  // Submit button.
  $form['birth']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save birth records'),
    '#format' => 'farm_format',
  );

  // Return the form.
  return $form;
}

/**
 * Form ajax function for birth quick form.
 */
function farm_livestock_birth_form_ajax($form, &$form_state) {
  return $form['birth']['child'];
}

/**
 * Validate callback for birth quick form.
 */
function farm_livestock_birth_form_validate($form, &$form_state) {

  // Validate mother and father.
  $parents = array(
    'mother',
    'father',
  );
  foreach ($parents as $parent) {
    if (!empty($form_state['values']['birth'][$parent])) {

      // Extract asset ID.
      $id = 0;
      $matches = array();
      $result = preg_match('/\\[id: ([0-9]+)\\]$/', $form_state['values']['birth'][$parent], $matches);
      if (!empty($matches[$result])) {
        $id = $matches[$result];
      }

      // If an ID couldn't be extracted, throw an error.
      if (empty($id)) {
        form_set_error('birth][' . $parent, t('Could not load the @parent animal record. Make sure the animal asset ID is included. For example: "My animal [id: 123]"', array('@parent' => $parent)));
        continue;
      }

      // Load the asset.
      $asset = farm_asset_load($id);

      // If the asset didn't load, throw an error.
      if (empty($asset)) {
        form_set_error('birth][' . $parent, t('Could not load the @parent animal record. Make sure the animal name and ID are correct.', array('@parent' => $parent)));
        continue;
      }

      // Save the asset to the form state.
      $form_state['storage'][$parent] = $asset;
    }
  }

  // If both a mother and a father are specified, make sure they're different.
  if (!empty($form_state['storage']['mother']) && !empty($form_state['storage']['father'])) {
    if ($form_state['storage']['mother']->id == $form_state['storage']['father']->id) {
      unset($form_state['storage']['father']);
      form_set_error('birth][father', t('The mother and father cannot be the same animal.'));
    }
  }
}

/**
 * Submit callback for birth quick form.
 */
function farm_livestock_birth_form_submit($form, &$form_state) {

  // Get the birth timestamp.
  $timestamp = strtotime($form_state['values']['birth']['timestamp']);

  // Get the mother and father animals, if they exists.
  $parents = array(
    'mother' => FALSE,
    'father' => FALSE,
  );
  if (!empty($form_state['storage']['mother'])) {
    $parents['mother'] = $form_state['storage']['mother'];
  }
  if (!empty($form_state['storage']['father'])) {
    $parents['father'] = $form_state['storage']['father'];
  }

  // Iterate through the children, and build an array of their asset records.
  $children = array();
  foreach ($form_state['values']['birth']['child'] as $child) {

    // Create a new animal asset.
    $values = array(
      'type' => 'animal',
      'name' => $child['name'],
      'created' => $timestamp,
    );
    $child_asset = entity_create('farm_asset', $values);
    $child_wrapper = entity_metadata_wrapper('farm_asset', $child_asset);

    // Set the animal's birthdate to the date of the log.
    $child_wrapper->field_farm_date->set($timestamp);

    // Set the animal's tag ID, if available. Create a new ID tag
    // field_collection entity attached to the animal.
    if (!empty($child['tag_id'])) {
      $animal_tag = entity_create('field_collection_item', array('field_name' => 'field_farm_animal_tag'));
      $animal_tag->setHostEntity('farm_asset', $child_asset);
      $animal_tag_wrapper = entity_metadata_wrapper('field_collection_item', $animal_tag);
      $animal_tag_wrapper->field_farm_animal_tag_id->set($child['tag_id']);
      $animal_tag_wrapper->save();
    }

    // Set the animal's sex, if available.
    if (!empty($child['sex'])) {
      $child_wrapper->field_farm_animal_sex->set($child['sex']);
    }

    // Set the animal's description, if available.
    if (!empty($child['description']['value'])) {
      $child_wrapper->field_farm_description->set($child['description']);
    }

    // Iterate through the parents.
    foreach ($parents as $name => $parent) {

      // If an asset is not loaded, skip it.
      if (empty($parent)) {
        continue;
      }

      // Add them to the child's parents.
      $child_wrapper->field_farm_parent[] = $parent->id;

      // Load metadata wrapper.
      $parent_wrapper = entity_metadata_wrapper('farm_asset', $parent);

      // If this is the mother...
      if ($name == 'mother') {

        // Copy the species/breed to the child.
        $animal_type = $parent_wrapper->field_farm_animal_type->value();
        $child_wrapper->field_farm_animal_type->set($animal_type);
      }
    }

    // If the child did not survive, then archive them.
    if (empty($child['survived'])) {
      $child_wrapper->archived->set($timestamp);
    }

    // Save the animal asset.
    $child_wrapper->save();

    // Add it to the array.
    $children[] = $child_asset;

    // Set a message.
    $label = entity_label('farm_asset', $child_asset);
    $uri = entity_uri('farm_asset', $child_asset);
    drupal_set_message('Child animal created: ' . l($label, $uri['path']));
  }

  // Create a birth log.
  $values = array(
    'type' => 'farm_birth',
    'timestamp' => $timestamp,
  );
  $log = entity_create('log', $values);
  $log_wrapper = entity_metadata_wrapper('log', $log);

  // Set the birth mother.
  $log_wrapper->field_farm_mother->set($parents['mother']->id);

  // Add the children.
  foreach ($children as $child) {
    $log_wrapper->field_farm_asset[] = $child->id;
  }

  // Set the location (from the mother, if available).
  $movement_log = farm_movement_asset_latest_movement($parents['mother']);
  if (!empty($movement_log)) {
    $movement_log_wrapper = entity_metadata_wrapper('log', $movement_log);
    $movement_field = entity_create('field_collection_item', array('field_name' => 'field_farm_movement'));
    $movement_field->setHostEntity('log', $log);
    $movement_field_wrapper = entity_metadata_wrapper('field_collection_item', $movement_field);
    $movement_field_wrapper->field_farm_move_to->set($movement_log_wrapper->field_farm_movement->field_farm_move_to->value());
    $movement_field_wrapper->field_farm_geofield->set($movement_log_wrapper->field_farm_movement->field_farm_geofield->value());
    $movement_field_wrapper->save();
  }

  // Set the group membership (from the mother, if available).
  $membership_log = farm_group_asset_latest_membership($parents['mother']);
  if (!empty($membership_log)) {
    $membership_log_wrapper = entity_metadata_wrapper('log', $membership_log);
    $membership_field = entity_create('field_collection_item', array('field_name' => 'field_farm_membership'));
    $membership_field->setHostEntity('log', $log);
    $membership_field_wrapper = entity_metadata_wrapper('field_collection_item', $membership_field);
    $membership_field_wrapper->field_farm_group->set($membership_log_wrapper->field_farm_membership->field_farm_group->value());
    $membership_field_wrapper->save();
  }

  // Set the birth log notes, if available.
  if (!empty($form_state['values']['birth']['notes']['value'])) {
    $log_wrapper->field_farm_notes->set($form_state['values']['birth']['notes']);
  }

  // Set the log's done status.
  $log_wrapper->done->set(TRUE);

  // Set the log owner.
  global $user;
  $log_wrapper->field_farm_log_owner[] = $user;

  // Save the log.
  $log_wrapper->save();

  // Set a message that the log was created.
  $label = entity_label('log', $log);
  $uri = entity_uri('log', $log);
  drupal_set_message('Log created: ' . l($label, $uri['path']));

  // Set a message linking to the mother animal.
  $label = entity_label('farm_asset', $parents['mother']);
  $uri = entity_uri('farm_asset', $parents['mother']);
  drupal_set_message("View the mother's animal record: " . l($label, $uri['path']));
}
