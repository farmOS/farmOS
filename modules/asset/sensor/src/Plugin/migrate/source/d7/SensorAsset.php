<?php

namespace Drupal\farm_sensor\Plugin\migrate\source\d7;

use Drupal\asset\Plugin\migrate\source\d7\Asset;
use Drupal\migrate\Row;

/**
 * Migration source for the d7 sensor asset.
 *
 * Extends the Asset source to include the sensor_type and sensor_settings
 * fields as source properties for the migration.
 *
 * @MigrateSource(
 *   id = "d7_sensor_asset",
 *   source_module = "farm_sensor"
 * )
 */
class SensorAsset extends Asset {

  /**
   * {@inheritdoc}
   */
  public function query() {

    // Get the parent query.
    $query = parent::query();

    // Join in the farm_sensor table.
    $query->join('farm_sensor', 'fs', 'fa.id = fs.id');

    // Add sensor fields aliased with correct name.
    $query->addField('fs', 'type', 'sensor_type');
    $query->addField('fs', 'settings', 'sensor_settings');

    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {

    // Get the sensor_settings field.
    $settings = $row->getSourceProperty('sensor_settings');
    if (!empty($settings)) {

      // Unserialize the sensor_settings field.
      $settings_array = unserialize($settings);

      // Re-set the source property value.
      $row->setSourceProperty('sensor_settings', $settings_array);
    }

    return parent::prepareRow($row);
  }

}
