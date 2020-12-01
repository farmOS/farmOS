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
   * Populate a log's geometry based on its location.
   *
   * @param \Drupal\log\Entity\LogInterface $log
   *   The Log entity.
   */
  public function populateGeometry(LogInterface $log): void;

}
