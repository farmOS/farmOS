<?php

namespace Drupal\data_stream_notification;

use Drupal\Component\Plugin\PluginManagerInterface;
use Drupal\Core\Executable\ExecutableManagerInterface;

/**
 * Provides an interface for the notification condition plugin manager.
 */
interface NotificationConditionManagerInterface extends PluginManagerInterface, ExecutableManagerInterface {

}
