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

  // Number of crops/varieties.
  $crop_count_default = 1;
  $form['planting']['crop_count'] = array(
    '#type' => 'select',
    '#title' => t('If this is a mix, how many crops/varieties are included?'),
    '#options' => drupal_map_assoc(range(1, 15)),
    '#default_value' => $crop_count_default,
    '#ajax' => array(
      'callback' => 'farm_crop_planting_quick_form_crops_ajax',
      'wrapper' => 'planting-crops',
    ),
  );

  // Create a field for each crop/variety.
  $crop_count = $crop_count_default;
  if (!empty($form_state['values']['planting']['crop_count'])) {
    $crop_count = $form_state['values']['planting']['crop_count'];
  }
  for ($i=0; $i<$crop_count; $i++) {
    $form['planting']['crops'][$i] = array(
      '#type' => 'textfield',
      '#title' => t('Crop/variety') . ' ' . ($i+1),
      '#autocomplete_path' => 'taxonomy/autocomplete/field_farm_crop',
      '#required' => TRUE,
    );
  }

  // Add the 'planting-crops' wrapper div around the crops field(s), so that it
  // can be replaced via AJAX.
  $form['planting']['crops']['#prefix'] = '<div id="planting-crops">';
  $form['planting']['crops']['#suffix'] = '</div>';

  // Submit button.
  $form['planting']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Create planting'),
  );

  // Return the form.
  return $form;
}

/**
 * Planting quick form crops ajax callback.
 */
function farm_crop_planting_quick_form_crops_ajax($form, &$form_state) {
  return $form['planting']['crops'];
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

  // Load/create the season term.
  $season_term = farm_term($season, 'farm_season');

  // Get the crop(s) and load/create terms for each.
  $crops = array();
  $crop_tags = $form_state['values']['planting']['crops'];
  foreach ($crop_tags as $tag) {
    $term = farm_term($tag, 'farm_crops');
    if (!empty($term)) {
      $crops[] = $term;
    }
  }

  // The planting will be named based on the season and crop.
  $planting_name_parts = array(
    $season,
    implode(', ', $crop_tags),
  );

  // Create a new planting asset.
  $values = array(
    'type' => 'planting',
    'name' => implode(' ', $planting_name_parts),
  );
  $planting_asset = entity_create('farm_asset', $values);
  $planting_wrapper = entity_metadata_wrapper('farm_asset', $planting_asset);

  // Add the season.
  $planting_wrapper->field_farm_season = $season_term;

  // Add the crop(s).
  foreach ($crops as $crop) {
    $planting_wrapper->field_farm_crop[] = $crop;
  }

  // Save the planting.
  $planting_wrapper->save();

  // Link the asset to this quick form.
  if (function_exists('farm_quick_entity_link')) {
    farm_quick_entity_link('farm_crop_planting_form', 'farm_asset', $planting_asset);
  }

  // Set a message.
  $label = entity_label('farm_asset', $planting_asset);
  $uri = entity_uri('farm_asset', $planting_asset);
  drupal_set_message('Planting created: ' . l($label, $uri['path']));
}
