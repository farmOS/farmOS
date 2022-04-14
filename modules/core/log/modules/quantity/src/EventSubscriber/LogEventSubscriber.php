<?php

namespace Drupal\farm_log_quantity\EventSubscriber;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\log\Event\LogEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Perform actions on log presave.
 */
class LogEventSubscriber implements EventSubscriberInterface {

  /**
   * Entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected EntityTypeManagerInterface $entityTypeManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   *
   * @return array
   *   The event names to listen for, and the methods that should be executed.
   */
  public static function getSubscribedEvents() {
    return [
      LogEvent::CLONE => 'logClone',
      LogEvent::DELETE => 'logDelete',
    ];
  }

  /**
   * Perform actions on log clone.
   *
   * @param \Drupal\log\Event\LogEvent $event
   *   The log event.
   */
  public function logClone(LogEvent $event) {

    // Get the log entity from the event.
    $log = $event->log;

    // Bail if the log does not reference any quantities.
    if ($log->get('quantity')->isEmpty()) {
      return;
    }

    // Duplicate each referenced quantity.
    $new_quantities = [];
    /** @var \Drupal\quantity\Entity\QuantityInterface $quantity */
    foreach ($log->get('quantity')->referencedEntities() as $quantity) {
      $duplicate_quantity = $quantity->createDuplicate();
      $new_quantities[] = $duplicate_quantity;
    }

    // Update the log to reference the new duplicated quantities.
    $log->set('quantity', $new_quantities);
  }

  /**
   * Perform actions on log delete.
   *
   * @param \Drupal\log\Event\LogEvent $event
   *   Config crud event.
   */
  public function logDelete(LogEvent $event) {

    // Get the log entity from the event.
    $log = $event->log;

    // If the log doesn't have a quantity field, bail.
    if (!$log->hasField('quantity')) {
      return;
    }

    // Get any quantities the log references.
    $quantities = $log->quantity->referencedEntities();

    // Delete quantity entities.
    if (!empty($quantities)) {
      $this->entityTypeManager->getStorage('quantity')->delete($quantities);
    }
  }

}
