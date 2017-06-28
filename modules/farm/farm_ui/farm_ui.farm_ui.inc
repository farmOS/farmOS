<?php
/**
 * Farm UI hooks implemented by the Farm UI module.
 */

/**
 * Implements hook_farm_ui_groups().
 */
function farm_ui_farm_ui_entity_view_groups() {
  $groups = array(
    'assets' => array(
      'title' => t('Assets'),
      'weight' => 98,
    ),
    'logs' => array(
      'title' => t('Logs'),
      'weight' => 99,
    ),
    'other' => array(
      'weight' => 100,
    ),
  );
  return $groups;
}

/**
 * Implements hook_farm_ui_entity_views().
 */
function farm_ui_farm_ui_entity_views($entity_type, $entity_bundle, $entity) {
  $views = array();

  // Load entity UI information.
  $ui_info = farm_ui_entities();

  // Automatically generate a list of Views to display on areas.
  if ($entity_type == 'taxonomy_term' && $entity_bundle == 'farm_areas') {

    // We will add Views of assets and logs.
    $types = array(
      'farm_asset',
      'log',
    );
    foreach ($types as $type) {
      if (!empty($ui_info[$type])) {
        foreach ($ui_info[$type] as $bundle => $info) {

          // If a View is not defined, skip it.
          if (empty($info['view'])) {
            continue;
          }

          // If the entity is a log, and it doesn't apply to areas, skip it.
          if ($type == 'log' && (empty($info['areas']) || $info['areas'] !== TRUE)) {
            continue;
          }

          // Determine which group to put the View in.
          $group = 'other';
          switch ($type) {
            case 'farm_asset':
              $group = 'assets';
              break;
            case 'log':
              $group = 'logs';
              break;
          }

          // Determine where the area ID argument is in the View.
          $area_argument_position = farm_ui_views_area_argument_position($type, $bundle);

          // Add the View.
          $view = array(
            'name' => $info['view'],
            'arg' => $area_argument_position,
            'group' => $group,
          );
          if (!empty($info['weight'])) {
            $view['weight'] = $info['weight'];
          }
          $views[] = $view;
        }
      }
    }
  }

  // Otherwise, if this is a taxonomy term...
  elseif ($entity_type == 'taxonomy_term') {

    // And if the term is associated with a specific asset type...
    if (!empty($ui_info[$entity_type][$entity_bundle]['farm_asset'])) {

      // Get the asset type.
      $asset_type = $ui_info[$entity_type][$entity_bundle]['farm_asset'];

      // And if that asset type has a View.
      if (!empty($ui_info['farm_asset'][$asset_type]['view'])) {

        // Get the View.
        $asset_view = $ui_info['farm_asset'][$asset_type]['view'];

        // And if the 'asset_view_arg' key is set...
        if (!empty($ui_info[$entity_type][$entity_bundle]['asset_view_arg'])) {

          // Get the argument position.
          $arg = $ui_info[$entity_type][$entity_bundle]['asset_view_arg'];

          // Add the asset View to this term (and always show it).
          $views[] = array(
            'name' => $asset_view,
            'arg' => $arg,
            'group' => 'assets',
            'always' => TRUE,
          );
        }
      }
    }
  }

  return $views;
}
