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

    // Get the entity type definition. Bail if invalid.
    $entity_type_definition = \Drupal::service('entity_type.manager')->getDefinition($this->entityType, FALSE);
    if (empty($entity_type_definition)) {
      return $links;
    }

    // Get the bundle entity type.
    $bundle_entity_type = $entity_type_definition->getBundleEntityType();

    // Load all available bundles for the entity type.
    $bundles = \Drupal::service('entity_type.manager')->getStorage($bundle_entity_type)->loadMultiple();

    // Add links for each bundle.
    foreach ($bundles as $type => $bundle) {
      $links['farm.' . $this->entityType . '.' . $type] = [
        'title' => $bundle->label(),
        'parent' => 'views_view:views.farm_' . $this->entityType . '.page',
        'route_name' => 'view.farm_' . $this->entityType . '.page_type',
        'route_parameters' => ['arg_0' => $type],
      ] + $base_plugin_definition;
    }

    return $links;
  }

}
