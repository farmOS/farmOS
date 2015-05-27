<?php

/**
 * @file
 * Hooks provided by farm_sensor.
 *
 * This file contains no working PHP code; it exists to provide additional
 * documentation for doxygen as well as to document hooks in the standard
 * Drupal manner.
 */

/**
 * @defgroup farm_sensor Farm sensor module integrations.
 *
 * Module integrations with the farm_sensor module.
 */

/**
 * @defgroup farm_sensor_hooks Farm sensor's hooks
 * @{
 * Hooks that can be implemented by other modules in order to extend farm_sensor.
 */

/**
 * Provide information about farm sensor types.
 *
 * @return array
 *   Returns an array of sensor type information.
 */
function hook_farm_sensor_type_info() {
  return array(
    'mysensor' => array(
      'label' => t('My Sensor'),
      'description' => t('Description of my sensor.'),
      'form' => 'my_sensor_settings_form_callback',
    ),
  );
}

/**
 * @}
 */
