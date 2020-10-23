<?php

namespace Drupal\data_stream;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;

/**
 * DataStreamPluginManager.
 */
class DataStreamPluginManager extends DefaultPluginManager {

  /**
   * Constructs a SensorTypeManager object.
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
      'Plugin/DataStream',
      $namespaces,
      $module_handler,
      'Drupal\data_stream\DataStreamPluginInterface',
      'Drupal\data_stream\Annotation\DataStream'
    );
    $this->setCacheBackend($cache_backend, 'data_stream_plugins');
  }

}
