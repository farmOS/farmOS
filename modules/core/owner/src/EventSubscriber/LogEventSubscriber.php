<?php

namespace Drupal\farm_owner\EventSubscriber;

use Drupal\Core\Session\AccountInterface;
use Drupal\log\Event\LogEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Perform actions on log presave.
 */
class LogEventSubscriber implements EventSubscriberInterface {

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * LogEventSubscriber constructor.
   *
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The current user.
   */
  public function __construct(AccountInterface $current_user) {
    $this->currentUser = $current_user;
  }

  /**
   * {@inheritdoc}
   *
   * @return array
   *   The event names to listen for, and the methods that should be executed.
   */
  public static function getSubscribedEvents() {
    return [
      LogEvent::PRESAVE => 'setLogOwner',
    ];
  }

  /**
   * Set the log owner to the current user, if an owner isn't specified.
   *
   * @param \Drupal\log\Event\LogEvent $event
   *   The log event.
   */
  public function setLogOwner(LogEvent $event): void {

    // Get the log entity from the event.
    $log = $event->log;

    // If there is no currently logged in user, bail.
    if (empty($this->currentUser->id())) {
      return;
    }

    // If the log already has an owner, bail.
    $owners = $log->get('owner')->referencedEntities();
    if (!empty($owners)) {
      return;
    }

    // Add the current user to the log's owners.
    $log->owner[] = ['target_id' => $this->currentUser->id()];
  }

}
