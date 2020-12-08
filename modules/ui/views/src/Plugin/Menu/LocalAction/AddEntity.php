<?php

namespace Drupal\farm_ui_views\Plugin\Menu\LocalAction;

use Drupal\Core\Menu\LocalActionDefault;
use Drupal\Core\Routing\RouteMatchInterface;

/**
 * Creates an action link to add entities.
 *
 * The 'entity_type' must be set in the action link configuration.
 */
class AddEntity extends LocalActionDefault {

  /**
   * {@inheritdoc}
   */
  public function getRouteParameters(RouteMatchInterface $route_match) {

    // Get the entity type.
    $entity_type = $this->pluginDefinition['entity_type'];
    $entity_type_param = $entity_type . '_type';

    // Set the entity_type parameter for the entity.type.add_form route.
    return [
      $entity_type_param => $route_match->getParameter('arg_0'),
    ];
  }

}
