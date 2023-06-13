<?php

namespace Drupal\data_stream_notification\Plugin\DataStream\NotificationCondition;

use Drupal\Core\Condition\ConditionInterface;
use Drupal\Core\Plugin\ContextAwarePluginInterface;

/**
 * Provides an interface for notification condition plugins.
 */
interface NotificationConditionInterface extends ConditionInterface, ContextAwarePluginInterface {

}
