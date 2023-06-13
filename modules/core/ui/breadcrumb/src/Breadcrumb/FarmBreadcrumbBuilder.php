<?php

namespace Drupal\farm_ui_breadcrumb\Breadcrumb;

use Drupal\Core\Link;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\system\PathBasedBreadcrumbBuilder;

/**
 * Build farmOS breadcrumbs.
 */
class FarmBreadcrumbBuilder extends PathBasedBreadcrumbBuilder {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public function applies(RouteMatchInterface $route_match) {
    $routes = [
      'entity.asset.canonical',
      'entity.log.canonical',
      'entity.plan.canonical',
      'entity.user.canonical',
    ];
    return in_array($route_match->getRouteName(), $routes);
  }

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
        $breadcrumb->addCacheableDependency($asset);
        $breadcrumb->addLink(Link::createFromRoute($this->t('Records'), '<front>'));
        $breadcrumb->addLink(Link::createFromRoute($this->t('Assets'), 'view.farm_asset.page'));
        $breadcrumb->addLink(Link::createFromRoute($asset->getBundleLabel(), 'view.farm_asset.page_type', ['arg_0' => $asset->bundle()]));
        break;

      // Log pages.
      case 'entity.log.canonical':
        /** @var \Drupal\log\Entity\LogInterface $log */
        $log = $parameters->get('log');
        $breadcrumb->addCacheableDependency($log);
        $breadcrumb->addLink(Link::createFromRoute($this->t('Records'), '<front>'));
        $breadcrumb->addLink(Link::createFromRoute($this->t('Logs'), 'view.farm_log.page'));
        $breadcrumb->addLink(Link::createFromRoute($log->getBundleLabel(), 'view.farm_log.page_type', ['arg_0' => $log->bundle()]));
        break;

      // Plan pages.
      case 'entity.plan.canonical':
        /** @var \Drupal\plan\Entity\PlanInterface $plan */
        $plan = $parameters->get('plan');
        $breadcrumb->addCacheableDependency($plan);
        $breadcrumb->addLink(Link::createFromRoute($this->t('Plans'), 'view.farm_plan.page'));
        $breadcrumb->addLink(Link::createFromRoute($plan->getBundleLabel(), 'view.farm_plan.page_type', ['arg_0' => $plan->bundle()]));
        break;

      // User pages.
      case 'entity.user.canonical':
        $breadcrumb->addLink(Link::createFromRoute($this->t('People'), 'view.farm_people.page'));
        break;
    }

    // This breadcrumb builder is based on a route parameter, and hence it
    // depends on the 'route' cache context.
    $breadcrumb->addCacheContexts(['route']);

    return $breadcrumb;
  }

}
