<?php

namespace Drupal\farm_ui_context;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\ContextAwarePluginInterface;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Plugin\FilteredPluginManagerTrait;

/**
 * Managed discovery and instantiation of FarmContext plugins.
 *
 * @see \Drupal\farm_ui_context\Plugin\FarmContext\FarmContextInterface
 */
class FarmContextManager extends DefaultPluginManager implements FarmContextManagerInterface {

  use FilteredPluginManagerTrait;

  /**
   * Constructs a FarmContextManager object.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct(
      'Plugin/FarmContext',
      $namespaces,
      $module_handler,
      'Drupal\farm_ui_context\Plugin\FarmContext\FarmContextInterface',
      'Drupal\farm_ui_context\Annotation\FarmContext',
    );
    $this->alterInfo($this->getType());
    $this->setCacheBackend($cache_backend, 'farm_context');
  }

  /**
   * {@inheritdoc}
   */
  protected function getType() {
    return 'farm_context';
  }

  /**
   * {@inheritdoc}
   */
  public function getMessages(string $consumer, array $contexts = NULL, array $extra = []): array {

    // Get applicable farm context plugins.
    $definitions = $this->getFilteredDefinitions($consumer, $contexts);

    // Collect messages from each plugin.
    $messages = [];
    foreach (array_keys($definitions) as $plugin_id) {
      /** @var \Drupal\farm_ui_context\Plugin\FarmContext\FarmContextInterface $plugin */
      $plugin = $this->createInstance($plugin_id);

      // Set the plugin contexts.
      if ($plugin instanceof ContextAwarePluginInterface) {
        $this->contextHandler()->applyContextMapping($plugin, $contexts);
      }

      // Include the messages.
      array_push($messages, ...$plugin->getMessages());
    }

    return $messages;
  }

}
