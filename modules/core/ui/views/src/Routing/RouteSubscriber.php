<?php

namespace Drupal\farm_ui_views\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Alter routes for the farm_ui_views module.
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

    // Add our _asset_children_access requirement to
    // view.farm_asset.page_children.
    if ($route = $collection->get('view.farm_asset.page_children')) {
      $route->setRequirement('_asset_children_access', 'Drupal\farm_ui_views\Access\FarmAssetChildrenViewsAccessCheck::access');
    }

    // Add our _location_assets_access requirement to
    // view.farm_asset.page_location.
    if ($route = $collection->get('view.farm_asset.page_location')) {
      $route->setRequirement('_location_assets_access', 'Drupal\farm_ui_views\Access\FarmLocationAssetViewsAccessCheck::access');
    }

    // Add our _inventory_asset_access requirement to
    // view.farm_inventory.page_asset.
    if ($route = $collection->get('view.farm_inventory.page_asset')) {
      $route->setRequirement('_asset_inventory_access', 'Drupal\farm_ui_views\Access\FarmInventoryAssetViewsAccessCheck::access');
    }
  }

}
