<?php

declare(strict_types=1);

namespace Drupal\farm_ui_action\Plugin\Menu\LocalAction;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Menu\LocalActionDefault;
use Drupal\Core\Routing\RouteMatchInterface;

/**
 * Creates an action link for entity actions.
 */
class EntityAction extends LocalActionDefault {

  /**
   * Sets the entity parameter for the entity action local actions.
   *
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The route match.
   *
   * @return array
   *   The route parameters.
   */
  public function getRouteParameters(RouteMatchInterface $route_match): array {
    $entity_types = [
      'asset',
      'log',
      'organization',
      'plan',
    ];
    $parameters = parent::getRouteParameters($route_match);
    foreach ($route_match->getParameters()->all() as $name => $value) {
      if (in_array($name, $entity_types)) {
        if ($value instanceof EntityInterface) {
          $parameters['entity'] = $value->id();
        }
      }
    }
    return $parameters;
  }

}
