<?php

namespace Drupal\data_stream_notification\EventSubscriber;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\data_stream\Event\DataStreamEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event subscriber for data stream events.
 *
 * Dispatches data stream notifications.
 */
class DataStreamEventSubscriber implements EventSubscriberInterface {

  /**
   * The data stream notification entity storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $notificationStorage;

  /**
   * Constructs a DataStreamEventSubscriber object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->notificationStorage = $entity_type_manager->getStorage('data_stream_notification');
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      DataStreamEvent::DATA_RECEIVE => 'onDataReceive',
    ];
  }

  /**
   * Trigger notifications when data is received.
   *
   * @param \Drupal\data_stream\Event\DataStreamEvent $event
   *   The data stream event.
   */
  public function onDataReceive(DataStreamEvent $event) {

    // Load any notifications configured for the data stream.
    /** @var \Drupal\data_stream_notification\Entity\DataStreamNotificationInterface[] $notifications */
    $notifications = $this->notificationStorage->loadByProperties([
      'status' => TRUE,
      'data_stream' => $event->dataStream->id(),
    ]);

    // Bail if there are none.
    if (empty($notifications)) {
      return;
    }

    // Execute all notifications.
    foreach ($notifications as $notification) {
      $conditions_met = FALSE;

      // Include the notification in the event context.
      $event->context['data_stream_notification'] = $notification;

      // Save the configured operator.
      $operator = $notification->get('condition_operator') ?? 'or';

      // Test each condition plugin and collect the result.
      $results = [];
      $summaries = [];
      $collections = $notification->getPluginCollections();
      /** @var \Drupal\data_stream_notification\Plugin\DataStream\NotificationCondition\NotificationConditionInterface $condition */
      foreach ($collections['condition'] as $condition) {

        // Set the event context values on the plugin.
        $contexts = $condition->getContextDefinitions();
        foreach ($event->context as $name => $value) {
          if (array_key_exists($name, $contexts)) {
            $condition->setContextValue($name, $value);
          }
        }

        // Evaluate the condition.
        $result = $condition->execute();

        // Collect the summary of successful conditions.
        if ($result) {
          $summaries[] = $condition->summary();
        }

        // If success, and the 'or' operator, stop checking conditions.
        if ($result && $operator === 'or') {
          $conditions_met = TRUE;
          break;
        }
        $results[] = $result;
      }

      // Check if the 'and' operator passes.
      if ($operator === 'and') {
        $conditions_met = array_product($results);
      }

      $state_key = $conditions_met ? 'activate_count' : 'deactivate_count';
      $new_state = $notification->incrementState($state_key);

      // Bail if the notification is not in an active state.
      if (!$conditions_met || empty($new_state['active'])) {
        return;
      }

      // Determine if the notification delivery needs to be executed.
      // This is based on the notification's configured delivery interval.
      $execute_delivery = FALSE;
      $delivery_interval = $notification->get('delivery_interval');
      $activate_count = $new_state['activate_count'];

      // Always execute delivery when the notification first becomes active.
      if ($activate_count === 1) {
        $execute_delivery = TRUE;
      }
      // Use modulus arithmetic to determine if the delivery_interval applies.
      elseif ($delivery_interval > 0 && ($activate_count - 1) % $delivery_interval === 0) {
        $execute_delivery = TRUE;
      }

      // Bail if not executing delivery.
      if (empty($execute_delivery)) {
        return;
      }

      // Include the condition summaries.
      $event->context['condition_summaries'] = $summaries;

      // Else execute all configured delivery plugins.
      /** @var \Drupal\data_stream_notification\Plugin\DataStream\NotificationDelivery\NotificationDeliveryInterface $delivery */
      foreach ($collections['delivery'] as $delivery) {

        // Set the event context values on the plugin.
        $contexts = $delivery->getContextDefinitions();
        foreach ($event->context as $name => $value) {
          if (array_key_exists($name, $contexts)) {
            $delivery->setContextValue($name, $value);
          }
        }

        // Execute the delivery plugin.
        $delivery->execute();
      }
    }
  }

}
