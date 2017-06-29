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

/**
 * Implements hook_farm_ui_actions().
 */
function farm_ui_farm_ui_actions() {

  // Define default farm action links.
  $actions = array(
    'area' => array(
      'title' => t('Add area'),
      'href' => 'admin/structure/taxonomy/farm_areas/add',
      'paths' => array(
        'farm',
      ),
      'weight' => -10,
    ),
    'asset' => array(
      'title' => t('Add asset'),
      'href' => 'farm/asset/add',
      'paths' => array(
        'farm',
        'farm/assets',
      ),
    ),
    'log' => array(
      'title' => t('Add log'),
      'href' => 'log/add',
      'paths' => array(
        'farm',
        'farm/logs',
      ),
      'weight' => 1,
    ),
    'user' => array(
      'title' => t('Add person'),
      'href' => 'admin/people/create',
      'views' => array(
        'farm_people',
      ),
    ),
  );

  // Load entity UI information.
  $ui_info = farm_ui_entities();

  // Create action links for each entity type/bundle.
  foreach ($ui_info as $entity_type => $bundles) {
    foreach ($bundles as $bundle => $info) {

      // Start with an action link on the entity listing page.
      if (!empty($info['label']) && !empty($info['view'])) {
        switch ($entity_type) {
          case 'farm_asset':
            $path = 'farm/asset/add/' . $bundle;
            break;
          case 'log':
            $path = 'log/add/' . $bundle;
            break;
          case 'taxonomy_term':
            $path = 'admin/structure/taxonomy/' . $bundle . '/add';
            break;
        }
        $actions[$bundle] = array(
          'title' => t('Add ' . strtolower($info['label'])),
          'href' => $path,
          'views' => array(
            $info['view'],
          ),
        );
      }

      // If the entity has a weight, pass it through to the action link.
      if (!empty($info['weight'])) {
        $actions[$bundle]['weight'] = $info['weight'];
      }

      // If the entity is a log, add an action link on asset and area pages.
      if ($entity_type == 'log') {

        // If 'farm_asset' is empty, or if it is set to 'all', add to all
        // asset pages.
        if (empty($info['farm_asset']) || $info['farm_asset'] == 'all') {
          $actions[$bundle]['assets'] = array('all');
        }

        // Otherwise, if 'farm_asset' is not set to 'none', pass the specified
        // asset through.
        elseif ($info['farm_asset'] != 'none') {
          $actions[$bundle]['assets'] = array($info['farm_asset']);
        }

        // Only add an action link on area pages if 'areas' is set to TRUE.
        if (!empty($info['areas']) && $info['areas'] === TRUE) {
          $actions[$bundle]['paths'] = array('taxonomy/term/%');
        }
      }
    }
  }

  return $actions;
}
