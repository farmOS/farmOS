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
function farm_livestock_weight_report(FarmAsset $farm_asset) {
  $output = '';
  // Get the asset ID.
  $farm_asset_id = entity_id('farm_asset', $farm_asset);
  $output .= '<p>' . 'Livestock ID: ' . $farm_asset_id . '</p>' ;

  // Get the assets current weight.
  $current_weight = farm_livestock_weight($farm_asset);

  // Check if the asset has a weight recorded.
  if (empty($current_weight)) {
    $output .= '<p>No weight recorded for asset</p>';
  } else {
    $output .= '<p> Current Weight: ' . $current_weight['value'] . ' ' . $current_weight['units'] . '</p>';
  };

  // Get all 'weight' logs associated with the asset.
  $logs = farm_quantity_log_asset($farm_asset, 'weight', $label = NULL, $time = REQUEST_TIME, $done = TRUE, $type = NULL, $single = FALSE);

  // Ensure there are weight logs.
  if (!empty($logs)) {
    // Simple html table of weights.
    $output .= '<h3> All Weights </h3>';
    $output .= '<table BORDER>
      <tr>
      <th>Date</th>
      <th>Value</th>
      <th>Units</th>
      </tr>';

    foreach ($logs as $log) {
      // Extract quantity data from the log.
      $data = farm_quantity_log_data($log, 'weight');

      // Iterate through the data and return the first one with a value.
      foreach ($data as $quantity) {
        if (!empty($quantity['value'])) {
          $value = $quantity['value'];
          $units = $quantity['units'];
        }
      }

      // Append the string.
      $output .= '<tr><td>'. format_date($log->timestamp) . '</td><td>' . $value . '</td><td>' . $units . '</td></tr>';
    }
    $output .= "</table>";
  }

  return $output;
}
