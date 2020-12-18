<?php

namespace Drupal\farm_log;

use Drupal\Core\Entity\Query\QueryInterface;

/**
 * The interface for a log query factory.
 */
interface LogQueryFactoryInterface {

  /**
   * Get a new log query object.
   *
   * @return \Drupal\Core\Entity\Query\QueryInterface
   *   A query object.
   */
  public function getQuery(): QueryInterface;

}
