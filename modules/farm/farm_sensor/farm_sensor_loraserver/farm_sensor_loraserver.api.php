<?php

/**
 * @file
 * Hooks provided by farm_sensor_loraserver.
 *
 * This file contains no working PHP code; it exists to provide additional
 * documentation for doxygen as well as to document hooks in the standard
 * Drupal manner.
 */

/**
 * @defgroup farm_sensor_loraserver Farm sensor module integrations.
 *
 * Module integrations with the farm_sensor_loraserver module.
 */

/**
 * @defgroup farm_sensor_loraserver_hooks Farm sensor's hooks
 * @{
 * Hooks that can be implemented by other modules in order to extend farm_sensor_loraserver.
 */

/**
 * Do more with sensor data after it has been stored to the database.
 *
 * @param FarmAsset $sensor
 *   The sensor asset.
 * @param string $key
 *   The measurement name (ie: value, sensor1, sensor2, temperature, etc).
 * @param string $value
 *   The sensor value.
 *
 * @return array
 *   Returns a build array to be merged into the sensor asset view page.
 */
function hook_farm_sensor_loraserver_data($sensor, $key, $value) {

}

/**
 * @}
 */
