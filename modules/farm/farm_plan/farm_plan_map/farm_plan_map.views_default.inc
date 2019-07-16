<?php
/**
 * @file
 * farm_plan_map.views_default.inc
 */

/**
 * Implements hook_views_default_views_alter().
 */
function farm_plan_map_views_default_views_alter(&$views) {

  // Add a plan contextual argument to asset and area geojson views.
  $view_names_tables = array(
    'farm_areas_geojson' => 'farm_plan_area',
    'farm_asset_geojson' => 'farm_plan_asset',
  );
  foreach ($view_names_tables as $name => $table) {
    $plan_id_argument = array(
      'id' => 'plan_id',
      'table' => $table,
      'field' => 'plan_id',
      'default_argument_type' => 'fixed',
      'summary' => array(
        'number_of_records' => '0',
        'format' => 'default_summary',
      ),
      'summary_options' => array(
        'items_per_page' => '25',
      ),
      'specify_validation' => TRUE,
      'validate' => array(
        'type' => 'farm_plan',
        'fail' => 'empty',
      ),
    );
    if (!empty($views[$name])) {
      $views[$name]->display['default']->display_options['arguments']['plan_id'] = $plan_id_argument;
    }
  }
}
