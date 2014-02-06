<?php
/**
 * @file
 * farm_log.views_default.inc
 */

/**
 * Implements hook_views_default_views().
 */
function farm_log_views_default_views() {
  $export = array();

  $view = new view();
  $view->name = 'farm_log_issues';
  $view->description = '';
  $view->tag = 'default';
  $view->base_table = 'log';
  $view->human_name = 'Farm Issue Log';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Logs';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['access']['perm'] = 'view any log entities';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['pager']['options']['items_per_page'] = '100';
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['columns'] = array(
    'lid' => 'lid',
    'timestamp' => 'timestamp',
    'type' => 'type',
    'name' => 'name',
    'nothing' => 'nothing',
  );
  $handler->display->display_options['style_options']['default'] = '-1';
  $handler->display->display_options['style_options']['info'] = array(
    'lid' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'timestamp' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'type' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'name' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'nothing' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
  );
  /* Header: Global: Unfiltered text */
  $handler->display->display_options['header']['area_text_custom']['id'] = 'area_text_custom';
  $handler->display->display_options['header']['area_text_custom']['table'] = 'views';
  $handler->display->display_options['header']['area_text_custom']['field'] = 'area_text_custom';
  $handler->display->display_options['header']['area_text_custom']['empty'] = TRUE;
  $handler->display->display_options['header']['area_text_custom']['content'] = '<a href="/log/add/farm_issue">+ Log an issue</a>';
  /* Field: Log: Log ID */
  $handler->display->display_options['fields']['lid']['id'] = 'lid';
  $handler->display->display_options['fields']['lid']['table'] = 'log';
  $handler->display->display_options['fields']['lid']['field'] = 'lid';
  $handler->display->display_options['fields']['lid']['exclude'] = TRUE;
  /* Field: Log: Timestamp */
  $handler->display->display_options['fields']['timestamp']['id'] = 'timestamp';
  $handler->display->display_options['fields']['timestamp']['table'] = 'log';
  $handler->display->display_options['fields']['timestamp']['field'] = 'timestamp';
  $handler->display->display_options['fields']['timestamp']['date_format'] = 'short';
  /* Field: Log: Name */
  $handler->display->display_options['fields']['name']['id'] = 'name';
  $handler->display->display_options['fields']['name']['table'] = 'log';
  $handler->display->display_options['fields']['name']['field'] = 'name';
  $handler->display->display_options['fields']['name']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['name']['alter']['path'] = 'log/[lid]';
  /* Field: Log: Crops */
  $handler->display->display_options['fields']['field_farm_crops']['id'] = 'field_farm_crops';
  $handler->display->display_options['fields']['field_farm_crops']['table'] = 'field_data_field_farm_crops';
  $handler->display->display_options['fields']['field_farm_crops']['field'] = 'field_farm_crops';
  $handler->display->display_options['fields']['field_farm_crops']['delta_offset'] = '0';
  /* Field: Field: Fields/Beds */
  $handler->display->display_options['fields']['field_farm_areas']['id'] = 'field_farm_areas';
  $handler->display->display_options['fields']['field_farm_areas']['table'] = 'field_data_field_farm_areas';
  $handler->display->display_options['fields']['field_farm_areas']['field'] = 'field_farm_areas';
  $handler->display->display_options['fields']['field_farm_areas']['delta_offset'] = '0';
  /* Field: Log: Threats */
  $handler->display->display_options['fields']['field_farm_threats']['id'] = 'field_farm_threats';
  $handler->display->display_options['fields']['field_farm_threats']['table'] = 'field_data_field_farm_threats';
  $handler->display->display_options['fields']['field_farm_threats']['field'] = 'field_farm_threats';
  $handler->display->display_options['fields']['field_farm_threats']['delta_offset'] = '0';
  /* Field: Log: Related Issues */
  $handler->display->display_options['fields']['field_farm_issues']['id'] = 'field_farm_issues';
  $handler->display->display_options['fields']['field_farm_issues']['table'] = 'field_data_field_farm_issues';
  $handler->display->display_options['fields']['field_farm_issues']['field'] = 'field_farm_issues';
  $handler->display->display_options['fields']['field_farm_issues']['settings'] = array(
    'link' => 1,
  );
  $handler->display->display_options['fields']['field_farm_issues']['delta_offset'] = '0';
  /* Field: Log: Issue severity */
  $handler->display->display_options['fields']['field_farm_issue_severity']['id'] = 'field_farm_issue_severity';
  $handler->display->display_options['fields']['field_farm_issue_severity']['table'] = 'field_data_field_farm_issue_severity';
  $handler->display->display_options['fields']['field_farm_issue_severity']['field'] = 'field_farm_issue_severity';
  $handler->display->display_options['fields']['field_farm_issue_severity']['label'] = 'Severity';
  /* Field: Global: Custom text */
  $handler->display->display_options['fields']['nothing']['id'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['table'] = 'views';
  $handler->display->display_options['fields']['nothing']['field'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['label'] = 'Operations';
  $handler->display->display_options['fields']['nothing']['alter']['text'] = '<a href="/log/[lid]/edit">Edit</a> | <a href="/log/[lid]/delete">Delete</a>';
  /* Sort criterion: Log: Timestamp */
  $handler->display->display_options['sorts']['timestamp']['id'] = 'timestamp';
  $handler->display->display_options['sorts']['timestamp']['table'] = 'log';
  $handler->display->display_options['sorts']['timestamp']['field'] = 'timestamp';
  $handler->display->display_options['sorts']['timestamp']['order'] = 'DESC';
  $handler->display->display_options['sorts']['timestamp']['expose']['label'] = 'Timestamp';
  $handler->display->display_options['sorts']['timestamp']['granularity'] = 'minute';
  /* Filter criterion: Log: Name */
  $handler->display->display_options['filters']['name']['id'] = 'name';
  $handler->display->display_options['filters']['name']['table'] = 'log';
  $handler->display->display_options['filters']['name']['field'] = 'name';
  $handler->display->display_options['filters']['name']['operator'] = 'contains';
  $handler->display->display_options['filters']['name']['group'] = 1;
  $handler->display->display_options['filters']['name']['exposed'] = TRUE;
  $handler->display->display_options['filters']['name']['expose']['operator_id'] = 'name_op';
  $handler->display->display_options['filters']['name']['expose']['label'] = 'Name';
  $handler->display->display_options['filters']['name']['expose']['use_operator'] = TRUE;
  $handler->display->display_options['filters']['name']['expose']['operator'] = 'name_op';
  $handler->display->display_options['filters']['name']['expose']['identifier'] = 'name';
  $handler->display->display_options['filters']['name']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
  );
  /* Filter criterion: Log: Timestamp */
  $handler->display->display_options['filters']['timestamp']['id'] = 'timestamp';
  $handler->display->display_options['filters']['timestamp']['table'] = 'log';
  $handler->display->display_options['filters']['timestamp']['field'] = 'timestamp';
  $handler->display->display_options['filters']['timestamp']['group'] = 1;
  $handler->display->display_options['filters']['timestamp']['exposed'] = TRUE;
  $handler->display->display_options['filters']['timestamp']['expose']['operator_id'] = 'timestamp_op';
  $handler->display->display_options['filters']['timestamp']['expose']['label'] = 'Timestamp';
  $handler->display->display_options['filters']['timestamp']['expose']['use_operator'] = TRUE;
  $handler->display->display_options['filters']['timestamp']['expose']['operator'] = 'timestamp_op';
  $handler->display->display_options['filters']['timestamp']['expose']['identifier'] = 'timestamp';
  $handler->display->display_options['filters']['timestamp']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
  );
  /* Filter criterion: Log: Crops (field_farm_crops) */
  $handler->display->display_options['filters']['field_farm_crops_tid']['id'] = 'field_farm_crops_tid';
  $handler->display->display_options['filters']['field_farm_crops_tid']['table'] = 'field_data_field_farm_crops';
  $handler->display->display_options['filters']['field_farm_crops_tid']['field'] = 'field_farm_crops_tid';
  $handler->display->display_options['filters']['field_farm_crops_tid']['value'] = '';
  $handler->display->display_options['filters']['field_farm_crops_tid']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_crops_tid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_crops_tid']['expose']['operator_id'] = 'field_farm_crops_tid_op';
  $handler->display->display_options['filters']['field_farm_crops_tid']['expose']['label'] = 'Crops';
  $handler->display->display_options['filters']['field_farm_crops_tid']['expose']['operator'] = 'field_farm_crops_tid_op';
  $handler->display->display_options['filters']['field_farm_crops_tid']['expose']['identifier'] = 'field_farm_crops_tid';
  $handler->display->display_options['filters']['field_farm_crops_tid']['expose']['multiple'] = TRUE;
  $handler->display->display_options['filters']['field_farm_crops_tid']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
  );
  $handler->display->display_options['filters']['field_farm_crops_tid']['vocabulary'] = 'farm_crops';
  /* Filter criterion: Field: Fields/Beds (field_farm_areas) */
  $handler->display->display_options['filters']['field_farm_areas_tid']['id'] = 'field_farm_areas_tid';
  $handler->display->display_options['filters']['field_farm_areas_tid']['table'] = 'field_data_field_farm_areas';
  $handler->display->display_options['filters']['field_farm_areas_tid']['field'] = 'field_farm_areas_tid';
  $handler->display->display_options['filters']['field_farm_areas_tid']['value'] = '';
  $handler->display->display_options['filters']['field_farm_areas_tid']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_areas_tid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_areas_tid']['expose']['operator_id'] = 'field_farm_areas_tid_op';
  $handler->display->display_options['filters']['field_farm_areas_tid']['expose']['label'] = 'Fields/Beds';
  $handler->display->display_options['filters']['field_farm_areas_tid']['expose']['operator'] = 'field_farm_areas_tid_op';
  $handler->display->display_options['filters']['field_farm_areas_tid']['expose']['identifier'] = 'field_farm_areas_tid';
  $handler->display->display_options['filters']['field_farm_areas_tid']['expose']['multiple'] = TRUE;
  $handler->display->display_options['filters']['field_farm_areas_tid']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
  );
  $handler->display->display_options['filters']['field_farm_areas_tid']['vocabulary'] = 'farm_areas';
  /* Filter criterion: Log: Threats (field_farm_threats) */
  $handler->display->display_options['filters']['field_farm_threats_tid']['id'] = 'field_farm_threats_tid';
  $handler->display->display_options['filters']['field_farm_threats_tid']['table'] = 'field_data_field_farm_threats';
  $handler->display->display_options['filters']['field_farm_threats_tid']['field'] = 'field_farm_threats_tid';
  $handler->display->display_options['filters']['field_farm_threats_tid']['value'] = '';
  $handler->display->display_options['filters']['field_farm_threats_tid']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_threats_tid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_threats_tid']['expose']['operator_id'] = 'field_farm_threats_tid_op';
  $handler->display->display_options['filters']['field_farm_threats_tid']['expose']['label'] = 'Threats';
  $handler->display->display_options['filters']['field_farm_threats_tid']['expose']['operator'] = 'field_farm_threats_tid_op';
  $handler->display->display_options['filters']['field_farm_threats_tid']['expose']['identifier'] = 'field_farm_threats_tid';
  $handler->display->display_options['filters']['field_farm_threats_tid']['expose']['multiple'] = TRUE;
  $handler->display->display_options['filters']['field_farm_threats_tid']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
  );
  $handler->display->display_options['filters']['field_farm_threats_tid']['vocabulary'] = 'farm_threats';
  /* Filter criterion: Log: Issue severity (field_farm_issue_severity) */
  $handler->display->display_options['filters']['field_farm_issue_severity_value']['id'] = 'field_farm_issue_severity_value';
  $handler->display->display_options['filters']['field_farm_issue_severity_value']['table'] = 'field_data_field_farm_issue_severity';
  $handler->display->display_options['filters']['field_farm_issue_severity_value']['field'] = 'field_farm_issue_severity_value';
  $handler->display->display_options['filters']['field_farm_issue_severity_value']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_issue_severity_value']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_issue_severity_value']['expose']['operator_id'] = 'field_farm_issue_severity_value_op';
  $handler->display->display_options['filters']['field_farm_issue_severity_value']['expose']['label'] = 'Severity';
  $handler->display->display_options['filters']['field_farm_issue_severity_value']['expose']['operator'] = 'field_farm_issue_severity_value_op';
  $handler->display->display_options['filters']['field_farm_issue_severity_value']['expose']['identifier'] = 'field_farm_issue_severity_value';
  $handler->display->display_options['filters']['field_farm_issue_severity_value']['expose']['multiple'] = TRUE;
  $handler->display->display_options['filters']['field_farm_issue_severity_value']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
  );

  /* Display: Issue log (page) */
  $handler = $view->new_display('page', 'Issue log (page)', 'page');
  $handler->display->display_options['defaults']['title'] = FALSE;
  $handler->display->display_options['title'] = 'Issue Log';
  $handler->display->display_options['defaults']['style_plugin'] = FALSE;
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['columns'] = array(
    'lid' => 'lid',
    'timestamp' => 'timestamp',
    'name' => 'name',
    'field_farm_crops' => 'field_farm_crops',
    'field_farm_areas' => 'field_farm_areas',
    'field_farm_issues' => 'field_farm_issues',
    'nothing' => 'nothing',
  );
  $handler->display->display_options['style_options']['default'] = '-1';
  $handler->display->display_options['style_options']['info'] = array(
    'lid' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'timestamp' => array(
      'sortable' => 1,
      'default_sort_order' => 'desc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'name' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_crops' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_areas' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_issues' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'nothing' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
  );
  $handler->display->display_options['defaults']['style_options'] = FALSE;
  $handler->display->display_options['defaults']['row_plugin'] = FALSE;
  $handler->display->display_options['defaults']['row_options'] = FALSE;
  $handler->display->display_options['path'] = 'logs/issue';
  $handler->display->display_options['menu']['type'] = 'tab';
  $handler->display->display_options['menu']['title'] = 'Issues';
  $handler->display->display_options['menu']['weight'] = '5';
  $handler->display->display_options['menu']['name'] = 'management';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;
  $handler->display->display_options['tab_options']['type'] = 'normal';
  $handler->display->display_options['tab_options']['title'] = 'Logs';
  $handler->display->display_options['tab_options']['weight'] = '0';
  $handler->display->display_options['tab_options']['name'] = 'management';
  $export['farm_log_issues'] = $view;

  return $export;
}
