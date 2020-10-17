<?php

namespace Drupal\farm_migrate\Plugin\migrate\source\d7;

use Drupal\taxonomy\Plugin\migrate\source\d7\Term;

/**
 * Farm area source.
 *
 * @MigrateSource(
 *   id = "d7_farm_area",
 *   source_module = "taxonomy"
 * )
 */
class FarmArea extends Term {

  /**
   * {@inheritdoc}
   */
  public function query() {

    // Set the bundle to "farm_areas".
    $this->configuration['bundle'] = 'farm_areas';

    // Get the parent class query.
    $query = parent::query();

    // Join the "area type" field.
    $query->leftJoin('field_data_field_farm_area_type', 'fdffat', 'td.tid = fdffat.entity_id AND fdffat.deleted = 0');

    // If "area_type" is defined, filter by field_farm_area_type.
    if (!empty($this->configuration['area_type'])) {
      $query->condition('fdffat.field_farm_area_type_value', (array) $this->configuration['area_type'], 'IN');
    }

    // Otherwise, filter by field_farm_area_type IS NULL.
    else {
      $query->isNull('fdffat.field_farm_area_type_value');
    }

    return $query;
  }

}
