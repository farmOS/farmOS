<?php

namespace Drupal\farm_quick;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\farm_quick\Form\QuickForm;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Quick form manager class.
 */
class QuickFormManager extends DefaultPluginManager {

  /**
   * Constructs a QuickFormManager object.
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
      'Plugin/QuickForm',
      $namespaces,
      $module_handler,
      'Drupal\farm_quick\Plugin\QuickForm\QuickFormInterface',
      'Drupal\farm_quick\Annotation\QuickForm'
    );
    $this->alterInfo('quick_form_info');
    $this->setCacheBackend($cache_backend, 'quick_forms');
  }

  /**
   * Provides routes for quick forms.
   *
   * @return \Symfony\Component\Routing\RouteCollection
   *   Returns a route collection.
   */
  public function getRoutes(): RouteCollection {
    $route_collection = new RouteCollection();
    foreach ($this->getDefinitions() as $id => $definition) {
      $route = new Route(
        "/quick/$id",
        [
          '_form' => QuickForm::class,
          '_title_callback' => QuickForm::class . '::getTitle',
          'id' => $id,
        ],
        [
          '_custom_access' => QuickForm::class . '::access',
        ],
      );
      $route_collection->add("farm.quick.$id", $route);
    }
    return $route_collection;
  }

}
