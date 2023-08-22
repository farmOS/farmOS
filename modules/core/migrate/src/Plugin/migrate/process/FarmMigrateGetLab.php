<?php

namespace Drupal\farm_migrate\Plugin\migrate\process;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;

/**
 * Gets the testing lab from a soil/water test log.
 *
 * @MigrateProcessPlugin(
 *   id = "get_lab"
 * )
 *
 * @deprecated in farm:2.2.0 and is removed from farm:3.0.0. Migrate from farmOS
 *   v1 to v2 before upgrading to farmOS v3.
 * @see https://www.drupal.org/node/3382609
 */
class FarmMigrateGetLab extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {

    // First try to get a soil lab.
    $lab = $row->get('field_farm_soil_lab');

    // If that failed, try to get a water lab.
    if (empty($lab)) {
      $lab = $row->get('field_farm_water_lab');
    }

    // If a lab was found, return it.
    if (!empty($lab[0]['value'])) {
      return $lab[0]['value'];
    }
    return '';
  }

}
