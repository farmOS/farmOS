<?php

namespace Drupal\data_stream_notification\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\data_stream_notification\DataStreamNotificationPluginCollection;

/**
 * Defines the DataStreamNotification entity.
 *
 * @ConfigEntityType(
 *   id = "data_stream_notification",
 *   label = @Translation("Data stream notification"),
 *   label_collection = @Translation("Data stream notifications"),
 *   label_singular = @Translation("data stream notification"),
 *   label_plural = @Translation("data stream notifications"),
 *   label_count = @PluralTranslation(
 *     singular = "@count data stream notification",
 *     plural = "@count data stream notifications",
 *   ),
 *   handlers = {
 *     "access" = "\Drupal\entity\EntityAccessControlHandler",
 *     "form" = {
 *       "add" = "Drupal\data_stream_notification\Form\DataStreamNotificationForm",
 *       "edit" = "Drupal\data_stream_notification\Form\DataStreamNotificationForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm",
 *     },
 *     "list_builder" = "Drupal\data_stream_notification\DataStreamNotificationListBuilder",
 *     "permission_provider" = "\Drupal\entity\EntityPermissionProvider",
 *     "route_provider" = {
 *       "default" = "Drupal\entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *   },
 *   admin_permission = "administer data stream notification",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "status" = "status",
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "data_stream",
 *     "activation_threshold",
 *     "deactivation_threshold",
 *     "condition_operator",
 *     "condition",
 *     "delivery_interval",
 *     "delivery",
 *   },
 *   links = {
 *     "collection" = "/data-stream-notifications",
 *     "add-form" = "/data-stream-notifications/add",
 *     "edit-form" = "/data-stream-notifications/{data_stream_notification}/edit",
 *     "delete-form" = "/data-stream-notifications/{data_stream_notification}/delete",
 *     "enable" = "/data-stream-notifications/{data_stream_notification}/enable",
 *     "disable" = "/data-stream-notifications/{data_stream_notification}/disable",
 *   }
 * )
 *
 * @ingroup farm
 */
class DataStreamNotification extends ConfigEntityBase implements DataStreamNotificationInterface {

  /**
   * The notification ID.
   *
   * @var string
   */
  protected string $id;

  /**
   * The notification label.
   *
   * @var string
   */
  protected string $label;

  /**
   * The data stream ID.
   *
   * @var int
   */
  protected int $data_stream;

  /**
   * The activation threshold.
   *
   * @var int
   */
  protected int $activation_threshold;

  /**
   * The deactivation threshold.
   *
   * @var int
   */
  protected int $deactivation_threshold;

  /**
   * The condition operator.
   *
   * @var string
   */
  protected string $condition_operator;

  /**
   * Stores all conditions of this notification.
   *
   * @var array
   */
  protected array $condition = [];

  /**
   * The condition plugin collection.
   *
   * @var \Drupal\data_stream_notification\DataStreamNotificationPluginCollection
   */
  protected $conditionCollection;

  /**
   * The delivery_interval.
   *
   * @var int
   */
  protected int $delivery_interval;

  /**
   * Stores all deliveries of this notification.
   *
   * @var array
   */
  protected array $delivery = [];

  /**
   * The delivery plugin collection.
   *
   * @var \Drupal\data_stream_notification\DataStreamNotificationPluginCollection
   */
  protected $deliveryCollection;

  /**
   * {@inheritdoc}
   */
  public function getPluginCollections() {
    foreach (['condition', 'delivery'] as $type) {
      $name = $type . 'Collection';
      if (empty($this->$name)) {
        $this->$name = new DataStreamNotificationPluginCollection(\Drupal::service("plugin.manager.data_stream_notification_$type"), $this->$type);
      }
    }
    return [
      'condition' => $this->conditionCollection,
      'delivery' => $this->deliveryCollection,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function postDelete(EntityStorageInterface $storage, array $entities) {
    parent::postDelete($storage, $entities);

    // Clear the notification state on deletion.
    foreach ($entities as $entity) {
      \Drupal::state()->delete($entity->getStateKey());
    }
  }

  /**
   * {@inheritdoc}
   */
  public function postSave(EntityStorageInterface $storage, $update = TRUE) {
    parent::postSave($storage, $update);

    // Initialize the notification state on creation.
    if ($update === FALSE) {
      $this->resetState();
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getState(): array {
    return \Drupal::state()->get($this->getStateKey());
  }

  /**
   * {@inheritdoc}
   */
  public function isActive(): bool {
    return !empty($this->getState()['active']);
  }

  /**
   * {@inheritdoc}
   */
  public function resetState(bool $active = FALSE): array {

    // Initialize the notification state.
    $new_state = [
      'active' => $active,
      'activate_count' => $active ? 1 : 0,
      'deactivate_count' => 0,
    ];
    \Drupal::state()->set($this->getStateKey(), $new_state);
    return $new_state;
  }

  /**
   * {@inheritdoc}
   */
  public function incrementState(string $key): array {

    // Bail if an invalid key is provided.
    if (!in_array($key, ['activate_count', 'deactivate_count'])) {
      return [];
    }

    // Get the current state and save a copy as the new_state.
    $state = \Drupal::state();
    $notification_key = $this->getStateKey();
    $current_state = $state->get($notification_key);
    $new_state = $current_state;

    // Update the desired key in the new_state.
    $new_state[$key]++;

    // Check if the notification active state should be changed.
    $current_active_state = $current_state['active'];

    // Reset the other key to 0. This enforces that thresholds are consecutive.
    // Do not reset the activate_count while the notification is active.
    $other_key = $key === 'activate_count' ? 'deactivate_count' : 'activate_count';
    if (!($current_active_state && $other_key === 'activate_count')) {
      $new_state[$other_key] = 0;
    }

    // If currently active, check if the deactivation threshold was reached.
    if ($current_active_state && $new_state['deactivate_count'] >= $this->deactivation_threshold) {
      return $this->resetState(FALSE);
    }

    // If not currently active, check if the activation threshold was reached.
    elseif (!$current_active_state && $new_state['activate_count'] >= $this->activation_threshold) {
      return $this->resetState(TRUE);
    }

    // Otherwise just increment the key in the notification state.
    $state->set($notification_key, $new_state);
    return $new_state;
  }

  /**
   * Helper function to return the state key for the notification.
   *
   * @return string
   *   The state key.
   */
  protected function getStateKey() {
    return 'data_stream_notification.state.' . $this->id();
  }

}
