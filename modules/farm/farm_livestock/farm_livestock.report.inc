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
function farm_livestock_report(FarmAsset $farm_asset) {
  // Get the asset ID.
  $farm_asset_id = entity_id('farm_asset', $farm_asset);
  $id_string = '<p>' . 'Livestock ID: ' . $farm_asset_id . '</p>' ;

  // Get the assets current weight.
  $current_weight = farm_livestock_animal_weight($farm_asset);

  // Check if the asset has a weight recorded.
  if (empty($current_weight)) {
    $weight_string = 'No weight recorded for asset';
  } else {
    $weight_string = '<p> Current Weight: ' . $current_weight['value'] . ' ' . $current_weight['units'] . '</p>';
  };

  // Get all 'weight' logs associated with the asset.
  $logs = farm_quantity_log_asset($farm_asset, 'weight', $label = NULL, $time = REQUEST_TIME, $done = TRUE, $type = NULL, $single = FALSE);

  // Simple html table of weights.
  $weights_string = '<h3> All Weights </h3>';
  $weights_string .= '<table BORDER>
    <tr>
    <th>Date</th>
    <th>Value</th>
    <th>Units</th>
    </tr>';

  // Ensure there are weight logs.
  if (!empty($logs)) {
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
      $weights_string .= '<tr><td>'. format_date($log->timestamp) . '</td><td>' . $value . '</td><td>' . $units . '</td></tr>';
    }
  }
  $weights_string .= "</table>";

  return $id_string . $weight_string . $weights_string;
}
