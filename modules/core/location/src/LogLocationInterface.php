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
  public function hasCustomGeometry(LogInterface $log): bool;

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
   * Populate a log's geometry based on its location.
   *
   * @param \Drupal\log\Entity\LogInterface $log
   *   The Log entity.
   */
  public function populateGeometryFromLocation(LogInterface $log): void;

}
