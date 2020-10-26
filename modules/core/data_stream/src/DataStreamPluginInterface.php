<?php

namespace Drupal\data_stream;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Plugin\PluginFormInterface;

/**
 * An interface for DataStream Plugins.
 */
interface DataStreamPluginInterface extends ContainerFactoryPluginInterface, PluginFormInterface {

}
