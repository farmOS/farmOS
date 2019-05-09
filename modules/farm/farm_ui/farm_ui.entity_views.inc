<?php
/**
 * Views code for the Farm UI module.
 */

/**
 * Build a renderable array of Views to add to a farmOS entity.
 *
 * @param $entity_type
 *   The entity type. Currently supports: 'farm_asset' or 'taxonomy_term'.
 * @param $entity_bundle
 *   The entity bundle.
 * @param $entity
 *   The loaded entity object.
 *
 * @return array
 *   Returns a render array of Views to add to the entity page.
 */
function farm_ui_entity_views($entity_type, $entity_bundle, $entity) {

  // Start an empty build array.
  $build = array();

  // Start an empty array of Views.
  $views = array();

  // Load entity UI information.
  $ui_info = farm_ui_entities();

  // Automatically generate a list of log Views to display on assets.
  if ($entity_type == 'farm_asset' && !empty($ui_info['log'])) {
    foreach ($ui_info['log'] as $bundle => $info) {

      // If a View is not defined, skip it.
      if (empty($info['view'])) {
        continue;
      }

      // If the log applies to this asset type (or to all types), add the View.
      if (empty($info['farm_asset']) || $info['farm_asset'] == 'all' || $info['farm_asset'] == $entity->type) {
        $view = array(
          'name' => $info['view'],
          'group' => 'logs',
        );
        if (!empty($info['log_view_asset_arg'])) {
          $view['arg'] = $info['log_view_asset_arg'];
        }
        if (!empty($info['weight'])) {
          $view['weight'] = $info['weight'];
        }
        $views[] = $view;
      }
    }
  }

  // Ask modules for Views.
  $module_views = module_invoke_all('farm_ui_entity_views', $entity_type, $entity_bundle, $entity);
  if (!empty($module_views)) {
    $views = array_merge($views, $module_views);
  }

  // If there are no Views, bail.
  if (empty($views)) {
    return $build;
  }

  // Process the list of Views into a standardized list,
  // and prepare to order by weight and name.
  $weight_index = array();
  $name_index = array();
  foreach ($views as $key => $data) {

    // If the data is just a name, wrap it in an array.
    if (!is_array($data)) {
      $data = array(
        'name' => $data,
      );
    }

    // Merge with defaults.
    $defaults = array(
      'arg' => 1,
      'weight' => 0,
    );
    $views[$key] = array_merge($defaults, $data);

    // Add to the weight and name indexes for sorting.
    $weight_index[$key] = $views[$key]['weight'];
    $name_index[$key] = $views[$key]['name'];
  }

  // Sort the Views by weight ascending, name ascending.
  array_multisort($weight_index, SORT_ASC, $name_index, SORT_ASC, $views);

  // Define the ID property of the entity, based on the type.
  switch ($entity_type) {
    case 'farm_asset':
      $id = 'id';
      break;
    case 'farm_plan':
      $id = 'id';
      break;
    case 'taxonomy_term':
      $id = 'tid';
      break;
  }

  // Add the Views to the entity's render array.
  foreach ($views as $key => $data) {

    // Load the View, and bail if it isn't found.
    $view = views_get_view($data['name']);
    if (empty($view)) {
      continue;
    }

    // Determine the argument position (default to 1).
    // This looks for the presence of $data['arg'] to learn which argument
    // in the View we should send $entity->{$id} into. This is useful if the View
    // has multiple contextual filters, and the entity filter is not first.
    // Any arguments that come before the term argument will receive 'all'
    // as their input.
    $args = array();
    $arg_pos = isset($data['arg']) ? $data['arg'] : 1;
    for ($i = 1; $i <= $arg_pos; $i++) {
      if ($i == $arg_pos) {
        $args[] = $entity->{$id};
      }
      else {
        $args[] = 'all';
      }
    }

    // If a specific display was specified, use it. Otherwise use 'default'.
    if (!empty($data['display'])) {
      $display = $data['display'];
    }
    else {
      $display = 'default';
    }
    $view->set_display($display);

    // Get the View's default title.
    // We intentionally do this before we build the preview so that the title
    // is not overridden by the arguments. This keeps the title simple on the
    // actual entity page, but more descriptive in other contexts.
    $title = $view->get_title();

    // Build the View preview.
    $preview = $view->preview($display, $args);

    // Only display if the View has results (and 'always' is not TRUE).
    if (empty($data['always']) && $view->total_rows == 0) {
      continue;
    }

    // Determine which group the View should be in. Available options are:
    // assets, logs, and other (default).
    $group = 'other';
    if (!empty($data['group'])) {
      $group = $data['group'];
    }

    // Build the output.
    $output = '<h3 id="' . $title . '">' . $title . '</h3>' . $preview;

    // Add the output to the entity build array.
    $build[$group][$data['name']] = array(
      '#markup' => $output,
      '#weight' => $key,
    );
  }

  // Add group meta properties.
  $groups = module_invoke_all('farm_ui_entity_view_groups');
  foreach ($groups as $group => $info) {
    if (!empty($build[$group])) {
      if (!empty($info['title'])) {
        $build[$group]['#type'] = 'fieldset';
        $build[$group]['#title'] = $info['title'];
      }
      $build[$group]['#weight'] = $info['weight'];

      // Collapse by default, if desired.
      if (!empty($info['collapse'])) {
        $build[$group]['#collapsible'] = TRUE;
        $build[$group]['#collapsed'] = TRUE;
      }
    }
  }

  // Return the build array.
  return $build;
}
