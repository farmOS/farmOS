<?php
/**
 * @file
 * farm_area.views_default.inc
 */

/**
 * Implements hook_views_default_views().
 */
function farm_area_views_default_views() {
  $export = array();

  $view = new view();
  $view->name = 'farm_areas';
  $view->description = '';
  $view->tag = 'default';
  $view->base_table = 'taxonomy_term_data';
  $view->human_name = 'Farm Areas';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Fields/Beds';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['pager']['type'] = 'none';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['style_plugin'] = 'openlayers_data';
  $handler->display->display_options['style_options']['data_source'] = array(
    'value' => 'wkt',
    'other_lat' => 'name',
    'other_lon' => 'name',
    'wkt' => 'field_farm_geofield',
    'other_top' => 'name',
    'other_right' => 'name',
    'other_bottom' => 'name',
    'other_left' => 'name',
    'name_field' => 'name',
    'description_field' => 'description',
    'style_field' => '',
  );
  $handler->display->display_options['row_plugin'] = 'fields';
  /* Field: Taxonomy term: Name */
  $handler->display->display_options['fields']['name']['id'] = 'name';
  $handler->display->display_options['fields']['name']['table'] = 'taxonomy_term_data';
  $handler->display->display_options['fields']['name']['field'] = 'name';
  $handler->display->display_options['fields']['name']['label'] = '';
  $handler->display->display_options['fields']['name']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['name']['alter']['ellipsis'] = FALSE;
  $handler->display->display_options['fields']['name']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['name']['link_to_taxonomy'] = TRUE;
  /* Field: Taxonomy term: Term description */
  $handler->display->display_options['fields']['description']['id'] = 'description';
  $handler->display->display_options['fields']['description']['table'] = 'taxonomy_term_data';
  $handler->display->display_options['fields']['description']['field'] = 'description';
  $handler->display->display_options['fields']['description']['label'] = '';
  $handler->display->display_options['fields']['description']['element_label_colon'] = FALSE;
  /* Field: Taxonomy term: Location */
  $handler->display->display_options['fields']['field_farm_geofield']['id'] = 'field_farm_geofield';
  $handler->display->display_options['fields']['field_farm_geofield']['table'] = 'field_data_field_farm_geofield';
  $handler->display->display_options['fields']['field_farm_geofield']['field'] = 'field_farm_geofield';
  $handler->display->display_options['fields']['field_farm_geofield']['label'] = '';
  $handler->display->display_options['fields']['field_farm_geofield']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_farm_geofield']['click_sort_column'] = 'geom';
  $handler->display->display_options['fields']['field_farm_geofield']['settings'] = array(
    'data' => 'full',
  );
  $handler->display->display_options['fields']['field_farm_geofield']['group_rows'] = FALSE;
  $handler->display->display_options['fields']['field_farm_geofield']['delta_offset'] = '0';
  /* Filter criterion: Taxonomy vocabulary: Machine name */
  $handler->display->display_options['filters']['machine_name']['id'] = 'machine_name';
  $handler->display->display_options['filters']['machine_name']['table'] = 'taxonomy_vocabulary';
  $handler->display->display_options['filters']['machine_name']['field'] = 'machine_name';
  $handler->display->display_options['filters']['machine_name']['value'] = array(
    'farm_areas' => 'farm_areas',
  );

  /* Display: Area Data */
  $handler = $view->new_display('openlayers', 'Area Data', 'openlayers_1');
  $handler->display->display_options['defaults']['row_plugin'] = FALSE;
  $handler->display->display_options['row_plugin'] = 'fields';

  /* Display: Page */
  $handler = $view->new_display('page', 'Page', 'page_1');
  $handler->display->display_options['defaults']['style_plugin'] = FALSE;
  $handler->display->display_options['style_plugin'] = 'openlayers_map';
  $handler->display->display_options['style_options']['map'] = 'farm_map_areas';
  $handler->display->display_options['defaults']['style_options'] = FALSE;
  $handler->display->display_options['defaults']['row_plugin'] = FALSE;
  $handler->display->display_options['defaults']['row_options'] = FALSE;
  $handler->display->display_options['path'] = 'farm/areas';
  $export['farm_areas'] = $view;

  return $export;
}
