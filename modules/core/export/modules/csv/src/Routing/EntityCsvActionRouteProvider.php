<?php

namespace Drupal\farm_export_csv\Routing;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\Routing\EntityRouteProviderInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Provides routes for the entity CSV export action.
 */
class EntityCsvActionRouteProvider implements EntityRouteProviderInterface {

  /**
   * {@inheritdoc}
   */
  public function getRoutes(EntityTypeInterface $entity_type) {
    $collection = new RouteCollection();
    $entity_type_id = $entity_type->id();
    if ($route = $this->getEntityCsvFormRoute($entity_type)) {
      $collection->add("entity.$entity_type_id.csv_form", $route);
    }

    return $collection;
  }

  /**
   * Gets the entity CSV export form route.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type.
   *
   * @return \Symfony\Component\Routing\Route|null
   *   The generated route, if available.
   */
  protected function getEntityCsvFormRoute(EntityTypeInterface $entity_type) {
    if ($entity_type->hasLinkTemplate('csv-action-form')) {
      $route = new Route($entity_type->getLinkTemplate('csv-action-form'));
      $route->setDefault('_form', $entity_type->getFormClass('csv-action-form'));
      $route->setDefault('entity_type_id', $entity_type->id());
      $route->setRequirement('_user_is_logged_in', 'TRUE');
      return $route;
    }
  }

}
