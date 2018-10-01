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
    'milk' => array(
      'label' => t('Milk'),
      'permission' => 'create farm_harvest log entities',
      'form' => 'farm_livestock_milk_form',
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
    '#description' => t('Select the mother animal. As you type, a dropdown of matching animal names will appear. Click on the one you want, and the field will be filled in with the name and asset ID (example: "Betsy [id: 123]"). The "[id: X]" portion is required.'),
    '#autocomplete_path' => 'farm_asset/autocomplete/animal',
    '#required' => TRUE,
  );

  // Father animal reference.
  $form['birth']['father'] = array(
    '#type' => 'textfield',
    '#title' => t('Father'),
    '#description' => t('Select the father animal (optional). See the mother field above for instructions.'),
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
      '#description' => t('Give the animal a name (and/or tag ID below). If the name is left blank, then it will be copied from the tag ID.'),
      '#weight' => 0,
    );

    // Tag ID.
    $form['birth']['child'][$i]['tag_id'] = array(
      '#type' => 'textfield',
      '#title' => t('Tag ID'),
      '#weight' => 0,
    );

    // Male or female.
    $form['birth']['child'][$i]['sex'] = array(
      '#type' => 'radios',
      '#title' => t('Sex'),
      '#options' => array(
        'F' => t('Female'),
        'M' => t('Male'),
      ),
      '#weight' => 10,
    );

    // Birth weight.
    $form['birth']['child'][$i]['weight'] = array(
      '#type' => 'textfield',
      '#title' => t('Birth weight'),
      '#element_validate' => array('element_validate_number'),
      '#weight' => 20,
    );
    $form['birth']['child'][$i]['units'] = array(
      '#type' => 'textfield',
      '#title' => t('Weight units'),
      '#autocomplete_path' => 'taxonomy/autocomplete/field_farm_quantity_units',
      '#weight' => 21,
    );

    // Animal description.
    $form['birth']['child'][$i]['description'] = array(
      '#type' => 'text_format',
      '#title' => t('Description'),
      '#format' => 'farm_format',
      '#weight' => 30,
    );

    // Survived.
    $form['birth']['child'][$i]['survived'] = array(
      '#type' => 'checkbox',
      '#title' => t('Survived birth'),
      '#description' => t('Uncheck this if the child did not survive. The child animal record will still be created, but will be immediately archived.'),
      '#default_value' => TRUE,
      '#weight' => 40,
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

  // Iterate through the children.
  foreach ($form_state['values']['birth']['child'] as $i => $child) {

    // Make sure that either the name or tag ID is filled in.
    if (empty($child['name']) && empty($child['tag_id'])) {
      form_set_error('birth][child][' . $i . '][name', t('The child must have a name or tag ID.'));
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

    // If the name is not set, but tag ID is, copy the tag ID to the name.
    if (empty($child['name']) && !empty($child['tag_id'])) {
      $child['name'] = $child['tag_id'];
    }

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

    // Link the asset to this quick form.
    if (function_exists('farm_quick_entity_link')) {
      farm_quick_entity_link('farm_livestock_birth_form', 'farm_asset', $child_asset);
    }

    // Set a message.
    $label = entity_label('farm_asset', $child_asset);
    $uri = entity_uri('farm_asset', $child_asset);
    drupal_set_message('Child animal created: ' . l($label, $uri['path']));

    // If the animal's weight was specified, create a weight measurement log.
    if (!empty($child['weight'])) {
      farm_livestock_weight_set($child_asset, $child['weight'], $child['units'], $timestamp);
    }
  }

  // Create a birth log. Leave the name blank so that it is auto-generated.
  $log_type = 'farm_birth';
  $log_name = '';
  $log = farm_log_create($log_type, $log_name, $timestamp, TRUE, $children);

  // Create an entity metadata wrapper for the log.
  $log_wrapper = entity_metadata_wrapper('log', $log);

  // Set the birth mother.
  $log_wrapper->field_farm_mother->set($parents['mother']->id);

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

  // Save the log.
  $log_wrapper->save();

  // Link the log to the quick form.
  if (function_exists('farm_quick_entity_link')) {
    farm_quick_entity_link('farm_livestock_birth_form', 'log', $log);
  }

  // Set a message linking to the mother animal.
  $label = entity_label('farm_asset', $parents['mother']);
  $uri = entity_uri('farm_asset', $parents['mother']);
  drupal_set_message("View the mother's animal record: " . l($label, $uri['path']));

  // Add the children to $form_state['storage'] so that other submit functions
  // can work with them.
  $form_state['storage']['children'] = $children;
}

/**
 * Form for adding milk harvest logs.
 */
function farm_livestock_milk_form($form, &$form_state) {

  // Determine the default quantity measure and units based on the system of
  // measurement (Weight in Lbs for US/Imperial, Volume in Liters for Metric).
  $system = farm_quantity_system_of_measurement();
  $default_measure = 'volume';
  $default_units = 'liters';
  if ($system == 'us') {
    $default_measure = 'weight';
    $default_units = 'lbs';
  }

  // We also remember what the measure and units were from the previous
  // submission. So if those are set, override the defaults.
  $default_measure = variable_get('farm_livestock_milk_measure', $default_measure);
  $default_units = variable_get('farm_livestock_milk_units', $default_units);

  // Wrapper fieldset.
  $form['milk'] = array(
    '#type' => 'fieldset',
    '#title' => t('Record a milk harvest'),
    '#description' => t('Use this form to record a milk harvest. A harvest log will be created with standard details filled in.'),
  );

  // Animal/group select.
  $form['milk']['asset'] = array(
    '#type' => 'textfield',
    '#title' => t('Group/animal'),
    '#description' => t('Select the group/animal that this milk came from.'),
    '#autocomplete_path' => 'farm_asset/autocomplete/animal+group',
    '#required' => TRUE,
  );

  // Quantity measure.
  $form['milk']['measure'] = array(
    '#type' => 'select',
    '#title' => t('Measure'),
    '#description' => t('Is this harvest measured in weight or in volume?'),
    '#options' => array(
      'weight' => t('Weight'),
      'volume' => t('Volume'),
    ),
    '#default_value' => $default_measure,
    '#required' => TRUE,
  );

  // Quantity value.
  $form['milk']['quantity'] = array(
    '#type' => 'textfield',
    '#title' => t('Quantity'),
    '#required' => TRUE,
    '#element_validate' => array('element_validate_integer_positive'),
  );

  // Quantity units.
  $form['milk']['units'] = array(
    '#type' => 'textfield',
    '#title' => t('Unit of measure'),
    '#description' => t('Specify what units this harvest is measured in. This will remember what you entered previously, so if you use standard units you only need to enter them the first time you use this form.'),
    '#autocomplete_path' => 'taxonomy/autocomplete/field_farm_quantity_units',
    '#default_value' => $default_units,
    '#required' => TRUE,
  );

  // Submit button.
  $form['milk']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save log'),
  );

  // Return the form.
  return $form;
}

