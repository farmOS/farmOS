<?php

/**
 * @file
 * Farm dashboard hooks implemented by farm_ui module.
 */

/**
 * Implements hook_farm_dashboard_metrics().
 */
function farm_ui_farm_dashboard_metrics() {

  // Start an empty metrics array.
  $metrics = array();

  // Load information about farmOS entities.
  $entities = farm_ui_entities();

  // Add metrics for each asset type.
  foreach ($entities['farm_asset'] as $type => $info) {

    // Query the database for a count.
    $count = db_query('SELECT COUNT(*) FROM {farm_asset} WHERE type = :type AND archived = 0', array(':type' => $type))->fetchField();

    // If no assets exist, skip.
    if (empty($count)) {
      continue;
    }

    // Build a metric.
    $metrics[] = array(
      'label' => $info['label_plural'],
      'value' => $count,
      'link' => farm_ui_view_page_path($info['view']),
    );
  }

  // Return the metrics.
  return $metrics;
}
