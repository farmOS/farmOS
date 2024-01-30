<?php

namespace Drupal\farm_quick\Routing;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\farm_quick\Form\QuickForm;
use Drupal\farm_quick\QuickFormInstanceManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Defines quick form routes.
 */
class QuickFormRoutes implements ContainerInjectionInterface {

  /**
   * The quick form instance manager.
   *
   * @var \Drupal\farm_quick\QuickFormInstanceManagerInterface
   */
  protected $quickFormInstanceManager;

  /**
   * Constructs a QuickFormRoutes object.
   *
   * @param \Drupal\farm_quick\QuickFormInstanceManagerInterface $quick_form_instance_manager
   *   The quick form instance manager.
   */
  public function __construct(QuickFormInstanceManagerInterface $quick_form_instance_manager) {
    $this->quickFormInstanceManager = $quick_form_instance_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('quick_form.instance_manager'),
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
    /** @var \Drupal\farm_quick\Entity\QuickFormInstanceInterface[] $quick_forms */
    $quick_forms = $this->quickFormInstanceManager->getInstances();
    foreach ($quick_forms as $id => $quick_form) {

      // Skip quick forms that are disabled.
      if (!$quick_form->status()) {
        continue;
      }

      // Build a route for the quick form.
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
