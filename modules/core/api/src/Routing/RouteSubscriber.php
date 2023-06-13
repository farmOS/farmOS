<?php

namespace Drupal\farm_api\Routing;

use Drupal\Core\Routing\RouteObjectInterface;
use Drupal\Core\Routing\RouteSubscriberBase;
use Drupal\farm_api\Controller\FarmEntryPoint;
use Symfony\Component\Routing\RouteCollection;

/**
 * Alter routes for the farm_api module.
 *
 * @ingroup farm
 */
class RouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  public function alterRoutes(RouteCollection $collection) {
    // Alter the root jsonapi.resource_list route to use the FarmEntryPoint
    // controller. This allows us to add the meta.farm object.
    if ($route = $collection->get('jsonapi.resource_list')) {
      $route->setDefaults([RouteObjectInterface::CONTROLLER_NAME => FarmEntryPoint::class . '::index']);
    }
  }

}
