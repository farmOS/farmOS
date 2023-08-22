<?php

namespace Drupal\farm_migrate\Plugin\migrate\source\d7;

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
 *
 * @deprecated in farm:2.2.0 and is removed from farm:3.0.0. Migrate from farmOS
 *   v1 to v2 before upgrading to farmOS v3.
 * @see https://www.drupal.org/node/3382609
 *
 * @phpstan-ignore-next-line
 */
class SensorAsset extends FarmAsset {

  /**
   * {@inheritdoc}
   */
  public function query() {

    // Get the parent query.
    $query = parent::query();

    // Join in the farm_sensor table.
    $query->join('farm_sensor', 'fs', 'fa.id = fs.id');

    // Limit by the sensor type.
    if (isset($this->configuration['sensor_type'])) {

      // Specify the sensor type.
      if (!empty($this->configuration['sensor_type'])) {
        $query->condition('fs.type', (array) $this->configuration['sensor_type'], 'IN');
      }

      // Allow empty sensor type.
      else {
        $query->where("fs.type = '' OR fs.type IS NULL");
      }
    }

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
      $settings_array = unserialize($settings, ['allowed_classes' => FALSE]);

      // Re-set the source property value.
      $row->setSourceProperty('sensor_settings', $settings_array);
    }

    return parent::prepareRow($row);
  }

}
