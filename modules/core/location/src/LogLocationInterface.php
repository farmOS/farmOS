<?php

namespace Drupal\farm_location;

use Drupal\log\Entity\LogInterface;

/**
 * The interface for log location logic.
 */
interface LogLocationInterface {

  /**
   * Populate a log's geometry based on its location.
   *
   * @param \Drupal\log\Entity\LogInterface $log
   *   The Log entity.
   */
  public function populateGeometry(LogInterface $log): void;

}
