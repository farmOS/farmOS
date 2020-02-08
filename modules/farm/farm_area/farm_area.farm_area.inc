<?php
/**
 * @file
 * Farm Area hooks implemented by the Farm Area module.
 */

/**
 * Implements hook_farm_area_type_info().
 */
function farm_area_farm_area_type_info() {
  return array(
    'other' => array(
      'label' => t('Other'),
      'style' => 'blue',
      'weight' => 150,
    ),
  );
}

/**
 * Implements hook_farm_area_details().
 */
function farm_area_farm_area_details($id) {

  // Start a render array.
  $output = array();

  // Load and display the area description.
  $area = taxonomy_term_load($id);
  if (!empty($area->description)) {
    $output['description'] = array(
      '#type' => 'markup',
      '#markup' => '<p><small>' . check_markup($area->description, $area->format) . '</small></p>',
      '#weight' => -99,
    );
  }

  // Return the render array.
  return $output;
}

/**
 * Implements hook_farm_area_details().
 */
function farm_flags_farm_area_details($id) {

  // Start a render array.
  $output = array();

  // Load the area.
  $area = taxonomy_term_load($id);

  // If the area didn't load, bail.
  if (empty($area)) {
    return $output;
  }

  // Load and display any flags on the area.
  $all_flags = farm_flags_list();
  $area_flags = farm_flags_load($area);
  foreach ($area_flags as $key => $flag) {
    if (!empty($all_flags[$flag])) {
      $area_flags[$key] = farm_flags_wrap($all_flags[$flag], $flag);
    }
  }

  // If there are no flags, bail.
  if (empty($area_flags)) {
    return $output;
  }

  // Add the list of flags to the area details.
  $output[] = array(
    '#type' => 'markup',
    '#markup' => implode(' ', $area_flags),
    '#weight' => -101,
  );

  // Return the output.
  return $output;
}
