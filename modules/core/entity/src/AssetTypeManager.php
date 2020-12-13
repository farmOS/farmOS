<?php

namespace Drupal\farm_entity;

use Drupal\Component\Plugin\Exception\PluginException;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;

/**
 * Manages discovery and instantiation of asset type plugins.
 *
 * @see \Drupal\farm_entity\Annotation\AssetType
 * @see plugin_api
 */
class AssetTypeManager extends DefaultPluginManager {

  /**
   * Constructs a new AssetTypeManager object.
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
    parent::__construct('Plugin/Asset/AssetType', $namespaces, $module_handler, 'Drupal\farm_entity\Plugin\Asset\AssetType\AssetTypeInterface', 'Drupal\farm_entity\Annotation\AssetType');

    $this->alterInfo('asset_type_info');
    $this->setCacheBackend($cache_backend, 'asset_type_plugins');
  }

  /**
   * {@inheritdoc}
   */
  public function processDefinition(&$definition, $plugin_id) {
    parent::processDefinition($definition, $plugin_id);

    foreach (['id', 'label'] as $required_property) {
      if (empty($definition[$required_property])) {
        throw new PluginException(sprintf('The asset type %s must define the %s property.', $plugin_id, $required_property));
      }
    }
  }

}
