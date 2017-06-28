<?php
/**
 * Farm Area hooks implemented by the Farm UI module.
 */

/**
 * Implements hook_farm_area_details().
 */
function farm_ui_farm_area_details($id) {

  // Start a render array.
  $output = array();

  // Add links to lists of assets and logs in the area.
  $entity_types = array(
    'farm_asset' => array(
      'label' => t('Assets'),
      'weight' => -50,
    ),
    'log' => array(
      'label' => t('Logs'),
      'weight' => -40,
    ),
  );
  foreach ($entity_types as $type => $info) {

    // Get area links for the entity type.
    $area_links = farm_ui_area_links($id, $type);

    // Create variables for an item list.
    $variables = array(
      'items' => $area_links,
      'attributes' => array(),
    );

    // Render the links as markup with a title.
    $output[$type . '_links'] = array(
      '#type' => 'markup',
      '#markup' => '<strong>' . $info['label'] . '</strong>' . theme('item_list', $variables),
      '#weight' => $info['weight'],
    );
  }

  // Return the render array.
  return $output;
}

/**
 * Generate area links for farm_asset or log entity types.
 *
 * @param int $id
 *   The area id.
 * @param string $entity_type
 *   The entity type to general links for. Currently only 'farm_asset' and
 *   'log' are supported.
 *
 * @return array
 *   Returns an array of links.
 */
function farm_ui_area_links($id, $entity_type) {

  // Start an empty array of links.
  $links = array();

  // Load entity UI info.
  $ui_info = farm_ui_entities();

  // Generate links for the entity type.
  if (!empty($ui_info[$entity_type])) {
    foreach ($ui_info[$entity_type] as $bundle => $info) {

      // If a View is not available, skip it.
      if (empty($info['view'])) {
        continue;
      }

      // If the entity is a log, and it doesn't apply to areas, skip it.
      if ($entity_type == 'log' && (empty($info['areas']) || $info['areas'] !== TRUE)) {
        continue;
      }

      // Determine the position of the area ID argument in the View, and build
      // the arguments array accordingly. If the area ID is in the second
      // argument position, then we assume that asset ID is the first argument,
      // and we set that to 'all' to include all assets.
      $args = array($id);
      $area_argument_position = farm_ui_views_area_argument_position($entity_type, $bundle);
      if ($area_argument_position == 2) {
        array_unshift($args, 'all');
      }

      // Load the View and generate a preview to count rows.
      $view = views_get_view($info['view']);
      $view->preview('default', $args);

      // If there are no results, skip it.
      if (empty($view->total_rows)) {
        continue;
      }

      // Build the View page path with arguments.
      $path = farm_ui_view_page_path($info['view']);
      foreach ($args as $arg) {
        $path .= '/' . $arg;
      }

      // Build a link.
      $link = array(
        'title' => $info['label_plural'] . ': ' . $view->total_rows,
        'href' => $path,
      );

      // Add the link.
      $links[] = l($link['title'], $link['href']);
    }
  }

  // Return the links.
  return $links;
}
