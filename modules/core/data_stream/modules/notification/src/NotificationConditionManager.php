<?php

namespace Drupal\data_stream_notification;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Executable\ExecutableException;
use Drupal\Core\Executable\ExecutableInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\data_stream_notification\Plugin\DataStream\NotificationCondition\NotificationConditionInterface;

/**
 * Plugin manager for notification condition plugins.
 */
class NotificationConditionManager extends DefaultPluginManager implements NotificationConditionManagerInterface {

  /**
   * Constructs a NotificationConditionManager object.
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
      'Plugin/DataStream/NotificationCondition',
      $namespaces,
      $module_handler,
      'Drupal\data_stream_notification\Plugin\DataStream\NotificationCondition\NotificationConditionInterface',
      'Drupal\data_stream_notification\Annotation\NotificationCondition',
    );
    $this->alterInfo('data_stream_notification_condition_info');
    $this->setCacheBackend($cache_backend, 'data_stream_notification_condition');
  }

  /**
   * {@inheritdoc}
   */
  public function createInstance($plugin_id, array $configuration = []) {
    $plugin = parent::createInstance($plugin_id, $configuration);

    // Set the executable manager.
    return $plugin->setExecutableManager($this);
  }

  /**
   * {@inheritdoc}
   */
  public function execute(ExecutableInterface $plugin) {
    if ($plugin instanceof NotificationConditionInterface) {
      $result = $plugin->evaluate();
      return $plugin->isNegated() ? !$result : $result;
    }
    throw new ExecutableException("This manager object can only execute notification condition plugins");
  }

}
