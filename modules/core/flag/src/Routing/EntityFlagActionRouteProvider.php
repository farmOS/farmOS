<?php

namespace Drupal\farm_flag\Routing;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\Routing\EntityRouteProviderInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Provides routes for the entity flag action.
 */
class EntityFlagActionRouteProvider implements EntityRouteProviderInterface {

  /**
   * {@inheritdoc}
   */
  public function getRoutes(EntityTypeInterface $entity_type) {
    $collection = new RouteCollection();
    $entity_type_id = $entity_type->id();
    if ($flag_route = $this->getEntityFlagFormRoute($entity_type)) {
      $collection->add("entity.$entity_type_id.flag_form", $flag_route);
    }

    return $collection;
  }

  /**
   * Gets the entity flag form route.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type.
   *
   * @return \Symfony\Component\Routing\Route|null
   *   The generated route, if available.
   */
  protected function getEntityFlagFormRoute(EntityTypeInterface $entity_type) {
    if ($entity_type->hasLinkTemplate('flag-action-form')) {
      $route = new Route($entity_type->getLinkTemplate('flag-action-form'));
      $route->setDefault('_form', $entity_type->getFormClass('flag-action-form'));
      $route->setDefault('entity_type_id', $entity_type->id());
      $route->setRequirement('_user_is_logged_in', 'TRUE');
      return $route;
    }
  }

}
