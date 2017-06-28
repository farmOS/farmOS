<?php
/**
 * Views code for the Farm UI module.
 */

/**
 * Helper function to add asset cluster maps to Views.
 */
function _farm_ui_views_post_render(&$view, &$output, &$cache) {

  // Load entity UI info.
  $ui_info = farm_ui_entities();

  // Search for a matching asset list View.
  $found = FALSE;
  if (!empty($ui_info['farm_asset'])) {
    foreach ($ui_info['farm_asset'] as $bundle => $info) {

      // If an asset list View is not available, skip it.
      if (empty($info['view'])) {
        continue;
      }

      // If we are not working with the same View, skip it.
      if ($info['view'] != $view->name) {
        continue;
      }

      // We must have found it! We can stop now.
      $found = TRUE;
      break;
    }
  }

  // If nothing was found, bail.
  if (empty($found)) {
    return;
  }

  // We also only care about the 'page' display.
  if ($view->current_display != 'page') {
    return;
  }

  // If there are no results in the View, bail.
  if (empty($view->result)) {
    return;
  }

  // If dashboard maps are disabled in the farm_map module settings, bail.
  if (!variable_get('farm_map_show', TRUE)) {
    return;
  }

  // If there are any arguments, bail.
  /**
   * @todo
   * Display a map that is filtered by the same arguments.
   */
  if (!empty($view->args)) {
    return;
  }

  // Add the asset cluster map to the top of the View.
  $map = \Drupal\openlayers\Openlayers::load('Map', 'farm_assets_' . $bundle);
  if (!empty($map)) {
    $map_build = $map->build();
    $output = drupal_render($map_build) . $output;
  }
}
