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
  $view->tag = 'farm_area';
  $view->base_table = 'taxonomy_term_data';
  $view->human_name = 'Farm Areas';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Areas';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'none';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['exposed_form']['options']['reset_button'] = TRUE;
  $handler->display->display_options['exposed_form']['options']['autosubmit'] = TRUE;
  $handler->display->display_options['pager']['type'] = 'none';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['style_plugin'] = 'tree';
  $handler->display->display_options['style_options']['main_field'] = 'tid';
  $handler->display->display_options['style_options']['parent_field'] = 'tid_1';
  $handler->display->display_options['row_plugin'] = 'fields';
  $handler->display->display_options['row_options']['inline'] = array(
    'name' => 'name',
    'field_farm_area_type' => 'field_farm_area_type',
  );
  /* Header: Global: Unfiltered text */
  $handler->display->display_options['header']['area_text_custom']['id'] = 'area_text_custom';
  $handler->display->display_options['header']['area_text_custom']['table'] = 'views';
  $handler->display->display_options['header']['area_text_custom']['field'] = 'area_text_custom';
  $handler->display->display_options['header']['area_text_custom']['content'] = '<h4>Hierarchy <small><a href="/admin/structure/taxonomy/farm_areas?destination=farm/areas">(change)</a></small></h4>';
  /* No results behavior: Global: Unfiltered text */
  $handler->display->display_options['empty']['area_text_custom']['id'] = 'area_text_custom';
  $handler->display->display_options['empty']['area_text_custom']['table'] = 'views';
  $handler->display->display_options['empty']['area_text_custom']['field'] = 'area_text_custom';
  $handler->display->display_options['empty']['area_text_custom']['empty'] = TRUE;
  $handler->display->display_options['empty']['area_text_custom']['content'] = 'No areas found.';
  /* Relationship: Taxonomy term: Parent term */
  $handler->display->display_options['relationships']['parent']['id'] = 'parent';
  $handler->display->display_options['relationships']['parent']['table'] = 'taxonomy_term_hierarchy';
  $handler->display->display_options['relationships']['parent']['field'] = 'parent';
  /* Field: Taxonomy term: Term ID */
  $handler->display->display_options['fields']['tid']['id'] = 'tid';
  $handler->display->display_options['fields']['tid']['table'] = 'taxonomy_term_data';
  $handler->display->display_options['fields']['tid']['field'] = 'tid';
  $handler->display->display_options['fields']['tid']['exclude'] = TRUE;
  /* Field: Taxonomy term: Term ID */
  $handler->display->display_options['fields']['tid_1']['id'] = 'tid_1';
  $handler->display->display_options['fields']['tid_1']['table'] = 'taxonomy_term_data';
  $handler->display->display_options['fields']['tid_1']['field'] = 'tid';
  $handler->display->display_options['fields']['tid_1']['relationship'] = 'parent';
  $handler->display->display_options['fields']['tid_1']['label'] = '';
  $handler->display->display_options['fields']['tid_1']['exclude'] = TRUE;
  $handler->display->display_options['fields']['tid_1']['element_label_colon'] = FALSE;
  /* Field: Taxonomy term: Name */
  $handler->display->display_options['fields']['name']['id'] = 'name';
  $handler->display->display_options['fields']['name']['table'] = 'taxonomy_term_data';
  $handler->display->display_options['fields']['name']['field'] = 'name';
  $handler->display->display_options['fields']['name']['label'] = '';
  $handler->display->display_options['fields']['name']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['name']['alter']['ellipsis'] = FALSE;
  $handler->display->display_options['fields']['name']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['name']['link_to_taxonomy'] = TRUE;
  /* Field: Taxonomy term: Area type */
  $handler->display->display_options['fields']['field_farm_area_type']['id'] = 'field_farm_area_type';
  $handler->display->display_options['fields']['field_farm_area_type']['table'] = 'field_data_field_farm_area_type';
  $handler->display->display_options['fields']['field_farm_area_type']['field'] = 'field_farm_area_type';
  $handler->display->display_options['fields']['field_farm_area_type']['label'] = '';
  $handler->display->display_options['fields']['field_farm_area_type']['alter']['alter_text'] = TRUE;
  $handler->display->display_options['fields']['field_farm_area_type']['alter']['text'] = '([field_farm_area_type])';
  $handler->display->display_options['fields']['field_farm_area_type']['element_label_colon'] = FALSE;
  /* Filter criterion: Taxonomy term: Vocabulary */
  $handler->display->display_options['filters']['vid']['id'] = 'vid';
  $handler->display->display_options['filters']['vid']['table'] = 'taxonomy_term_data';
  $handler->display->display_options['filters']['vid']['field'] = 'vid';
  $handler->display->display_options['filters']['vid']['value'] = array(
    1 => '1',
  );
  $handler->display->display_options['filters']['vid']['group'] = 1;

  /* Display: Page */
  $handler = $view->new_display('page', 'Page', 'page');
  $handler->display->display_options['path'] = 'farm/areas/list';
  $handler->display->display_options['menu']['type'] = 'default tab';
  $handler->display->display_options['menu']['title'] = 'Areas';
  $handler->display->display_options['menu']['weight'] = '-100';
  $handler->display->display_options['menu']['name'] = 'farm';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;
  $handler->display->display_options['tab_options']['type'] = 'normal';
  $handler->display->display_options['tab_options']['title'] = 'Areas';
  $handler->display->display_options['tab_options']['weight'] = '0';
  $handler->display->display_options['tab_options']['name'] = 'farm';
  $export['farm_areas'] = $view;

  $view = new view();
  $view->name = 'farm_areas_geojson';
  $view->description = '';
  $view->tag = 'default';
  $view->base_table = 'taxonomy_term_data';
  $view->human_name = 'Farm Areas GeoJSON';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'none';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['pager']['type'] = 'none';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['style_plugin'] = 'default';
  $handler->display->display_options['row_plugin'] = 'fields';
  /* Field: Taxonomy term: Name */
  $handler->display->display_options['fields']['name']['id'] = 'name';
  $handler->display->display_options['fields']['name']['table'] = 'taxonomy_term_data';
  $handler->display->display_options['fields']['name']['field'] = 'name';
  $handler->display->display_options['fields']['name']['label'] = '';
  $handler->display->display_options['fields']['name']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['name']['alter']['ellipsis'] = FALSE;
  $handler->display->display_options['fields']['name']['link_to_taxonomy'] = TRUE;
  /* Field: Field: Geometry */
  $handler->display->display_options['fields']['field_farm_geofield']['id'] = 'field_farm_geofield';
  $handler->display->display_options['fields']['field_farm_geofield']['table'] = 'field_data_field_farm_geofield';
  $handler->display->display_options['fields']['field_farm_geofield']['field'] = 'field_farm_geofield';
  $handler->display->display_options['fields']['field_farm_geofield']['click_sort_column'] = 'geom';
  $handler->display->display_options['fields']['field_farm_geofield']['settings'] = array(
    'data' => 'full',
  );
  $handler->display->display_options['fields']['field_farm_geofield']['delta_offset'] = '0';
  /* Field: Taxonomy term: Area Details */
  $handler->display->display_options['fields']['farm_area_details']['id'] = 'farm_area_details';
  $handler->display->display_options['fields']['farm_area_details']['table'] = 'taxonomy_term_data';
  $handler->display->display_options['fields']['farm_area_details']['field'] = 'farm_area_details';
  /* Contextual filter: Taxonomy term: Area type (field_farm_area_type) */
  $handler->display->display_options['arguments']['field_farm_area_type_value']['id'] = 'field_farm_area_type_value';
  $handler->display->display_options['arguments']['field_farm_area_type_value']['table'] = 'field_data_field_farm_area_type';
  $handler->display->display_options['arguments']['field_farm_area_type_value']['field'] = 'field_farm_area_type_value';
  $handler->display->display_options['arguments']['field_farm_area_type_value']['default_argument_type'] = 'raw';
  $handler->display->display_options['arguments']['field_farm_area_type_value']['default_argument_options']['index'] = '3';
  $handler->display->display_options['arguments']['field_farm_area_type_value']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['field_farm_area_type_value']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['field_farm_area_type_value']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['arguments']['field_farm_area_type_value']['limit'] = '0';
  /* Filter criterion: Taxonomy vocabulary: Machine name */
  $handler->display->display_options['filters']['machine_name']['id'] = 'machine_name';
  $handler->display->display_options['filters']['machine_name']['table'] = 'taxonomy_vocabulary';
  $handler->display->display_options['filters']['machine_name']['field'] = 'machine_name';
  $handler->display->display_options['filters']['machine_name']['value'] = array(
    'farm_areas' => 'farm_areas',
  );
  /* Filter criterion: Taxonomy term: Area type (field_farm_area_type) */
  $handler->display->display_options['filters']['field_farm_area_type_value']['id'] = 'field_farm_area_type_value';
  $handler->display->display_options['filters']['field_farm_area_type_value']['table'] = 'field_data_field_farm_area_type';
  $handler->display->display_options['filters']['field_farm_area_type_value']['field'] = 'field_farm_area_type_value';
  $handler->display->display_options['filters']['field_farm_area_type_value']['operator'] = 'not empty';

  /* Display: Feed */
  $handler = $view->new_display('feed', 'Feed', 'feed');
  $handler->display->display_options['pager']['type'] = 'none';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['style_plugin'] = 'views_geojson_feed';
  $handler->display->display_options['style_options']['data_source']['value'] = 'wkt';
  $handler->display->display_options['style_options']['data_source']['latitude'] = 'name';
  $handler->display->display_options['style_options']['data_source']['longitude'] = 'name';
  $handler->display->display_options['style_options']['data_source']['geofield'] = 'field_farm_geofield';
  $handler->display->display_options['style_options']['data_source']['wkt'] = 'field_farm_geofield';
  $handler->display->display_options['style_options']['data_source']['name_field'] = 'name';
  $handler->display->display_options['style_options']['data_source']['description_field'] = 'farm_area_details';
  $handler->display->display_options['style_options']['jsonp_prefix'] = '';
  $handler->display->display_options['path'] = 'farm/areas/json';
  $export['farm_areas_geojson'] = $view;

  return $export;
}
