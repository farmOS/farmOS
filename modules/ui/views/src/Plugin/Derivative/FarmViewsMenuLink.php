<?php

namespace Drupal\farm_ui_views\Plugin\Derivative;

use Drupal\views\Plugin\Derivative\ViewsMenuLink;

/**
 * Provides menu links for farmOS Views.
 *
 * @see \Drupal\views\Plugin\Menu\ViewsMenuLink
 */
class FarmViewsMenuLink extends ViewsMenuLink {

  /**
   * Set this in child classes.
   *
   * @var string
   *
   * @see \Drupal\farm_ui_views\Plugin\Derivative\FarmAssetViewsMenuLink
   * @see \Drupal\farm_ui_views\Plugin\Derivative\FarmLogViewsMenuLink
   * @see \Drupal\farm_ui_views\Plugin\Derivative\FarmQuantityViewsMenuLink
   */
  protected string $entityType;

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    $links = [];

    // Add links for each bundle.
    $bundles = \Drupal::service('entity_type.bundle.info')->getBundleInfo($this->entityType);
    foreach ($bundles as $type => $info) {
      $links['farm.' . $this->entityType . '.' . $type] = [
        'title' => $info['label'],
        'parent' => 'views_view:views.farm_' . $this->entityType . '.page',
        'route_name' => 'view.farm_' . $this->entityType . '.page_type',
        'route_parameters' => ['arg_0' => $type],
      ] + $base_plugin_definition;
    }

    return $links;
  }

}
