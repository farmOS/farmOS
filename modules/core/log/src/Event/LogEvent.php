<?php

namespace Drupal\farm_log\Event;

use Drupal\log\Entity\LogInterface;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Event that is fired by hook_log_OPERATION().
 */
class LogEvent extends Event {

  const PRESAVE = 'farm_log_presave';

  /**
   * The Log entity.
   *
   * @var \Drupal\log\Entity\LogInterface
   */
  public LogInterface $log;

  /**
   * Constructs the object.
   *
   * @param \Drupal\log\Entity\LogInterface $log
   *   The Log entity.
   */
  public function __construct(LogInterface $log) {
    $this->log = $log;
  }

}
