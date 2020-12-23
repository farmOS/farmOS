<?php

namespace Drupal\farm_location\EventSubscriber;

use Drupal\farm_location\LogLocationInterface;
use Drupal\farm_location\Traits\WktTrait;
use Drupal\farm_log\Event\LogPresaveEvent;
use Drupal\log\Entity\LogInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Perform actions on log presave.
 */
class LogPresaveEventSubscriber implements EventSubscriberInterface {

  use WktTrait;

  /**
   * Log location service.
   *
   * @var \Drupal\farm_location\LogLocationInterface
   */
  protected LogLocationInterface $logLocation;

  /**
   * LogPresaveEventSubscriber Constructor.
   *
   * @param \Drupal\farm_location\LogLocationInterface $log_location
   *   Log location service.
   */
  public function __construct(LogLocationInterface $log_location) {
    $this->logLocation = $log_location;
  }

  /**
   * The name of the asset geometry field.
   *
   * @var string
   */
  const ASSET_FIELD_GEOMETRY = 'geometry';

  /**
   * {@inheritdoc}
   *
   * @return array
   *   The event names to listen for, and the methods that should be executed.
   */
  public static function getSubscribedEvents() {
    return [
      LogPresaveEvent::PRESAVE => 'logPresave',
    ];
  }

  /**
   * Perform actions on log presave.
   *
   * @param \Drupal\farm_log\Event\LogPresaveEvent $event
   *   Config crud event.
   */
  public function logPresave(LogPresaveEvent $event) {

    // Get the log entity from the event.
    $log = $event->log;

    // If the log does not reference any location assets, we will have nothing
    // to copy from, so do nothing.
    if (!$this->logLocation->hasLocation($log)) {
      return;
    }

    // If this is a new log and it has a geometry, do nothing.
    if (empty($log->original) && $this->logLocation->hasGeometry($log)) {
      return;
    }

    // If this is an update to an existing log...
    if (!empty($log->original)) {

      // If the original log has a custom geometry, do nothing.
      if ($this->hasCustomGeometry($log->original)) {
        return;
      }

      // If the geometry has changed (and it has not been emptied), do nothing.
      $old_geometry = $this->logLocation->getGeometry($log->original);
      $new_geometry = $this->logLocation->getGeometry($log);

      // If the new geometry is not empty, do nothing.
      if ($old_geometry != $new_geometry && !empty($new_geometry)) {
        return;
      }
    }

    // Populate the log geometry from the location geometry.
    $this->populateGeometryFromLocation($log);
  }

  /**
   * Check if a log has a custom geometry.
   *
   * This is determined by checking to see if the log's geometry matches that
   * of the location assets it references. If it does not, and it is not empty,
   * them we assume it has a custom geometry.
   *
   * @param \Drupal\log\Entity\LogInterface $log
   *   The Log entity.
   *
   * @return bool
   *   Returns TRUE if it matches, FALSE otherwise.
   */
  protected function hasCustomGeometry(LogInterface $log): bool {

    // If the log's geometry is empty, then it does not have a custom geometry.
    if (!$this->logLocation->hasGeometry($log)) {
      return FALSE;
    }

    // Load location assets referenced by the log.
    $assets = $this->logLocation->getLocation($log);

    // Get the combined location asset geometry.
    $location_geometry = $this->getCombinedAssetGeometry($assets);

    // Get the log geometry.
    $log_geometry = $this->logLocation->getGeometry($log);

    // Compare the log and location geometries.
    return $log_geometry != $location_geometry;
  }

  /**
   * Populate a log's geometry based on its location.
   *
   * @param \Drupal\log\Entity\LogInterface $log
   *   The Log entity.
   */
  protected function populateGeometryFromLocation(LogInterface $log): void {

    // Load location assets referenced by the log.
    $assets = $this->logLocation->getLocation($log);

    // Get the combined location asset geometry.
    $wkt = $this->getCombinedAssetGeometry($assets);

    // If the WKT is not empty, set the log geometry.
    if (!empty($wkt)) {
      $this->logLocation->setGeometry($log, $wkt);
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
  protected function getCombinedAssetGeometry(array $assets): string {

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
