<?php
/**
 * @file
 * Farm dashboard hooks implemented by farm calendar module.
 */

/**
 * Implements hook_farm_dashboard_panes().
 */
function farm_area_farm_dashboard_panes() {
  return array(
    'farm_area_map' => array(
      'title' => t('Map'),
      'callback' => 'farm_area_dashboard_map',
      'group' => 'map',
    ),
  );
}

/**
 * Date dashboard callback.
 */
function farm_area_dashboard_map() {
  $build = farm_map_build('farm_areas');
  return drupal_render($build);
}

/**
 * Implements hook_farm_dashboard_metrics().
 */
function farm_area_farm_dashboard_metrics() {

  // Start with empty metrics.
  $metrics = array();

  // Count the number of areas.
  $areas = db_query("SELECT COUNT(tid) FROM {taxonomy_term_data} t LEFT JOIN {taxonomy_vocabulary} v ON t.vid = v.vid WHERE v.machine_name = 'farm_areas'")->fetchField();
  if (!empty($areas)) {
    $metrics[] = array(
      'label' => t('Areas'),
      'value' => $areas,
      'link' => 'farm/areas',
      'weight' => 100,
    );
  }

  // Load the list of all area types and find the ones that have dashboard
  // metrics enabled.
  $area_type_info = farm_area_types();
  $area_types = array();
  foreach ($area_type_info as $area_type => $info) {
    if (!empty($info['dashboard_metric'])) {
      $area_types[] = $area_type;
    }
  }

  // Generate a metric for each area type.
  foreach ($area_types as $area_type) {

    // Get the total area of fields.
    $total_area = farm_area_calculate_area_type($area_type, TRUE);

    // If the total is empty, skip it.
    if (empty($total_area)) {
      continue;
    }

    // If the area type has a label, use that.
    $label = $area_type;
    if (!empty($area_type_info[$area_type]['label'])) {
      $label = $area_type_info[$area_type]['label'];
    }

    // Add a metric.
    $metrics[] = array(
      'label' => t('@area_type area', array('@area_type' => $label)),
      'value' => $total_area,
      'link' => 'farm/areas',
      'weight' => 101,
    );
  }

  // Return the metrics.
  return $metrics;
}
