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
    $output .= '<p>' . t('No weight recorded for asset') . '</p>';
  }
  else {
    $output .= '<p><strong>' . t('Current weight') . ':</strong> ' . $current_weight['value'] . ' ' . $current_weight['units'] . '</p>';

    // Load the daily live weight gain.
    $dlwg = farm_livestock_weight_dlwg($farm_asset);
    if (!empty($dlwg)) {
      // Add the dlwg markup.
      $output .= $dlwg['markup'];
    }
  };

  // Get all 'weight' logs associated with the asset.
  $weights = farm_livestock_weight_all($farm_asset);

  // Ensure there are weight logs.
  if (!empty($weights)) {

    // Simple html table of weights.
    $output .= '<h3>' . t('All weights') . '</h3>';
    $header = array(t('Date'), t('Value'), t('Units'));
    $table_data = array();

    foreach($weights as $key => $weight) {

      // Save the log.
      $log = $weight['log'];

      // Save the timestamp to the weights array for graphing.
      $weights[$key]['timestamp'] = $log->timestamp;

      // Add to table data.
      $table_data[] = array(format_date($log->timestamp), $weight['value'], $weight['units']);
    }

    // Output the table.
    $output .= theme('table', array('header' => $header, 'rows' => $table_data));

    // Create the div to hold report graphs.
    $graph_markup = array();

    // Create a graph object to pass to JS.
    $graphs = array();

    // Create a Weight Report Graph
    $graph = array(
      'name' => htmlspecialchars(entity_label('farm_asset', $farm_asset)) . ' ' . t('Weight report'),
      'id' => 'farm-report-weight-graph',
      'data' => $weights,
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
