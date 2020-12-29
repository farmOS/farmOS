<?php

namespace Drupal\farm_log\Event;

use Drupal\log\Entity\LogInterface;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Event that is fired by hook_entity_log_presave().
 */
class LogPresaveEvent extends Event {

  const PRESAVE = 'farm_log_presave';

  /**
   * The Log entity that is being saved.
   *
   * @var \Drupal\log\Entity\LogInterface
   */
  public LogInterface $log;

  /**
   * Constructs the object.
   *
   * @param \Drupal\log\Entity\LogInterface $log
   *   The Log entity that is being saved.
   */
  public function __construct(LogInterface $log) {
    $this->log = $log;
  }

}
