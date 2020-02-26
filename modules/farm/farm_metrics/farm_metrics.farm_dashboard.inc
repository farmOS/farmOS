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

  // Ask modules for metrics.
  $metrics = module_invoke_all('farm_metrics');
  if (empty($metrics)) {
    return 'No metrics available.';
  }

  // Make sure all metrics have defaults set.
  $defaults = array(
    'label' => '',
    'value' => '0',
    'link' => 'farm',
    'weight' => 0,
  );
  foreach ($metrics as $key => $metric) {
    $metrics[$key] = array_merge($defaults, $metric);
  }

  // Sort the metrics by weight ascending, value descending.
  $weight_index = array();
  $value_index = array();
  foreach ($metrics as $key => $row) {
    $weight_index[$key]  = $row['weight'];
    $value_index[$key] = $row['value'];
  }
  array_multisort($weight_index, SORT_ASC, $value_index, SORT_DESC, $metrics);

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
