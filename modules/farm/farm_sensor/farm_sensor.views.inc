<?php

/**
 * @file
 * Farm Sensor Views data.
 */

/**
 * Implements hook_views_data().
 */
function farm_sensor_views_data() {

  // Describe the {farm_sensor} table to Views.
  $data['farm_sensor']['table']['group'] = t('Sensor');

  // Create an implicit relationship to the farm_asset table, so that when the
  // base table is farm_asset, sensor fields are automatically available.
  $data['farm_sensor']['table']['join'] = array(
    'farm_asset' => array(
      'left_field' => 'id',
      'field' => 'id',
    ),
  );

  // Sensor reading name.
  $data['farm_sensor']['type'] = array(
    'title' => t('Sensor type'),
    'help' => t('Type of sensor.'),
    'field' => array(
      'handler' => 'views_handler_field',
      'click sortable' => TRUE,
    ),
    'sort' => array(
      'handler' => 'views_handler_sort',
    ),
    'filter' => array(
      'handler' => 'views_handler_filter_string',
    ),
    'argument' => array(
      'handler' => 'views_handler_argument_string',
    ),
  );

  return $data;
}
