<?php

namespace Drupal\data_stream_notification\Plugin\DataStream\NotificationDelivery;

use Drupal\Component\Plugin\ConfigurableInterface;
use Drupal\Core\Executable\ExecutableInterface;
use Drupal\Core\Plugin\ContextAwarePluginInterface;
use Drupal\Core\Plugin\PluginFormInterface;

/**
 * Provides an interface for notification delivery plugins.
 */
interface NotificationDeliveryInterface extends ConfigurableInterface, ContextAwarePluginInterface, ExecutableInterface, PluginFormInterface {

}
