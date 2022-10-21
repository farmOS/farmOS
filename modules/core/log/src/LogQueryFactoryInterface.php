<?php

namespace Drupal\farm_log;

use Drupal\Core\Entity\Query\QueryInterface;

/**
 * The interface for a log query factory.
 *
 * @internal
 */
interface LogQueryFactoryInterface {

  /**
   * Get a new log query object.
   *
   * @param array $options
   *   An array of options for building the query.
   *
   * @return \Drupal\Core\Entity\Query\QueryInterface
   *   A query object.
   */
  public function getQuery(array $options = []): QueryInterface;

}
