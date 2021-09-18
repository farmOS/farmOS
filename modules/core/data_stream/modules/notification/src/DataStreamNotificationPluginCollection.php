<?php

namespace Drupal\data_stream_notification;

use Drupal\Core\Plugin\DefaultLazyPluginCollection;

/**
 * A plugin collection for notification condition and delivery plugins.
 *
 * Overrides the DefaultLazyPluginCollection to use "type" as the plugin key.
 */
class DataStreamNotificationPluginCollection extends DefaultLazyPluginCollection {

  /**
   * {@inheritdoc}
   */
  protected $pluginKey = 'type';

}
