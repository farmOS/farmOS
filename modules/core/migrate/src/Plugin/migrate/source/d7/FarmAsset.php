<?php

namespace Drupal\farm_migrate\Plugin\migrate\source\d7;

use Drupal\asset\Plugin\migrate\source\d7\Asset;
use Drupal\farm_migrate\Traits\FarmQuickEntity;
use Drupal\migrate\Row;

/**
 * Asset source from database.
 *
 * Extends the Asset source plugin to include source properties needed for the
 * farmOS migration.
 *
 * @MigrateSource(
 *   id = "d7_farm_asset",
 *   source_module = "farm_asset"
 * )
 *
 * @deprecated in farm:2.2.0 and is removed from farm:3.0.0. Migrate from farmOS
 *   v1 to v2 before upgrading to farmOS v3.
 * @see https://www.drupal.org/node/3382609
 */
class FarmAsset extends Asset {

  use FarmQuickEntity;

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    $result = parent::prepareRow($row);
    if (!$result) {
      return FALSE;
    }

    // Prepare reference to the quick form that created this entity.
    $this->prepareQuickEntityRow($row, 'asset');

    // Return success.
    return TRUE;
  }

}
