<?php

namespace Drupal\farm_ui_context\Plugin\FarmContext;

use Drupal\Component\Plugin\DependentPluginInterface;
use Drupal\Component\Plugin\PluginInspectionInterface;
use Drupal\Core\Cache\CacheableDependencyInterface;

/**
 * Defines an interface for farm context plugins.
 */
interface FarmContextInterface extends CacheableDependencyInterface, DependentPluginInterface, PluginInspectionInterface {

  /**
   * Get the context messages provided by the plugin.
   *
   * @return array
   *   An array of messages that match the plugin context. Each message is an
   *   array containing the following keys:
   *     type: The message type.
   *     message: A message string.
   *     long_message: (Optional) A longer message with more information.
   *     weight: (Optional) The weight of the message when rendering.
   */
  public function getMessages(): array;

}
