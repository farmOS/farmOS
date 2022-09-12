<?php

/**
 * @file
 * Provides Views runtime hooks for farm_structure.module.
 */

use Drupal\farm_structure\Entity\FarmStructureType;
use Drupal\views\ViewExecutable;

/**
 * Implements hook_views_pre_render().
 */
function farm_structure_views_pre_render(ViewExecutable $view) {

  // Add structure type map layers to the structure assets map.
  if ($view->id() == 'farm_asset' && $view->current_display == 'page_type' && !empty($view->args[0]) && $view->args[0] == 'structure') {

    // If the asset_map has not been added, bail.
    if (empty($view->attachment_before['asset_map'])) {
      return;
    }
    $map = &$view->attachment_before['asset_map'];

    // Load all structure types.
    $structure_types = FarmStructureType::loadMultiple();

    // Get exposed filters.
    $exposed_filters = $view->getExposedInput();

    // If the structure type exposed filter is already in use remove structure
    // types that are not included.
    if (!empty($exposed_filters['structure_type_value'])) {
      $structure_types = array_filter($structure_types, function ($key) use ($exposed_filters) {
        return in_array($key, $exposed_filters['structure_type_value']);
      }, ARRAY_FILTER_USE_KEY);
    }

    // Create a layer for each structure type.
    $asset_layers = [];
    foreach ($structure_types as $structure_type) {
      /** @var \Drupal\farm_map\Entity\LayerStyleInterface $layer_style */
      $layer_style = \Drupal::service('farm_map.layer_style_loader')->load(['asset_type' => 'structure', 'structure_type' => $structure_type->id()]);
      if (!is_null($layer_style)) {
        $color = $layer_style->get('color');
      }
      $asset_layers['structure_' . $structure_type->id()] = [
        'group' => t('Structure types'),
        'label' => $structure_type->label(),
        'asset_type' => 'structure',
        'filters' => $exposed_filters + ['structure_type_value[]' => $structure_type->id()],
        'color' => $color ?? 'orange',
        'zoom' => TRUE,
      ];
    }

    // Add layers to the map settings.
    $map['#map_settings']['asset_type_layers'] = array_merge($map['#map_settings']['asset_type_layers'], $asset_layers);

    // Remove the structure asset layer.
    unset($map['#map_settings']['asset_type_layers']['full_structure']);
  }
}
