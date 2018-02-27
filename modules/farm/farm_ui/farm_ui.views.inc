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

  // If there are any arguments, bail.
  /**
   * @todo
   * Display a map that is filtered by the same arguments.
   */
  if (!empty($view->args)) {
    return;
  }

  // Add the asset cluster map to the top of the View.
  $title = t('Asset cluster map');
  if (!empty($view->display['default']->display_options['title'])) {
    $asset_type_label = $view->display['default']->display_options['title'];
    $title = t('@asset_type cluster map', array('@asset_type' => $asset_type_label));
  }
  $fieldset = TRUE;
  $collapsed = TRUE;
  $map_build = farm_map_build('farm_assets_' . $bundle, $fieldset, $title, $collapsed);
  $output = drupal_render($map_build)  . $output;
}

/**
 * Helper function for altering entityreference_view_widget arguments.
 */
function _farm_ui_entityreference_view_widget_views_arguments_alter(&$arguments, $form_state, $view) {

  // The farm_log module provides a View that is used for searching and
  // selecting assets for the field_farm_asset entity reference field, using
  // the Entity Reference View Widget module.
  //
  // In some cases, we want to limit what shows up in the View of assets, based
  // on the log type that the field is on. For instance, a seeding should only
  // be able to reference planting assets, but it still uses the general
  // field_farm_asset field, which technically can reference any asset type.
  //
  // The Entity Reference View Widget module provides this hook to allow the
  // View arguments to be altered. We implement this hook, and use information
  // provided by hook_farm_ui_entities() to see if we should filter the View
  // results to a specific asset type, given the current log type.
  //
  // The View that we use is farm_asset_entityreference_view, which has two
  // contextual arguments: asset id (to filter out already-selected assets), and
  // asset type. The code below only tries to set the asset type argument, and
  // maintains any asset id argument that is already set.

  /**
   * @todo
   * Note that this does NOT alter the "Asset type" exposed filter. So users
   * will still see that filter, and it will still default to "- Any -". If
   * the contextual filter is activated here, however, then selecting any other
   * asset type with the exposed filter will return an empty result set. That's
   * confusing, so perhaps we should find a way to hide that exposed filter if
   * this code successfully sets a contextual filter argument.
   */

  // Only apply this to the View: farm_asset_entityreference_view
  if ($view->name != 'farm_asset_entityreference_view') {
    return;
  }

  // Figure out what the log type is.
  if (!empty($form_state['build_info']['args'][0]->type)) {
    $log_type = $form_state['build_info']['args'][0]->type;
  }

  // If that failed, bail.
  else {
    return;
  }

  // Load entity UI info for this log type.
  $ui_info = farm_ui_entities('log', $log_type);

  // If the asset type is empty, or 'all', or 'none', then we will stop here
  // because we don't want to apply any filtering.
  if (empty($ui_info['farm_asset']) || in_array($ui_info['farm_asset'], array('all', 'none'))) {
    return;
  }

  // Take the asset type specified so we can use it as a filter.
  $type = $ui_info['farm_asset'];

  // We need to ensure that the first argument is reserved for asset id(s). So
  // if it's not set, set it to 'all'.
  if (empty($arguments)) {
    $arguments[0] = 'all';
  }

  // Add the asset type as the second argument.
  $arguments[1] = $type;
}
