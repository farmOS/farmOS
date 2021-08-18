<?php

namespace Drupal\data_stream_notification\Plugin\DataStream\NotificationCondition;

use Drupal\Core\Condition\ConditionPluginBase;

/**
 * A base class for notification condition plugins.
 */
abstract class NotificationConditionBase extends ConditionPluginBase implements NotificationConditionInterface {

  /**
   * {@inheritdoc}
   */
  public function getConfiguration() {
    return [
      'type' => $this->getPluginId(),
    ] + $this->configuration;
  }

}
