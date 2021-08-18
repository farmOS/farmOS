<?php

namespace Drupal\data_stream_notification\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\Core\Entity\EntityWithPluginCollectionInterface;

/**
 * Provides an interface for defining data stream entities.
 */
interface DataStreamNotificationInterface extends ConfigEntityInterface, EntityWithPluginCollectionInterface {

}
