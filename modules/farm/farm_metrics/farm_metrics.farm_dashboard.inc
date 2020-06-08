<?php
/**
 * @file
 * Farm dashboard hooks implemented by farm_metrics module.
 */

/**
 * Implements hook_farm_dashboard_panes().
 */
function farm_metrics_farm_dashboard_panes() {
  $panes = array();
  if (user_access('access farm metrics')) {
    $panes['farm_metrics'] = array(
      'title' => t('Metrics'),
      'callback' => 'farm_metrics_dashboard_pane',
      'group' => 'metrics',
    );
  }
  return $panes;
}

/**
 * Metrics dashboard callback.
 */
function farm_metrics_dashboard_pane() {

  // Load metrics.
  $metrics = farm_metrics();

  // If no metrics are available, show a message.
  if (empty($metrics)) {
    return 'No metrics available.';
  }

  // Iterate through the metrics and build rendered metrics.
  $rendered_metrics = array();
  foreach ($metrics as $metric) {
    $rendered_metrics[] = '<li role="presentation"><a href="' . url($metric['link']) . '">' . $metric['label'] . ' <span class="badge">' . $metric['value'] . '</span></a></li>';
  }

  // Build and return the final output.
  $output = '<ul class="nav nav-pills" role="tablist">';
  $output .= implode('', $rendered_metrics);
  $output .= '</ul>';
  return $output;
}
