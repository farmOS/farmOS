<?php

namespace Drupal\farm_migrate\Plugin\migrate\source\d7;

use Drupal\migrate\Plugin\migrate\source\SqlBase;

/**
 * Migration source for sensor listener data names.
 *
 * @MigrateSource(
 *   id = "d7_sensor_listener_data_names",
 *   source_module = "farm_sensor_listener"
 * )
 *
 * @deprecated in farm:2.2.0 and is removed from farm:3.0.0. Migrate from farmOS
 *   v1 to v2 before upgrading to farmOS v3.
 * @see https://www.drupal.org/node/3382609
 */
class SensorListenerDataNames extends SqlBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    $fields = [
      'id',
      'name',
    ];
    return $this->select('farm_sensor_data', 'fsd')
      ->fields('fsd', $fields)
      ->distinct()
      ->orderBy('fsd.id');
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [
      'id' => $this->t('Sensor asset ID.'),
      'name' => $this->t('Sensor reading name'),
    ];
    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      'id' => ['type' => 'integer'],
      'name' => ['type' => 'string'],
    ];
  }

}
