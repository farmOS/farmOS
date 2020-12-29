<?php

namespace Drupal\farm_location;

use Drupal\log\Entity\LogInterface;

/**
 * The interface for log location logic.
 */
interface LogLocationInterface {

  /**
   * Check if a log references location assets.
   *
   * @param \Drupal\log\Entity\LogInterface $log
   *   The Log entity.
   *
   * @return bool
   *   Returns TRUE if the log references location assets, FALSE otherwise.
   */
  public function hasLocation(LogInterface $log): bool;

  /**
   * Check if a log has geometry.
   *
   * @param \Drupal\log\Entity\LogInterface $log
   *   The Log entity.
   *
   * @return bool
   *   Returns TRUE if the log has geometry, FALSE otherwise.
   */
  public function hasGeometry(LogInterface $log): bool;

  /**
   * Get location assets referenced by a log.
   *
   * @param \Drupal\log\Entity\LogInterface $log
   *   The Log entity.
   *
   * @return array
   *   Returns an array of assets.
   */
  public function getLocation(LogInterface $log): array;

  /**
   * Get a log's geometry.
   *
   * @param \Drupal\log\Entity\LogInterface $log
   *   The Log entity.
   *
   * @return string
   *   Returns a WKT string.
   */
  public function getGeometry(LogInterface $log): string;

  /**
   * Set a log's location.
   *
   * @param \Drupal\log\Entity\LogInterface $log
   *   The Log entity.
   * @param \Drupal\asset\Entity\AssetInterface[] $assets
   *   An array of location asset entities.
   */
  public function setLocation(LogInterface $log, array $assets): void;

  /**
   * Set a log's geometry.
   *
   * @param \Drupal\log\Entity\LogInterface $log
   *   The Log entity.
   * @param string $wkt
   *   The geometry as a WKT string.
   */
  public function setGeometry(LogInterface $log, string $wkt): void;

}
