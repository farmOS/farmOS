<?php

namespace Drupal\farm_migrate\Plugin\migrate\source\d7;

use Drupal\asset\Plugin\migrate\source\d7\Asset;

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
 */
class FarmAsset extends Asset {

}
