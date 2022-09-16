<?php

/**
 * @file
 * Provides Views runtime hooks for farm_ui_map.module.
 */

use Drupal\asset\Entity\AssetType;
use Drupal\views\Entity\View;
use Drupal\views\ViewExecutable;

/**
 * Implements hook_views_pre_view().
 */
function farm_ui_map_views_pre_view(ViewExecutable $view, $display_id, array &$args) {

  // Alter the farm_asset_geojson View's full and centroid displays to add all
  // exposed filters that are present in the farm_asset View.
  if ($view->id() == 'farm_asset_geojson' && in_array($display_id, ['full', 'centroid'])) {

    // Load the farm_asset View. Bail if unavailable.
    $farm_asset_view = View::load('farm_asset');
    if (empty($farm_asset_view)) {
      return;
    }

    // Copy all exposed filters from the default display.
    $display = $farm_asset_view->getDisplay('default');
    if (!empty($display['display_options']['filters'])) {
      foreach ($display['display_options']['filters'] as $field => $filter) {
        $view->addHandler($display_id, 'filter', $filter['table'], $field, $filter, $filter['id']);
      }
    }

    // If a type argument is present, add bundle-specific exposed filters.
    if (!empty($args[0]) && $args[0] != 'all') {
      farm_ui_views_add_bundle_handlers($view, $display_id, $args[0], 'filter');
    }
  }
}

/**
 * Implements hook_views_pre_render().
 *
 * @see farm_ui_map_module_implements_alter()
 */
function farm_ui_map_views_pre_render(ViewExecutable $view) {

  // Render a map attachment above views of assets.
  if ($view->id() == 'farm_asset' && in_array($view->current_display, ['page', 'page_type'])) {

    // Get all asset bundles.
    $asset_bundles = \Drupal::service('entity_type.bundle.info')->getBundleInfo($view->getBaseEntityType()->id());

    // Start array of filtered bundles.
    $filtered_bundles = $asset_bundles;

    // Start array of asset layers to add.
    $asset_layers = [
      'full' => [],
    ];

    // Save the group labels.
    $layer_group = $view->getBaseEntityType()->getCollectionLabel();

    // Get exposed filters.
    $exposed_filters = $view->getExposedInput();

    // Add multiple asset layers to the page of all assets.
    if ($view->current_display == 'page') {

      // Limit to filtered asset types.
      if (!empty($exposed_filters['type'])) {
        $filtered_bundles = array_intersect_key($asset_bundles, array_flip($exposed_filters['type']));
      }
    }

    // Determine if we are filtering by bundle.
    // This may happen via contextual filter on the "page_type" display, or via
    // the exposed "type" filter.
    $bundle_filters = [];
    if ($view->current_display == 'page_type' && !empty($view->args[0])) {
      $bundle_filters[] = $view->args[0];
    }
    elseif (!empty($exposed_filters['type'])) {
      foreach ($exposed_filters['type'] as $bundle) {
        $bundle_filters[] = $bundle;
      }
    }

    // Filter by bundle, if desired.
    if (!empty($bundle_filters)) {
      $filtered_bundles = [];
      foreach ($bundle_filters as $bundle) {
        $filtered_bundles[$bundle] = $asset_bundles[$bundle];
      }
    }

    // Add a cluster layer for summarizing asset counts.
    $asset_layers['cluster']['all'] = [
      'label' => t('Asset counts'),
      'cluster' => TRUE,
      'filters' => $exposed_filters,
    ];
    if (!empty($bundle_filters)) {
      $asset_layers['cluster']['all']['asset_type'] = implode('+', $bundle_filters);
    }

    // Add a full asset geometry layer for each asset type.
    foreach ($filtered_bundles as $bundle => $bundle_info) {

      // Load the bundle entity.
      $type = AssetType::load($bundle);

      // Load the map layer style.
      /** @var \Drupal\farm_map\Entity\LayerStyleInterface $layer_style */
      $layer_style = \Drupal::service('farm_map.layer_style_loader')->load(['asset_type' => $bundle]);
      if (!is_null($layer_style)) {
        $color = $layer_style->get('color');
      }

      // Add layer for the asset type.
      $asset_layers['full']['full_' . $bundle] = [
        'group' => $type->getThirdPartySetting('farm_location', 'is_location', FALSE) ? t('Location assets') : $layer_group,
        'label' => $bundle_info['label'],
        'asset_type' => $bundle,
        'filters' => $exposed_filters,
        'color' => $color ?? 'orange',
        'zoom' => TRUE,
      ];
    }

    // Build the map render array.
    $map = [
      '#type' => 'farm_map',
      '#map_type' => 'asset_list',
    ];
    $all_layers = array_merge($asset_layers['cluster'], $asset_layers['full']);
    $map['#map_settings']['asset_type_layers'] = $all_layers;

    // Render the map.
    $view->attachment_before['asset_map'] = $map;
  }
}
