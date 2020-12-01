<?php

namespace Drupal\farm_location;

use Drupal\farm_location\Traits\WktTrait;
use Drupal\log\Entity\LogInterface;

/**
 * Log location logic.
 */
class LogLocation implements LogLocationInterface {

  use WktTrait;

  /**
   * The name of the log location field.
   *
   * @var string
   */
  const LOG_FIELD_LOCATION = 'location';

  /**
   * The name of the log geometry field.
   *
   * @var string
   */
  const LOG_FIELD_GEOMETRY = 'geometry';

  /**
   * The name of the asset geometry field.
   *
   * @var string
   */
  const ASSET_FIELD_GEOMETRY = 'geometry';

  /**
   * {@inheritdoc}
   */
  public function hasLocation(LogInterface $log): bool {
    return !$log->get(static::LOG_FIELD_LOCATION)->isEmpty();
  }

  /**
   * {@inheritdoc}
   */
  public function hasGeometry(LogInterface $log): bool {
    return !$log->get(static::LOG_FIELD_GEOMETRY)->isEmpty();
  }

  /**
   * {@inheritdoc}
   */
  public function populateGeometry(LogInterface $log): void {

    // Load location assets referenced by the log.
    $locations = $log->{static::LOG_FIELD_LOCATION}->referencedEntities();

    // Get the combined location asset geometry.
    $wkt = $this->getCombinedAssetGeometry($locations);

    // If the WKT is not empty, set the log geometry.
    if (!empty($wkt)) {
      $log->{static::LOG_FIELD_GEOMETRY}->value = $wkt;
    }
  }

  /**
   * Load a combined set of location asset geometries.
   *
   * @param \Drupal\asset\Entity\AssetInterface[] $assets
   *   An array of location assets.
   *
   * @return string
   *   Returns a WKT string of the combined asset geometries.
   */
  protected function getCombinedAssetGeometry(array $assets) {

    // Collect all the location geometries.
    $geoms = [];
    foreach ($assets as $asset) {
      if (!empty($asset->{static::ASSET_FIELD_GEOMETRY}->value)) {
        $geoms[] = $asset->{static::ASSET_FIELD_GEOMETRY}->value;
      }
    }

    // Combine the geometries into a single WKT string.
    $wkt = $this->combineWkt($geoms);

    return $wkt;
  }

}
