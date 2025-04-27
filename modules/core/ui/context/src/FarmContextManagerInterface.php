<?php

namespace Drupal\farm_ui_context;

use Drupal\Core\Plugin\Context\ContextAwarePluginManagerInterface;
use Drupal\Core\Plugin\FilteredPluginManagerInterface;

/**
 * Provides an interface the FarmContextManager.
 */
interface FarmContextManagerInterface extends ContextAwarePluginManagerInterface, FilteredPluginManagerInterface {

  /**
   * Return messages provided by farm context plugins.
   *
   * @param string $consumer
   *   A string identifying the consumer of these farm context messages.
   * @param array|null $contexts
   *   (optional) Either an array of contexts to use for filtering, or NULL to
   *   not filter by contexts.
   * @param array $extra
   *   (optional) An associative array containing additional information
   *   provided by the code requesting the filtered definitions.
   *
   * @return array
   *   An array of messages provided by farm context plugins.
   *
   * @see \Drupal\farm_ui_context\Plugin\FarmContext\FarmContextInterface::getMessages()
   */
  public function getMessages(string $consumer, array $contexts = NULL, array $extra = []): array;

}
