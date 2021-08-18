<?php

namespace Drupal\data_stream_notification\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
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
 *     "condition_operator",
 *     "condition",
 *     "delivery",
 *   },
 *   links = {
 *     "add-form" = "/data-stream-notifications/add",
 *     "edit-form" = "/data-stream-notifications/{data_stream_notification}/edit",
 *     "delete-form" = "/data-stream-notifications/{data_stream_notification}/delete",
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

}
