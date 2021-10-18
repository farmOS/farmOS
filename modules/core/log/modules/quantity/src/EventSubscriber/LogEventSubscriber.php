<?php

namespace Drupal\farm_log_quantity\EventSubscriber;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\log\Event\LogEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
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
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   *
   * @return array
   *   The event names to listen for, and the methods that should be executed.
   */
  public static function getSubscribedEvents() {
    return [
      LogEvent::DELETE => 'logDelete',
    ];
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
