<?php

namespace Drupal\farm_sensor_listener\Plugin\migrate\source\d7;

use Drupal\migrate\Plugin\migrate\source\SqlBase;

/**
 * Migration source for sensor listener data.
 *
 * @MigrateSource(
 *   id = "d7_sensor_listener_data",
 *   source_module = "farm_sensor_listener"
 * )
 */
class SensorListenerData extends SqlBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    $fields = [
      'id',
      'timestamp',
      'name',
      'value_numerator',
      'value_denominator',
    ];
    return $this->select('farm_sensor_data', 'fsd')
      ->fields('fsd', $fields)
      ->orderBy('fsd.id');
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [
      'id' => $this->t('Sensor asset ID.'),
      'timestamp' => $this->t('Timestamp of the sensor reading.'),
      'name' => $this->t('Sensor reading name'),
      'value_numerator' => $this->t('Value numerator'),
      'value_denominator' => $this->t('Value denominator'),
    ];
    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      'id' => ['type' => 'integer'],
      'timestamp' => ['type' => 'integer'],
      'name' => ['type' => 'string'],
    ];
  }

}
