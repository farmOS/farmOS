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
    'building' => array(
      'label' => t('Building'),
      'style' => 'farm_map_style_red',
      'weight' => 0,
    ),
    'field' => array(
      'label' => t('Field'),
      'style' => 'farm_map_style_yellow',
      'weight' => 10,
    ),
    'landmark' => array(
      'label' => t('Landmark'),
      'style' => 'farm_map_style_orange',
      'weight' => 50,
    ),
    'water' => array(
      'label' => t('Water'),
      'style' => 'farm_map_style_blue',
      'weight' => 50,
    ),
    'property' => array(
      'label' => t('Property'),
      'style' => 'farm_map_style_purple',
      'weight' => 100,
    ),
    'other' => array(
      'label' => t('Other'),
      'style' => 'farm_map_style_blue',
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

  // Display the calculated area (formatted).
  $calculated_area = farm_area_calculate_area($id, TRUE);
  if (!empty($calculated_area)) {
    $output['calculated_area'] = array(
      '#type' => 'markup',
      '#markup' => '<p><small>Calculated area: ' . $calculated_area . '</small></p>',
      '#weight' => -100,
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
