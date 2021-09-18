<?php

namespace Drupal\data_stream_notification\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a notification condition annotation object.
 *
 * @Annotation
 */
class NotificationCondition extends Plugin {

  /**
   * The condition ID.
   *
   * @var string
   */
  public $id;

  /**
   * The condition label.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

}
