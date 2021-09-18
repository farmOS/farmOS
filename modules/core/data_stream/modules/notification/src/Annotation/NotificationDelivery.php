<?php

namespace Drupal\data_stream_notification\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a notification delivery annotation object.
 *
 * @Annotation
 */
class NotificationDelivery extends Plugin {

  /**
   * The delivery ID.
   *
   * @var string
   */
  public $id;

  /**
   * The delivery label.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

}
