<?php

/**
 * @file
 * Provide Views data for legacy listener data streams.
 */

/**
 * Implements hook_views_data_alter().
 */
function farm_sensor_listener_views_data_alter(array &$data) {

  // Add a legacy_listener_data field to the data_stream_data table in views
  // that references the data_stream_data_legacy table.
  $data['data_stream_data']['legacy_listener_data'] = [
    'title' => t('Legacy listener data'),
    'help' => t('Legacy listener data stream data.'),
    'relationship' => [
      'base' => 'data_stream_data_legacy',
      'base field' => 'id',
      'field' => 'id',
      'id' => 'standard',
      'label' => t('Legacy listener data'),
    ],
  ];
}

/**
 * Implements hook_views_data().
 *
 * Views integration for data stream legacy listener data.
 */
function farm_sensor_listener_views_data() {

  // Save the table name.
  $data_table = 'data_stream_data_legacy';

  // Describe the {data_stream_data_storage} table.
  $data[$data_table]['table']['group'] = t('Legacy listener data stream data');

  // Data stream ID.
  $data[$data_table]['id'] = [
    'title' => t('Data stream ID'),
    'help' => t('ID of the data stream entity.'),
    'relationship' => [
      'base' => 'data_stream_data',
      'base_field' => 'id',
      'id' => 'standard',
      'label' => t('Data stream entity'),
    ],
  ];

  // Timestamp.
  $data[$data_table]['timestamp'] = [
    'title' => t('Timestamp'),
    'help' => t('Timestamp of the sensor reading.'),
    'field' => [
      'id' => 'date',
      'click sortable' => TRUE,
    ],
    'sort' => [
      'id' => 'date',
    ],
    'filter' => [
      'id' => 'date',
    ],
  ];

  // Name.
  $data[$data_table]['name'] = [
    'title' => t('Name'),
    'help' => t('Name of the value reading.'),
    'field' => [
      'id' => 'standard',
      'click sortable' => TRUE,
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      // @todo Use/create a filter that displays available "names".
      'id' => 'standard',
    ],
  ];

  // Value numerator.
  $data[$data_table]['value_numerator'] = [
    'title' => t('Sensor value numerator'),
    'help' => t('The stored numerator value of the sensor reading.'),
    'field' => [
      'id' => 'numeric',
      'click sortable' => TRUE,
    ],
    'filter' => [
      'id' => 'numeric',
    ],
    'sort' => [
      'id' => 'sort',
    ],
  ];

  // Value denominator.
  $data[$data_table]['value_denominator'] = [
    'title' => t('Sensor value denominator'),
    'help' => t('The stored denominator value of the sensor reading.'),
    'field' => [
      'id' => 'numeric',
      'click sortable' => TRUE,
    ],
    'filter' => [
      'id' => 'numeric',
    ],
    'sort' => [
      'id' => 'sort',
    ],
  ];

  // Create a new decimal column with fraction decimal handlers.
  $fraction_fields = [
    'numerator' => 'value_numerator',
    'denominator' => 'value_denominator',
  ];
  $data[$data_table]['value_decimal'] = [
    'title' => t('Sensor value (decimal)'),
    'help' => t('Decimal equivalent of sensor value.'),
    'real field' => 'value_numerator',
    'field' => [
      'id' => 'fraction',
      'additional fields' => $fraction_fields,
      'click sortable' => TRUE,
    ],
    'sort' => [
      'id' => 'fraction',
      'additional fields' => $fraction_fields,
    ],
    'filter' => [
      'id' => 'fraction',
      'additional fields' => $fraction_fields,
    ],
  ];

  return $data;
}
