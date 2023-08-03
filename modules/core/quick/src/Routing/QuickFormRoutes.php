<?php

namespace Drupal\farm_quick\Routing;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\farm_quick\Form\QuickForm;
use Drupal\farm_quick\QuickFormPluginManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Defines quick form routes.
 */
class QuickFormRoutes implements ContainerInjectionInterface {

  /**
   * The quick form manager.
   *
   * @var \Drupal\farm_quick\QuickFormPluginManager
   */
  protected $quickFormPluginManager;

  /**
   * Constructs a QuickFormRoutes object.
   *
   * @param \Drupal\farm_quick\QuickFormManager $quick_form_manager
   *   The quick form manager.
   */
  public function __construct(QuickFormPluginManager $quick_form_plugin_manager) {
    $this->quickFormPluginManager = $quick_form_plugin_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.quick_form'),
    );
  }

  /**
   * Provides routes for quick forms.
   *
   * @return \Symfony\Component\Routing\RouteCollection
   *   Returns a route collection.
   */
  public function routes(): RouteCollection {
    $route_collection = new RouteCollection();
    foreach ($this->quickFormPluginManager->getDefinitions() as $id => $definition) {
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
