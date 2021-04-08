<?php

namespace Drupal\farm_ui_breadcrumb\Breadcrumb;

use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Link;
use Drupal\system\PathBasedBreadcrumbBuilder;

/**
 * Build farmOS breadcrumbs.
 */
class FarmBreadcrumbBuilder extends PathBasedBreadcrumbBuilder {

  /**
   * {@inheritdoc}
   */
  public function build(RouteMatchInterface $route_match) {
    $breadcrumb = parent::build($route_match);

    // Get parameters.
    $parameters = $route_match->getParameters();

    // Add links based on the route.
    switch ($route_match->getRouteName()) {

      // Asset pages.
      case 'entity.asset.canonical':
        /** @var \Drupal\asset\Entity\AssetInterface $asset */
        $asset = $parameters->get('asset');
        $breadcrumb->addLink(Link::createFromRoute('Records', '<front>'));
        $breadcrumb->addLink(Link::createFromRoute('Assets', 'view.farm_asset.page'));
        $breadcrumb->addLink(Link::createFromRoute($asset->getBundleLabel(), 'view.farm_asset.page_type', ['arg_0' => $asset->bundle()]));
        break;

      // Log pages.
      case 'entity.log.canonical':
        /** @var \Drupal\log\Entity\LogInterface $log */
        $log = $parameters->get('log');
        $breadcrumb->addLink(Link::createFromRoute('Records', '<front>'));
        $breadcrumb->addLink(Link::createFromRoute('Logs', 'view.farm_log.page'));
        $breadcrumb->addLink(Link::createFromRoute($log->getBundleLabel(), 'view.farm_log.page_type', ['arg_0' => $log->bundle()]));
        break;

      // Plan pages.
      case 'entity.plan.canonical':
        /** @var \Drupal\plan\Entity\PlanInterface $plan */
        $plan = $parameters->get('plan');
        $breadcrumb->addLink(Link::createFromRoute('Plans', 'view.farm_plan.page'));
        $breadcrumb->addLink(Link::createFromRoute($plan->getBundleLabel(), 'view.farm_plan.page_type', ['arg_0' => $plan->bundle()]));
        break;
    }

    return $breadcrumb;
  }

}
