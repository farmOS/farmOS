<?php

/**
 * @file
 * Farm Sensor Listener Views data.
 */

/**
 * Implements hook_views_data().
 */
function farm_sensor_loraserver_views_data() {

  // Describe the {farm_sensor_data} table to Views.
  $data['farm_sensor_data']['table']['group'] = t('Sensor data');

  // Create an implicit relationship to the farm_asset table, so that when the
  // base table is farm_asset, sensor fields are automatically available.
  $data['farm_sensor_data']['table']['join'] = array(
    'farm_asset' => array(
      'left_field' => 'id',
      'field' => 'id',
    ),
  );

  // Timestamp.
  $data['farm_sensor_data']['timestamp'] = array(
    'title' => t('Timestamp'),
    'help' => t('Timestamp of the sensor reading.'),
    'field' => array(
      'handler' => 'views_handler_field_date',
      'click sortable' => TRUE,
    ),
    'sort' => array(
      'handler' => 'views_handler_sort_date',
    ),
    'filter' => array(
      'handler' => 'views_handler_filter_date',
    ),
  );

  // Sensor reading name.
  $data['farm_sensor_data']['name'] = array(
    'title' => t('Sensor reading name'),
    'help' => t('Name of the sensor reading.'),
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

  // Value numerator.
  $data['farm_sensor_data']['value_numerator'] = array(
    'title' => t('Sensor value numerator'),
    'help' => t('The stored numerator value of the sensor reading.'),
    'field' => array(
      'handler' => 'views_handler_field_numeric',
      'click sortable' => TRUE,
    ),
    'filter' => array(
      'handler' => 'views_handler_filter_numeric',
    ),
    'sort' => array(
      'handler' => 'views_handler_sort',
    ),
  );

  // Value denominator.
  $data['farm_sensor_data']['value_denominator'] = array(
    'title' => t('Sensor value denominator'),
    'help' => t('The stored denominator value of the sensor reading.'),
    'field' => array(
      'handler' => 'views_handler_field_numeric',
      'click sortable' => TRUE,
    ),
    'filter' => array(
      'handler' => 'views_handler_filter_numeric',
    ),
    'sort' => array(
      'handler' => 'views_handler_sort',
    ),
  );

  // Create a new decimal column with fraction decimal handlers.
  $fraction_fields = array(
    'numerator' => 'value_numerator',
    'denominator' => 'value_denominator',
  );
  $data['farm_sensor_data']['value_decimal'] = array(
    'title' => t('Sensor value (decimal)'),
    'help' => t('Decimal equivalent of sensor value.'),
    'real field' => 'value_numerator',
    'field' => array(
      'handler' => 'fraction_handler_field',
      'additional fields' => $fraction_fields,
      'click sortable' => TRUE,
    ),
    'sort' => array(
      'handler' => 'fraction_handler_sort_decimal',
      'additional fields' => $fraction_fields,
    ),
    'filter' => array(
      'handler' => 'fraction_handler_filter_decimal',
      'additional fields' => $fraction_fields,
    ),
  );

  return $data;
}
