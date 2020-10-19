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
  public function populateGeometry(LogInterface $log): void {

    // If a geometry is already defined, bail.
    if (!empty($log->{static::LOG_FIELD_GEOMETRY}->value)) {
      return;
    }

    // Load location assets referenced by the log.
    $locations = $log->{static::LOG_FIELD_LOCATION}->referencedEntities();

    // Collect all the location geometries.
    $geoms = [];
    foreach ($locations as $location) {
      if (!empty($location->{static::ASSET_FIELD_GEOMETRY}->value)) {
        $geoms[] = $location->{static::ASSET_FIELD_GEOMETRY}->value;
      }
    }

    // Combine the geometries into a single WKT string.
    $wkt = $this->combineWkt($geoms);

    // If the WKT is not empty, set the log geometry.
    if (!empty($wkt)) {
      $log->{static::LOG_FIELD_GEOMETRY}->value = $wkt;
    }
  }

}
