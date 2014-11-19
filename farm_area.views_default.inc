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
  $view->name = 'farm_area_data';
  $view->description = '';
  $view->tag = 'default';
  $view->base_table = 'taxonomy_term_data';
  $view->human_name = 'Farm Area Data';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Areas';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['access']['perm'] = 'edit terms in farm_areas';
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
    'description_field' => 'field_farm_area_type',
  );
  $handler->display->display_options['row_plugin'] = 'fields';
  /* Header: Global: Unfiltered text */
  $handler->display->display_options['header']['area_text_custom']['id'] = 'area_text_custom';
  $handler->display->display_options['header']['area_text_custom']['table'] = 'views';
  $handler->display->display_options['header']['area_text_custom']['field'] = 'area_text_custom';
  $handler->display->display_options['header']['area_text_custom']['empty'] = TRUE;
  $handler->display->display_options['header']['area_text_custom']['content'] = '<a href="/admin/structure/taxonomy/farm_areas/add?destination=admin/farm/areas">+ Add an area</a>';
  /* Field: Taxonomy term: Term ID */
  $handler->display->display_options['fields']['tid']['id'] = 'tid';
  $handler->display->display_options['fields']['tid']['table'] = 'taxonomy_term_data';
  $handler->display->display_options['fields']['tid']['field'] = 'tid';
  $handler->display->display_options['fields']['tid']['label'] = '';
  $handler->display->display_options['fields']['tid']['exclude'] = TRUE;
  $handler->display->display_options['fields']['tid']['element_label_colon'] = FALSE;
  /* Field: Field: Location */
  $handler->display->display_options['fields']['field_farm_geofield']['id'] = 'field_farm_geofield';
  $handler->display->display_options['fields']['field_farm_geofield']['table'] = 'field_data_field_farm_geofield';
  $handler->display->display_options['fields']['field_farm_geofield']['field'] = 'field_farm_geofield';
  $handler->display->display_options['fields']['field_farm_geofield']['label'] = '';
  $handler->display->display_options['fields']['field_farm_geofield']['exclude'] = TRUE;
  $handler->display->display_options['fields']['field_farm_geofield']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_farm_geofield']['click_sort_column'] = 'geom';
  $handler->display->display_options['fields']['field_farm_geofield']['settings'] = array(
    'data' => 'full',
  );
  $handler->display->display_options['fields']['field_farm_geofield']['delta_offset'] = '0';
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
  $handler->display->display_options['fields']['field_farm_area_type']['element_label_colon'] = FALSE;
  /* Filter criterion: Taxonomy term: Vocabulary */
  $handler->display->display_options['filters']['vid']['id'] = 'vid';
  $handler->display->display_options['filters']['vid']['table'] = 'taxonomy_term_data';
  $handler->display->display_options['filters']['vid']['field'] = 'vid';
  $handler->display->display_options['filters']['vid']['value'] = array(
    1 => '1',
  );

  /* Display: All area data (OpenLayers Data Overlay) */
  $handler = $view->new_display('openlayers', 'All area data (OpenLayers Data Overlay)', 'all');

  /* Display: Property area data (OpenLayers Data Overlay) */
  $handler = $view->new_display('openlayers', 'Property area data (OpenLayers Data Overlay)', 'property');
  $handler->display->display_options['defaults']['title'] = FALSE;
  $handler->display->display_options['title'] = 'Properties';
  $handler->display->display_options['defaults']['filter_groups'] = FALSE;
  $handler->display->display_options['defaults']['filters'] = FALSE;
  /* Filter criterion: Taxonomy term: Vocabulary */
  $handler->display->display_options['filters']['vid']['id'] = 'vid';
  $handler->display->display_options['filters']['vid']['table'] = 'taxonomy_term_data';
  $handler->display->display_options['filters']['vid']['field'] = 'vid';
  $handler->display->display_options['filters']['vid']['value'] = array(
    1 => '1',
  );
  /* Filter criterion: Taxonomy term: Area type (field_farm_area_type) */
  $handler->display->display_options['filters']['field_farm_area_type_value']['id'] = 'field_farm_area_type_value';
  $handler->display->display_options['filters']['field_farm_area_type_value']['table'] = 'field_data_field_farm_area_type';
  $handler->display->display_options['filters']['field_farm_area_type_value']['field'] = 'field_farm_area_type_value';
  $handler->display->display_options['filters']['field_farm_area_type_value']['value'] = array(
    'property' => 'property',
  );

  /* Display: Field area data (OpenLayers Data Overlay) */
  $handler = $view->new_display('openlayers', 'Field area data (OpenLayers Data Overlay)', 'field');
  $handler->display->display_options['defaults']['title'] = FALSE;
  $handler->display->display_options['title'] = 'Fields';
  $handler->display->display_options['defaults']['filter_groups'] = FALSE;
  $handler->display->display_options['defaults']['filters'] = FALSE;
  /* Filter criterion: Taxonomy term: Vocabulary */
  $handler->display->display_options['filters']['vid']['id'] = 'vid';
  $handler->display->display_options['filters']['vid']['table'] = 'taxonomy_term_data';
  $handler->display->display_options['filters']['vid']['field'] = 'vid';
  $handler->display->display_options['filters']['vid']['value'] = array(
    1 => '1',
  );
  /* Filter criterion: Taxonomy term: Area type (field_farm_area_type) */
  $handler->display->display_options['filters']['field_farm_area_type_value']['id'] = 'field_farm_area_type_value';
  $handler->display->display_options['filters']['field_farm_area_type_value']['table'] = 'field_data_field_farm_area_type';
  $handler->display->display_options['filters']['field_farm_area_type_value']['field'] = 'field_farm_area_type_value';
  $handler->display->display_options['filters']['field_farm_area_type_value']['value'] = array(
    'field' => 'field',
  );

  /* Display: Building area data (OpenLayers Data Overlay) */
  $handler = $view->new_display('openlayers', 'Building area data (OpenLayers Data Overlay)', 'building');
  $handler->display->display_options['defaults']['title'] = FALSE;
  $handler->display->display_options['title'] = 'Buildings';
  $handler->display->display_options['defaults']['filter_groups'] = FALSE;
  $handler->display->display_options['defaults']['filters'] = FALSE;
  /* Filter criterion: Taxonomy term: Vocabulary */
  $handler->display->display_options['filters']['vid']['id'] = 'vid';
  $handler->display->display_options['filters']['vid']['table'] = 'taxonomy_term_data';
  $handler->display->display_options['filters']['vid']['field'] = 'vid';
  $handler->display->display_options['filters']['vid']['value'] = array(
    1 => '1',
  );
  /* Filter criterion: Taxonomy term: Area type (field_farm_area_type) */
  $handler->display->display_options['filters']['field_farm_area_type_value']['id'] = 'field_farm_area_type_value';
  $handler->display->display_options['filters']['field_farm_area_type_value']['table'] = 'field_data_field_farm_area_type';
  $handler->display->display_options['filters']['field_farm_area_type_value']['field'] = 'field_farm_area_type_value';
  $handler->display->display_options['filters']['field_farm_area_type_value']['value'] = array(
    'building' => 'building',
  );

  /* Display: Greenhouse area data (OpenLayers Data Overlay) */
  $handler = $view->new_display('openlayers', 'Greenhouse area data (OpenLayers Data Overlay)', 'greenhouse');
  $handler->display->display_options['defaults']['title'] = FALSE;
  $handler->display->display_options['title'] = 'Greenhouses';
  $handler->display->display_options['defaults']['filter_groups'] = FALSE;
  $handler->display->display_options['defaults']['filters'] = FALSE;
  /* Filter criterion: Taxonomy term: Vocabulary */
  $handler->display->display_options['filters']['vid']['id'] = 'vid';
  $handler->display->display_options['filters']['vid']['table'] = 'taxonomy_term_data';
  $handler->display->display_options['filters']['vid']['field'] = 'vid';
  $handler->display->display_options['filters']['vid']['value'] = array(
    1 => '1',
  );
  /* Filter criterion: Taxonomy term: Area type (field_farm_area_type) */
  $handler->display->display_options['filters']['field_farm_area_type_value']['id'] = 'field_farm_area_type_value';
  $handler->display->display_options['filters']['field_farm_area_type_value']['table'] = 'field_data_field_farm_area_type';
  $handler->display->display_options['filters']['field_farm_area_type_value']['field'] = 'field_farm_area_type_value';
  $handler->display->display_options['filters']['field_farm_area_type_value']['value'] = array(
    'greenhouse' => 'greenhouse',
  );

  /* Display: Water area data (OpenLayers Data Overlay) */
  $handler = $view->new_display('openlayers', 'Water area data (OpenLayers Data Overlay)', 'water');
  $handler->display->display_options['defaults']['title'] = FALSE;
  $handler->display->display_options['title'] = 'Water features';
  $handler->display->display_options['defaults']['filter_groups'] = FALSE;
  $handler->display->display_options['defaults']['filters'] = FALSE;
  /* Filter criterion: Taxonomy term: Vocabulary */
  $handler->display->display_options['filters']['vid']['id'] = 'vid';
  $handler->display->display_options['filters']['vid']['table'] = 'taxonomy_term_data';
  $handler->display->display_options['filters']['vid']['field'] = 'vid';
  $handler->display->display_options['filters']['vid']['value'] = array(
    1 => '1',
  );
  /* Filter criterion: Taxonomy term: Area type (field_farm_area_type) */
  $handler->display->display_options['filters']['field_farm_area_type_value']['id'] = 'field_farm_area_type_value';
  $handler->display->display_options['filters']['field_farm_area_type_value']['table'] = 'field_data_field_farm_area_type';
  $handler->display->display_options['filters']['field_farm_area_type_value']['field'] = 'field_farm_area_type_value';
  $handler->display->display_options['filters']['field_farm_area_type_value']['value'] = array(
    'water' => 'water',
  );

  /* Display: Landmark area data (OpenLayers Data Overlay) */
  $handler = $view->new_display('openlayers', 'Landmark area data (OpenLayers Data Overlay)', 'landmark');
  $handler->display->display_options['defaults']['title'] = FALSE;
  $handler->display->display_options['title'] = 'Landmarks';
  $handler->display->display_options['defaults']['filter_groups'] = FALSE;
  $handler->display->display_options['defaults']['filters'] = FALSE;
  /* Filter criterion: Taxonomy term: Vocabulary */
  $handler->display->display_options['filters']['vid']['id'] = 'vid';
  $handler->display->display_options['filters']['vid']['table'] = 'taxonomy_term_data';
  $handler->display->display_options['filters']['vid']['field'] = 'vid';
  $handler->display->display_options['filters']['vid']['value'] = array(
    1 => '1',
  );
  /* Filter criterion: Taxonomy term: Area type (field_farm_area_type) */
  $handler->display->display_options['filters']['field_farm_area_type_value']['id'] = 'field_farm_area_type_value';
  $handler->display->display_options['filters']['field_farm_area_type_value']['table'] = 'field_data_field_farm_area_type';
  $handler->display->display_options['filters']['field_farm_area_type_value']['field'] = 'field_farm_area_type_value';
  $handler->display->display_options['filters']['field_farm_area_type_value']['value'] = array(
    'landmark' => 'landmark',
  );

  /* Display: Single area data (OpenLayers Data Overlay) */
  $handler = $view->new_display('openlayers', 'Single area data (OpenLayers Data Overlay)', 'single');
  $handler->display->display_options['defaults']['arguments'] = FALSE;
  /* Contextual filter: Taxonomy term: Term ID */
  $handler->display->display_options['arguments']['tid']['id'] = 'tid';
  $handler->display->display_options['arguments']['tid']['table'] = 'taxonomy_term_data';
  $handler->display->display_options['arguments']['tid']['field'] = 'tid';
  $handler->display->display_options['arguments']['tid']['default_action'] = 'default';
  $handler->display->display_options['arguments']['tid']['default_argument_type'] = 'taxonomy_tid';
  $handler->display->display_options['arguments']['tid']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['tid']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['tid']['summary_options']['items_per_page'] = '25';
  $export['farm_area_data'] = $view;

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
  $handler->display->display_options['title'] = 'Areas';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['access']['perm'] = 'edit terms in farm_areas';
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
  /* Header: Global: Unfiltered text */
  $handler->display->display_options['header']['area_text_custom']['id'] = 'area_text_custom';
  $handler->display->display_options['header']['area_text_custom']['table'] = 'views';
  $handler->display->display_options['header']['area_text_custom']['field'] = 'area_text_custom';
  $handler->display->display_options['header']['area_text_custom']['empty'] = TRUE;
  $handler->display->display_options['header']['area_text_custom']['content'] = '<a href="/admin/structure/taxonomy/farm_areas/add?destination=admin/farm/areas">+ Add an area</a>';
  /* Field: Taxonomy term: Term ID */
  $handler->display->display_options['fields']['tid']['id'] = 'tid';
  $handler->display->display_options['fields']['tid']['table'] = 'taxonomy_term_data';
  $handler->display->display_options['fields']['tid']['field'] = 'tid';
  $handler->display->display_options['fields']['tid']['exclude'] = TRUE;
  /* Field: Taxonomy term: Name */
  $handler->display->display_options['fields']['name']['id'] = 'name';
  $handler->display->display_options['fields']['name']['table'] = 'taxonomy_term_data';
  $handler->display->display_options['fields']['name']['field'] = 'name';
  $handler->display->display_options['fields']['name']['label'] = 'Area name';
  $handler->display->display_options['fields']['name']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['name']['alter']['ellipsis'] = FALSE;
  $handler->display->display_options['fields']['name']['link_to_taxonomy'] = TRUE;
  /* Field: Taxonomy term: Area type */
  $handler->display->display_options['fields']['field_farm_area_type']['id'] = 'field_farm_area_type';
  $handler->display->display_options['fields']['field_farm_area_type']['table'] = 'field_data_field_farm_area_type';
  $handler->display->display_options['fields']['field_farm_area_type']['field'] = 'field_farm_area_type';
  /* Field: Global: Custom text */
  $handler->display->display_options['fields']['nothing']['id'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['table'] = 'views';
  $handler->display->display_options['fields']['nothing']['field'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['label'] = 'Operations';
  $handler->display->display_options['fields']['nothing']['alter']['text'] = '<a href="/taxonomy/term/[tid]/edit">edit</a>';
  /* Filter criterion: Taxonomy term: Vocabulary */
  $handler->display->display_options['filters']['vid']['id'] = 'vid';
  $handler->display->display_options['filters']['vid']['table'] = 'taxonomy_term_data';
  $handler->display->display_options['filters']['vid']['field'] = 'vid';
  $handler->display->display_options['filters']['vid']['value'] = array(
    1 => '1',
  );

  /* Display: All areas (Page) */
  $handler = $view->new_display('page', 'All areas (Page)', 'page_1');
  $handler->display->display_options['defaults']['style_plugin'] = FALSE;
  $handler->display->display_options['style_plugin'] = 'openlayers_map';
  $handler->display->display_options['style_options']['map'] = 'farm_map_areas';
  $handler->display->display_options['defaults']['style_options'] = FALSE;
  $handler->display->display_options['defaults']['row_plugin'] = FALSE;
  $handler->display->display_options['defaults']['row_options'] = FALSE;
  $handler->display->display_options['path'] = 'admin/farm/areas/list';
  $handler->display->display_options['menu']['type'] = 'default tab';
  $handler->display->display_options['menu']['title'] = 'Areas';
  $handler->display->display_options['menu']['weight'] = '-10';
  $handler->display->display_options['menu']['name'] = 'management';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;
  $handler->display->display_options['tab_options']['type'] = 'tab';
  $handler->display->display_options['tab_options']['title'] = 'Areas';
  $handler->display->display_options['tab_options']['weight'] = '10';

  /* Display: All areas (Content pane) */
  $handler = $view->new_display('panel_pane', 'All areas (Content pane)', 'panel_pane_1');
  $handler->display->display_options['defaults']['style_plugin'] = FALSE;
  $handler->display->display_options['style_plugin'] = 'openlayers_map';
  $handler->display->display_options['style_options']['map'] = 'farm_map_areas';
  $handler->display->display_options['defaults']['style_options'] = FALSE;
  $handler->display->display_options['defaults']['row_plugin'] = FALSE;
  $handler->display->display_options['defaults']['row_options'] = FALSE;
  $handler->display->display_options['link_to_view'] = '0';

  /* Display: All areas (Attachment) */
  $handler = $view->new_display('attachment', 'All areas (Attachment)', 'attachment_1');
  $handler->display->display_options['pager']['type'] = 'none';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['defaults']['style_plugin'] = FALSE;
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['columns'] = array(
    'name' => 'name',
    'field_farm_area_type' => 'field_farm_area_type',
    'field_farm_areas' => 'field_farm_areas',
  );
  $handler->display->display_options['style_options']['default'] = 'name';
  $handler->display->display_options['style_options']['info'] = array(
    'name' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_area_type' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_areas' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
  );
  $handler->display->display_options['style_options']['empty_table'] = TRUE;
  $handler->display->display_options['defaults']['style_options'] = FALSE;
  $handler->display->display_options['defaults']['row_plugin'] = FALSE;
  $handler->display->display_options['defaults']['row_options'] = FALSE;
  $handler->display->display_options['defaults']['footer'] = FALSE;
  $handler->display->display_options['displays'] = array(
    'page_1' => 'page_1',
    'panel_pane_1' => 'panel_pane_1',
    'default' => 0,
  );
  $handler->display->display_options['attachment_position'] = 'after';
  $handler->display->display_options['inherit_exposed_filters'] = TRUE;
  $export['farm_areas'] = $view;

  return $export;
}
