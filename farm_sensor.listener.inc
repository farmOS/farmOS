<?php
/**
 * @file
 * Code for the Farm Sensor listener callback.
 */

/**
 * Callback function for receiving JSON over HTTP and storing data to the {farm_sensor_data} table.
 *
 * JSON should be in the following format:
 *   {
 *     'id': 123,
 *     'timestamp: 1234567890,
 *     'name': 'sensor1',
 *     'value': 76.5
 *   }
 */
function farm_sensor_listener_page_callback() {

  // Pull the data from the request.
  $data = drupal_json_decode(file_get_contents("php://input"));

  // Ensure that all the necessary keys are set.
  if (empty($data['id']) || empty($data['timestamp']) || empty($data['name']) || !isset($data['value'])) {
    return;
  }

  // Look up the sensor by asset id.
  $sensor = farm_asset_load($data['id']);

  // If no asset was found, bail.
  if (empty($sensor)) {
    return;
  }

  // Enter the reading into the {farm_sensor_data} table.
  drupal_write_record('farm_sensor_data', $data);
}
