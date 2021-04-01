<?php

namespace Drupal\data_stream;

use Drupal\Component\Plugin\Exception\PluginException;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;

/**
 * Manages discovery and instantiation of data stream type plugins.
 *
 * @see \Drupal\data_stream\Annotation\DataStreamType
 * @see plugin_api
 */
class DataStreamTypeManager extends DefaultPluginManager {

  /**
   * Constructs a new DataStreamTypeManager object.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   The cache backend.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct('Plugin/DataStream/DataStreamType', $namespaces, $module_handler, 'Drupal\data_stream\Plugin\DataStream\DataStreamType\DataStreamTypeInterface', 'Drupal\data_stream\Annotation\DataStreamType');

    $this->alterInfo('data_stream_type_info');
    $this->setCacheBackend($cache_backend, 'data_stream_type_plugins');
  }

  /**
   * {@inheritdoc}
   */
  public function processDefinition(&$definition, $plugin_id) {
    parent::processDefinition($definition, $plugin_id);

    foreach (['id', 'label'] as $required_property) {
      if (empty($definition[$required_property])) {
        throw new PluginException(sprintf('The data stream type %s must define the %s property.', $plugin_id, $required_property));
      }
    }
  }

}
