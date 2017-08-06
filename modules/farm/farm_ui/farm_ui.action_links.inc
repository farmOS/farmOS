<?php
/**
 * Action links code for the Farm UI module.
 */

/**
 * Helper function for adding farmOS action links.
 */
function _farm_ui_menu_local_tasks_alter(&$data, $router_item, $root_path) {

  // Gather action links defined by modules
  $actions = module_invoke_all('farm_ui_actions');

  // Sort by weight.
  uasort($actions, 'drupal_sort_weight');

  // Add actions to farm/asset/* paths.
  if ($router_item['page_callback'] == 'farm_asset_view') {

    // Get the asset id from the path.
    $asset_id = check_plain(arg(2));

    // Iterate through the actions.
    foreach ($actions as $name => $action) {

      // If the list of assets types is empty, skip it.
      if (empty($actions[$name]['assets'])) {
        continue;
      }

      // If 'all' assets are not allowed...
      if (!in_array('all', $actions[$name]['assets'])) {

        // Load the asset.
        $asset = farm_asset_load($asset_id);

        // If the asset's type is not in the list of acceptable types, skip it.
        if (!in_array($asset->type, $actions[$name]['assets'])) {
          continue;
        }
      }

      // Build the action link.
      $link = farm_ui_action_link($actions[$name]['title'], $actions[$name]['href'], array('destination' => 'farm/asset/' . $asset_id, 'farm_asset' => $asset_id));

      // Add the action link to the output.
      $data['actions']['output'][] = $link;
    }
  }

  // Add actions to Views pages.
  elseif ($router_item['page_callback'] == 'views_page') {

    // Iterate through the actions.
    foreach ($actions as $name => $action) {

      // If the list of Views is empty, skip it.
      if (empty($actions[$name]['views'])) {
        continue;
      }

      // Get the View name from the first page argument.
      $view_name = reset($router_item['page_arguments']);

      // If the View is not in the list of acceptable Views, skip it.
      if (!in_array($view_name, $actions[$name]['views'])) {
        continue;
      }

      // Build the action link.
      $link = farm_ui_action_link($actions[$name]['title'], $actions[$name]['href'], array('destination' => $root_path));

      // Add the action link to the output.
      $data['actions']['output'][] = $link;
    }
  }

  // Create a new array of actions keyed by path.
  $path_actions = array();
  foreach ($actions as $name => $action) {
    if (!empty($action['paths'])) {
      foreach ($action['paths'] as $path) {
        $path_actions[$path][] = $name;
      }
    }
  }

  // Add actions depending on the root path.
  if (array_key_exists($root_path, $path_actions)) {
    foreach ($path_actions[$root_path] as $name) {

      // Generate the action link.
      $link = farm_ui_action_link($actions[$name]['title'], $actions[$name]['href'], array('destination' => $root_path));

      if ($root_path == 'taxonomy/term/%' || substr($root_path, 0, 11) == 'farm/area/%') {

        // Get the area id from the path.
        $area_id = check_plain(arg(2));

        // Load the area taxonomy term.
        $term = taxonomy_term_load($area_id);

        // If the taxonomy term is not a farm_area, bail.
        if (empty($term->vocabulary_machine_name) || $term->vocabulary_machine_name != 'farm_areas') {
          continue;
        }

        // Build the action link.
        $link = farm_ui_action_link($actions[$name]['title'], $actions[$name]['href'], array('destination' => 'taxonomy/term/' . $area_id, 'farm_area' => $area_id));
      }

      // Add the action link to the output.
      $data['actions']['output'][] = $link;
    }
  }
}

/**
 * Helper function for generating an action link.
 *
 * @param string $title
 *   The title of the action link.
 * @param string $href
 *   The href path of the action link.
 * @param array $query
 *   An array of additional query parameters to add.
 *
 * @return array
 *   Returns an action link.
 *
 * @see farm_ui_menu_local_tasks_alter()
 */
function farm_ui_action_link($title, $href, $query = array()) {
  $action_link = array(
    '#theme' => 'menu_local_action',
    '#link' => array(
      'title' => $title,
      'href' => $href,
      'localized_options' => array(
        'query' => $query,
      ),
    ),
  );
  return $action_link;
}
