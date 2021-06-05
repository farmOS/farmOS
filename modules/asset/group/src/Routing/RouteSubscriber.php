<?php

namespace Drupal\farm_group\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Alter routes for the farm_group module.
 *
 * @ingroup farm
 */
class RouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  public function alterRoutes(RouteCollection $collection) {

    // Add our _group_members_access requirement to
    // view.farm_group_members.page.
    if ($route = $collection->get('view.farm_group_members.page')) {
      $route->setRequirement('_group_members_access', 'Drupal\farm_group\Access\FarmGroupMembersViewsAccessCheck::access');
    }
  }

}
