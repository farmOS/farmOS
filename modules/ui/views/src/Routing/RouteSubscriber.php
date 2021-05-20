<?php

namespace Drupal\farm_ui_views\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
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

    // Add our _asset_logs_access requirement to view.farm_log.page_asset.
    if ($route = $collection->get('view.farm_log.page_asset')) {
      $route->setRequirement('_asset_logs_access', 'Drupal\farm_ui_views\Access\FarmAssetLogViewsAccessCheck::access');
    }
  }

}
