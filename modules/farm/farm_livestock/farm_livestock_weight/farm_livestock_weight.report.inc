<?php

/**
 * @file
 * Farm livestock report page.
 */

/**
 * Asset Report view callback.
 *
 * @param FarmAsset $farm_asset
 *   The farm asset entity.
 *
 * @return
 *   Returns the page content.
 */
function farm_livestock_weight_individual_report(FarmAsset $farm_asset) {
  $output = '';

  // Get the assets current weight.
  $current_weight = farm_livestock_weight($farm_asset);

  // Check if the asset has a weight recorded.
  if (empty($current_weight)) {
    $output .= t('<p>No weight recorded for asset</p>');
  } else {
    $output .= t('<p><strong>Current Weight:</strong> ') . $current_weight['value'] . ' ' . $current_weight['units'] . '</p>';
    $dlwg = farm_livestock_weight_dlwg($farm_asset);
    if (!empty($dlwg)){

      $output .= t('<p><strong>Daily Liveweight Gain since @date: </strong>',array( '@date' => date('d/m/Y',$dlwg['previous_record']))) . $dlwg['value'] ." ". $dlwg['units']. t("/day");
    }
  };

  // Get all 'weight' logs associated with the asset.
  $logs = farm_livestock_weight_all($farm_asset);


  // Store all log weight data
  $log_weights = array();

  // Ensure there are weight logs.
  if (!empty($logs)) {
    // Simple html table of weights.
    $output .= t('<h3> All Weights </h3>');
    $header = array(t('Date'), t('Value'), t('Units'));
    $table_data = array();

    foreach($logs as $log){
      $table_data[] = array(format_date($log['timestamp']),$log['value'],$log['units']);

    }

    $output .= theme('table', array('header' => $header, 'rows' => $table_data));

    // Create the div to hold report graphs.
    $graph_markup = array();

    // Create a graph object to pass to JS.
    $graphs = array();

    // Create a Weight Report Graph
    $graph = array(
      'name' => $farm_asset->name . t(' Weight Report'),
      'id' => 'farm-report-weight-graph',
      'data' => $logs,
    );
    $graph_markup[] = '<div id="farm-report-weight-graph" class="farm-report-graph"></div>';
    $graphs[] = $graph;

    $settings = array(
      'farm_livestock_report' => array(
        'graphs' => $graphs,
      ),
    );

    // Add graphs to output.
    $output = '<div class="farm-report-graphs">' . implode('', $graph_markup) . '</div>' . $output;

    // Add JS and CSS to build the graphs.
    drupal_add_js($settings, 'setting');
    drupal_add_js(drupal_get_path('module', 'farm_livestock_weight') . '/farm_livestock_weight.js');
    drupal_add_js('https://cdn.plot.ly/plotly-latest.min.js', 'external');
    drupal_add_css(drupal_get_path('module', 'farm_livestock_weight') . '/farm_livestock_weight.css');
  }

  return $output;
}