/**
 * Validate callback for milk quick form.
 */
function farm_livestock_milk_form_validate($form, &$form_state) {

  // Validate the animal/group asset.
  if (!empty($form_state['values']['asset'])) {

    // Extract asset ID.
    $id = 0;
    $matches = array();
    $result = preg_match('/\\[id: ([0-9]+)\\]$/', $form_state['values']['asset'], $matches);
    if (!empty($matches[$result])) {
      $id = $matches[$result];
    }

    // If an ID couldn't be extracted, throw an error.
    if (empty($id)) {
      form_set_error('asset', t('Could not load the animal/group record. Make sure the asset ID is included. For example: "My animal [id: 123]"'));
    }

    // Load the asset.
    $asset = farm_asset_load($id);

    // If the asset didn't load, throw an error.
    if (empty($asset)) {
      form_set_error('asset', t('Could not load the animal/group record. Make sure the asset name and ID are correct.'));
    }

    // Save the asset to the form state.
    $form_state['storage']['asset'] = $asset;
  }
}

/**
 * Submit function for milk quick form.
 */
function farm_livestock_milk_form_submit($form, &$form_state) {

  // Remember what was entered for measure and units. This will be used as the
  // default the next time the form is loaded.
  $measure = check_plain($form_state['values']['measure']);
  $units = check_plain($form_state['values']['units']);
  variable_set('farm_livestock_milk_measure', $measure);
  variable_set('farm_livestock_milk_units', $units);

  // Get the asset and wrap it in an array.
  $asset = $form_state['storage']['asset'];
  $assets = array($asset);

  // The log type will be a harvest.
  $log_type = 'farm_harvest';

  // Set log name.
  $log_name = t('Milk @asset: @qty @units', array('@asset' => entity_label('farm_asset', $asset), '@qty' => $form_state['values']['quantity'], '@units' => $form_state['values']['units']));

  // Assemble an array of measurements.
  $measurements = array(
    array(
      'measure' => $measure,
      'value' => $form_state['values']['quantity'],
      'units' => $units,
      'label' => t('milk'),
    ),
  );

  // Create a new farm quantity log.
  farm_quantity_log_create($log_type, $log_name, REQUEST_TIME, TRUE, $assets, $measurements);
}
