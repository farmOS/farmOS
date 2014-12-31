<?php
/**
 * @file
 * farm_soil_test.views_default.inc
 */

/**
 * Implements hook_views_default_views().
 */
function farm_soil_test_views_default_views() {
  $export = array();

  $view = new view();
  $view->name = 'farm_log_soil_tests';
  $view->description = '';
  $view->tag = 'default';
  $view->base_table = 'log';
  $view->human_name = 'Farm Log: Soil Tests';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Soil Tests';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['access']['perm'] = 'view any farm_soil_test log entities';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['style_plugin'] = 'default';
  $handler->display->display_options['row_plugin'] = 'fields';
  /* Field: Log: Log ID */
  $handler->display->display_options['fields']['id']['id'] = 'id';
  $handler->display->display_options['fields']['id']['table'] = 'log';
  $handler->display->display_options['fields']['id']['field'] = 'id';
  $handler->display->display_options['fields']['id']['label'] = 'id';
  /* Field: Log: Date */
  $handler->display->display_options['fields']['field_farm_date']['id'] = 'field_farm_date';
  $handler->display->display_options['fields']['field_farm_date']['table'] = 'field_data_field_farm_date';
  $handler->display->display_options['fields']['field_farm_date']['field'] = 'field_farm_date';
  $handler->display->display_options['fields']['field_farm_date']['label'] = 'date';
  $handler->display->display_options['fields']['field_farm_date']['type'] = 'date_plain';
  $handler->display->display_options['fields']['field_farm_date']['settings'] = array(
    'format_type' => 'short',
    'fromto' => '',
    'multiple_number' => '',
    'multiple_from' => '',
    'multiple_to' => '',
  );
  /* Field: Log: Created */
  $handler->display->display_options['fields']['created']['id'] = 'created';
  $handler->display->display_options['fields']['created']['table'] = 'log';
  $handler->display->display_options['fields']['created']['field'] = 'created';
  $handler->display->display_options['fields']['created']['label'] = 'created';
  $handler->display->display_options['fields']['created']['date_format'] = 'custom';
  $handler->display->display_options['fields']['created']['custom_date_format'] = 'U';
  $handler->display->display_options['fields']['created']['second_date_format'] = 'long';
  /* Field: Log: Changed */
  $handler->display->display_options['fields']['changed']['id'] = 'changed';
  $handler->display->display_options['fields']['changed']['table'] = 'log';
  $handler->display->display_options['fields']['changed']['field'] = 'changed';
  $handler->display->display_options['fields']['changed']['label'] = 'changed';
  $handler->display->display_options['fields']['changed']['date_format'] = 'custom';
  $handler->display->display_options['fields']['changed']['custom_date_format'] = 'U';
  $handler->display->display_options['fields']['changed']['second_date_format'] = 'long';
  /* Field: Log: Name */
  $handler->display->display_options['fields']['name']['id'] = 'name';
  $handler->display->display_options['fields']['name']['table'] = 'log';
  $handler->display->display_options['fields']['name']['field'] = 'name';
  $handler->display->display_options['fields']['name']['label'] = 'name';
  /* Field: Log: Sample Points */
  $handler->display->display_options['fields']['field_farm_soil_sample_points']['id'] = 'field_farm_soil_sample_points';
  $handler->display->display_options['fields']['field_farm_soil_sample_points']['table'] = 'field_data_field_farm_soil_sample_points';
  $handler->display->display_options['fields']['field_farm_soil_sample_points']['field'] = 'field_farm_soil_sample_points';
  $handler->display->display_options['fields']['field_farm_soil_sample_points']['label'] = 'points';
  $handler->display->display_options['fields']['field_farm_soil_sample_points']['click_sort_column'] = 'geom';
  $handler->display->display_options['fields']['field_farm_soil_sample_points']['settings'] = array(
    'data' => 'full',
  );
  $handler->display->display_options['fields']['field_farm_soil_sample_points']['delta_offset'] = '0';
  /* Field: Log: Soil Types */
  $handler->display->display_options['fields']['field_farm_soil_types']['id'] = 'field_farm_soil_types';
  $handler->display->display_options['fields']['field_farm_soil_types']['table'] = 'field_data_field_farm_soil_types';
  $handler->display->display_options['fields']['field_farm_soil_types']['field'] = 'field_farm_soil_types';
  $handler->display->display_options['fields']['field_farm_soil_types']['label'] = 'soil_types';
  $handler->display->display_options['fields']['field_farm_soil_types']['delta_offset'] = '0';
  /* Field: Log: Notes */
  $handler->display->display_options['fields']['field_farm_notes']['id'] = 'field_farm_notes';
  $handler->display->display_options['fields']['field_farm_notes']['table'] = 'field_data_field_farm_notes';
  $handler->display->display_options['fields']['field_farm_notes']['field'] = 'field_farm_notes';
  $handler->display->display_options['fields']['field_farm_notes']['label'] = 'notes';
  /* Filter criterion: Log: Log type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'log';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'farm_soil_test' => 'farm_soil_test',
  );

  /* Display: Data export */
  $handler = $view->new_display('views_data_export', 'Data export', 'views_data_export_1');
  $handler->display->display_options['pager']['type'] = 'none';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['style_plugin'] = 'views_data_export_csv';
  $handler->display->display_options['style_options']['provide_file'] = 1;
  $handler->display->display_options['style_options']['parent_sort'] = 0;
  $handler->display->display_options['style_options']['quote'] = 1;
  $handler->display->display_options['style_options']['trim'] = 0;
  $handler->display->display_options['style_options']['replace_newlines'] = 0;
  $handler->display->display_options['style_options']['header'] = 1;
  $handler->display->display_options['style_options']['keep_html'] = 0;
  $handler->display->display_options['path'] = 'admin/farm/soil/tests/csv';
  $export['farm_log_soil_tests'] = $view;

  return $export;
}
