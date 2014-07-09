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
  $view->tag = 'farm_log';
  $view->base_table = 'log';
  $view->human_name = 'Farm Log: Issue';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Issues';
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
    'id' => 'id',
    'field_farm_date' => 'field_farm_date',
    'field_farm_threats' => 'field_farm_threats',
    'field_farm_crops' => 'field_farm_crops',
    'field_farm_areas' => 'field_farm_areas',
    'field_farm_priority' => 'field_farm_priority',
    'nothing' => 'nothing',
  );
  $handler->display->display_options['style_options']['default'] = 'field_farm_date';
  $handler->display->display_options['style_options']['info'] = array(
    'id' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_date' => array(
      'sortable' => 1,
      'default_sort_order' => 'desc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_threats' => array(
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
    'field_farm_priority' => array(
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
  $handler->display->display_options['style_options']['empty_table'] = TRUE;
  /* Footer: Global: Unfiltered text */
  $handler->display->display_options['footer']['area_text_custom']['id'] = 'area_text_custom';
  $handler->display->display_options['footer']['area_text_custom']['table'] = 'views';
  $handler->display->display_options['footer']['area_text_custom']['field'] = 'area_text_custom';
  $handler->display->display_options['footer']['area_text_custom']['empty'] = TRUE;
  $handler->display->display_options['footer']['area_text_custom']['content'] = '<a href="/log/add/farm_issue?destination=admin/farm/logs/issues">+ Log an issue</a>';
  /* No results behavior: Global: Unfiltered text */
  $handler->display->display_options['empty']['area_text_custom']['id'] = 'area_text_custom';
  $handler->display->display_options['empty']['area_text_custom']['table'] = 'views';
  $handler->display->display_options['empty']['area_text_custom']['field'] = 'area_text_custom';
  $handler->display->display_options['empty']['area_text_custom']['empty'] = TRUE;
  $handler->display->display_options['empty']['area_text_custom']['content'] = 'No issues found.';
  /* Field: Log: Log ID */
  $handler->display->display_options['fields']['id']['id'] = 'id';
  $handler->display->display_options['fields']['id']['table'] = 'log';
  $handler->display->display_options['fields']['id']['field'] = 'id';
  $handler->display->display_options['fields']['id']['exclude'] = TRUE;
  $handler->display->display_options['fields']['id']['separator'] = '';
  /* Field: Field: Date */
  $handler->display->display_options['fields']['field_farm_date']['id'] = 'field_farm_date';
  $handler->display->display_options['fields']['field_farm_date']['table'] = 'field_data_field_farm_date';
  $handler->display->display_options['fields']['field_farm_date']['field'] = 'field_farm_date';
  $handler->display->display_options['fields']['field_farm_date']['settings'] = array(
    'format_type' => 'short',
    'fromto' => 'both',
    'multiple_number' => '',
    'multiple_from' => '',
    'multiple_to' => '',
    'show_repeat_rule' => 'show',
  );
  /* Field: Log: Threats */
  $handler->display->display_options['fields']['field_farm_threats']['id'] = 'field_farm_threats';
  $handler->display->display_options['fields']['field_farm_threats']['table'] = 'field_data_field_farm_threats';
  $handler->display->display_options['fields']['field_farm_threats']['field'] = 'field_farm_threats';
  $handler->display->display_options['fields']['field_farm_threats']['delta_offset'] = '0';
  /* Field: Log: Crops */
  $handler->display->display_options['fields']['field_farm_crops']['id'] = 'field_farm_crops';
  $handler->display->display_options['fields']['field_farm_crops']['table'] = 'field_data_field_farm_crops';
  $handler->display->display_options['fields']['field_farm_crops']['field'] = 'field_farm_crops';
  $handler->display->display_options['fields']['field_farm_crops']['delta_offset'] = '0';
  /* Field: Log: Areas */
  $handler->display->display_options['fields']['field_farm_areas']['id'] = 'field_farm_areas';
  $handler->display->display_options['fields']['field_farm_areas']['table'] = 'field_data_field_farm_areas';
  $handler->display->display_options['fields']['field_farm_areas']['field'] = 'field_farm_areas';
  $handler->display->display_options['fields']['field_farm_areas']['delta_offset'] = '0';
  /* Field: Log: Priority */
  $handler->display->display_options['fields']['field_farm_priority']['id'] = 'field_farm_priority';
  $handler->display->display_options['fields']['field_farm_priority']['table'] = 'field_data_field_farm_priority';
  $handler->display->display_options['fields']['field_farm_priority']['field'] = 'field_farm_priority';
  /* Field: Global: Custom text */
  $handler->display->display_options['fields']['nothing']['id'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['table'] = 'views';
  $handler->display->display_options['fields']['nothing']['field'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['label'] = 'Operations';
  $handler->display->display_options['fields']['nothing']['alter']['text'] = '<a href="/log/[id]/edit?destination=admin/farm/logs/issues">Edit</a> | <a href="/log/[id]/delete?destination=admin/farm/logs/issues">Delete</a>';
  /* Filter criterion: Log: Log type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'log';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'farm_issue' => 'farm_issue',
  );
  $handler->display->display_options['filters']['type']['group'] = 1;
  /* Filter criterion: Field: Date (field_farm_date) */
  $handler->display->display_options['filters']['field_farm_date_value']['id'] = 'field_farm_date_value';
  $handler->display->display_options['filters']['field_farm_date_value']['table'] = 'field_data_field_farm_date';
  $handler->display->display_options['filters']['field_farm_date_value']['field'] = 'field_farm_date_value';
  $handler->display->display_options['filters']['field_farm_date_value']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_date_value']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_date_value']['expose']['operator_id'] = 'field_farm_date_value_op';
  $handler->display->display_options['filters']['field_farm_date_value']['expose']['label'] = 'Date';
  $handler->display->display_options['filters']['field_farm_date_value']['expose']['operator'] = 'field_farm_date_value_op';
  $handler->display->display_options['filters']['field_farm_date_value']['expose']['identifier'] = 'field_farm_date_value';
  $handler->display->display_options['filters']['field_farm_date_value']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
  );
  $handler->display->display_options['filters']['field_farm_date_value']['form_type'] = 'date_popup';
  /* Filter criterion: Log: Threats (field_farm_threats) */
  $handler->display->display_options['filters']['field_farm_threats_tid']['id'] = 'field_farm_threats_tid';
  $handler->display->display_options['filters']['field_farm_threats_tid']['table'] = 'field_data_field_farm_threats';
  $handler->display->display_options['filters']['field_farm_threats_tid']['field'] = 'field_farm_threats_tid';
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
    30037204 => 0,
    200153887 => 0,
    125843996 => 0,
    173712484 => 0,
    196317508 => 0,
    146437786 => 0,
  );
  $handler->display->display_options['filters']['field_farm_threats_tid']['type'] = 'select';
  $handler->display->display_options['filters']['field_farm_threats_tid']['vocabulary'] = 'farm_threats';
  $handler->display->display_options['filters']['field_farm_threats_tid']['hierarchy'] = 1;
  /* Filter criterion: Log: Crops (field_farm_crops) */
  $handler->display->display_options['filters']['field_farm_crops_tid']['id'] = 'field_farm_crops_tid';
  $handler->display->display_options['filters']['field_farm_crops_tid']['table'] = 'field_data_field_farm_crops';
  $handler->display->display_options['filters']['field_farm_crops_tid']['field'] = 'field_farm_crops_tid';
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
    30037204 => 0,
    200153887 => 0,
    125843996 => 0,
    173712484 => 0,
    196317508 => 0,
    146437786 => 0,
  );
  $handler->display->display_options['filters']['field_farm_crops_tid']['type'] = 'select';
  $handler->display->display_options['filters']['field_farm_crops_tid']['vocabulary'] = 'farm_crops';
  $handler->display->display_options['filters']['field_farm_crops_tid']['hierarchy'] = 1;
  /* Filter criterion: Log: Areas (field_farm_areas) */
  $handler->display->display_options['filters']['field_farm_areas_tid']['id'] = 'field_farm_areas_tid';
  $handler->display->display_options['filters']['field_farm_areas_tid']['table'] = 'field_data_field_farm_areas';
  $handler->display->display_options['filters']['field_farm_areas_tid']['field'] = 'field_farm_areas_tid';
  $handler->display->display_options['filters']['field_farm_areas_tid']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_areas_tid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_areas_tid']['expose']['operator_id'] = 'field_farm_areas_tid_op';
  $handler->display->display_options['filters']['field_farm_areas_tid']['expose']['label'] = 'Areas';
  $handler->display->display_options['filters']['field_farm_areas_tid']['expose']['operator'] = 'field_farm_areas_tid_op';
  $handler->display->display_options['filters']['field_farm_areas_tid']['expose']['identifier'] = 'field_farm_areas_tid';
  $handler->display->display_options['filters']['field_farm_areas_tid']['expose']['multiple'] = TRUE;
  $handler->display->display_options['filters']['field_farm_areas_tid']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    30037204 => 0,
    200153887 => 0,
    125843996 => 0,
    173712484 => 0,
    196317508 => 0,
    146437786 => 0,
  );
  $handler->display->display_options['filters']['field_farm_areas_tid']['type'] = 'select';
  $handler->display->display_options['filters']['field_farm_areas_tid']['vocabulary'] = 'farm_areas';
  $handler->display->display_options['filters']['field_farm_areas_tid']['hierarchy'] = 1;
  /* Filter criterion: Log: Priority (field_farm_priority) */
  $handler->display->display_options['filters']['field_farm_priority_tid']['id'] = 'field_farm_priority_tid';
  $handler->display->display_options['filters']['field_farm_priority_tid']['table'] = 'field_data_field_farm_priority';
  $handler->display->display_options['filters']['field_farm_priority_tid']['field'] = 'field_farm_priority_tid';
  $handler->display->display_options['filters']['field_farm_priority_tid']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_priority_tid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_priority_tid']['expose']['operator_id'] = 'field_farm_priority_tid_op';
  $handler->display->display_options['filters']['field_farm_priority_tid']['expose']['label'] = 'Priorities';
  $handler->display->display_options['filters']['field_farm_priority_tid']['expose']['operator'] = 'field_farm_priority_tid_op';
  $handler->display->display_options['filters']['field_farm_priority_tid']['expose']['identifier'] = 'field_farm_priority_tid';
  $handler->display->display_options['filters']['field_farm_priority_tid']['expose']['multiple'] = TRUE;
  $handler->display->display_options['filters']['field_farm_priority_tid']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    30037204 => 0,
    200153887 => 0,
    125843996 => 0,
    173712484 => 0,
    196317508 => 0,
    146437786 => 0,
  );
  $handler->display->display_options['filters']['field_farm_priority_tid']['type'] = 'select';
  $handler->display->display_options['filters']['field_farm_priority_tid']['vocabulary'] = 'farm_priority';
  $handler->display->display_options['filters']['field_farm_priority_tid']['hierarchy'] = 1;

  /* Display: All issues (Page) */
  $handler = $view->new_display('page', 'All issues (Page)', 'page');
  $handler->display->display_options['path'] = 'admin/farm/logs/issues';
  $handler->display->display_options['menu']['type'] = 'tab';
  $handler->display->display_options['menu']['title'] = 'Issues';
  $handler->display->display_options['menu']['weight'] = '100';
  $handler->display->display_options['menu']['name'] = 'management';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;
  $handler->display->display_options['tab_options']['type'] = 'tab';
  $handler->display->display_options['tab_options']['title'] = 'Logs';
  $handler->display->display_options['tab_options']['weight'] = '0';
  $handler->display->display_options['tab_options']['name'] = 'management';

  /* Display: Issues by area (Page) */
  $handler = $view->new_display('page', 'Issues by area (Page)', 'page_1');
  $handler->display->display_options['defaults']['style_plugin'] = FALSE;
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['columns'] = array(
    'id' => 'id',
    'field_farm_date' => 'field_farm_date',
    'field_farm_threats' => 'field_farm_threats',
    'field_farm_crops' => 'field_farm_crops',
    'field_farm_priority' => 'field_farm_priority',
    'nothing' => 'nothing',
  );
  $handler->display->display_options['style_options']['default'] = 'field_farm_date';
  $handler->display->display_options['style_options']['info'] = array(
    'id' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_date' => array(
      'sortable' => 1,
      'default_sort_order' => 'desc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_threats' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_crops' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_priority' => array(
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
  $handler->display->display_options['style_options']['empty_table'] = TRUE;
  $handler->display->display_options['defaults']['style_options'] = FALSE;
  $handler->display->display_options['defaults']['row_plugin'] = FALSE;
  $handler->display->display_options['defaults']['row_options'] = FALSE;
  $handler->display->display_options['defaults']['fields'] = FALSE;
  /* Field: Log: Log ID */
  $handler->display->display_options['fields']['id']['id'] = 'id';
  $handler->display->display_options['fields']['id']['table'] = 'log';
  $handler->display->display_options['fields']['id']['field'] = 'id';
  $handler->display->display_options['fields']['id']['exclude'] = TRUE;
  $handler->display->display_options['fields']['id']['separator'] = '';
  /* Field: Field: Date */
  $handler->display->display_options['fields']['field_farm_date']['id'] = 'field_farm_date';
  $handler->display->display_options['fields']['field_farm_date']['table'] = 'field_data_field_farm_date';
  $handler->display->display_options['fields']['field_farm_date']['field'] = 'field_farm_date';
  $handler->display->display_options['fields']['field_farm_date']['settings'] = array(
    'format_type' => 'short',
    'fromto' => 'both',
    'multiple_number' => '',
    'multiple_from' => '',
    'multiple_to' => '',
    'show_repeat_rule' => 'show',
  );
  /* Field: Log: Threats */
  $handler->display->display_options['fields']['field_farm_threats']['id'] = 'field_farm_threats';
  $handler->display->display_options['fields']['field_farm_threats']['table'] = 'field_data_field_farm_threats';
  $handler->display->display_options['fields']['field_farm_threats']['field'] = 'field_farm_threats';
  $handler->display->display_options['fields']['field_farm_threats']['delta_offset'] = '0';
  /* Field: Log: Crops */
  $handler->display->display_options['fields']['field_farm_crops']['id'] = 'field_farm_crops';
  $handler->display->display_options['fields']['field_farm_crops']['table'] = 'field_data_field_farm_crops';
  $handler->display->display_options['fields']['field_farm_crops']['field'] = 'field_farm_crops';
  $handler->display->display_options['fields']['field_farm_crops']['delta_offset'] = '0';
  /* Field: Log: Priority */
  $handler->display->display_options['fields']['field_farm_priority']['id'] = 'field_farm_priority';
  $handler->display->display_options['fields']['field_farm_priority']['table'] = 'field_data_field_farm_priority';
  $handler->display->display_options['fields']['field_farm_priority']['field'] = 'field_farm_priority';
  /* Field: Global: Custom text */
  $handler->display->display_options['fields']['nothing']['id'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['table'] = 'views';
  $handler->display->display_options['fields']['nothing']['field'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['label'] = 'Operations';
  $handler->display->display_options['fields']['nothing']['alter']['text'] = '<a href="/log/[id]/edit?destination=admin/farm/logs/issues">Edit</a> | <a href="/log/[id]/delete?destination=admin/farm/logs/issues">Delete</a>';
  $handler->display->display_options['defaults']['arguments'] = FALSE;
  /* Contextual filter: Log: Areas (field_farm_areas) */
  $handler->display->display_options['arguments']['field_farm_areas_tid']['id'] = 'field_farm_areas_tid';
  $handler->display->display_options['arguments']['field_farm_areas_tid']['table'] = 'field_data_field_farm_areas';
  $handler->display->display_options['arguments']['field_farm_areas_tid']['field'] = 'field_farm_areas_tid';
  $handler->display->display_options['arguments']['field_farm_areas_tid']['default_action'] = 'not found';
  $handler->display->display_options['arguments']['field_farm_areas_tid']['title_enable'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_areas_tid']['title'] = 'Issues with %1';
  $handler->display->display_options['arguments']['field_farm_areas_tid']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['field_farm_areas_tid']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['field_farm_areas_tid']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['field_farm_areas_tid']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['arguments']['field_farm_areas_tid']['specify_validation'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_areas_tid']['validate']['type'] = 'taxonomy_term';
  $handler->display->display_options['arguments']['field_farm_areas_tid']['validate_options']['vocabularies'] = array(
    'farm_areas' => 'farm_areas',
  );
  $handler->display->display_options['defaults']['filter_groups'] = FALSE;
  $handler->display->display_options['defaults']['filters'] = FALSE;
  /* Filter criterion: Log: Log type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'log';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'farm_issue' => 'farm_issue',
  );
  $handler->display->display_options['filters']['type']['group'] = 1;
  /* Filter criterion: Field: Date (field_farm_date) */
  $handler->display->display_options['filters']['field_farm_date_value']['id'] = 'field_farm_date_value';
  $handler->display->display_options['filters']['field_farm_date_value']['table'] = 'field_data_field_farm_date';
  $handler->display->display_options['filters']['field_farm_date_value']['field'] = 'field_farm_date_value';
  $handler->display->display_options['filters']['field_farm_date_value']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_date_value']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_date_value']['expose']['operator_id'] = 'field_farm_date_value_op';
  $handler->display->display_options['filters']['field_farm_date_value']['expose']['label'] = 'Date';
  $handler->display->display_options['filters']['field_farm_date_value']['expose']['operator'] = 'field_farm_date_value_op';
  $handler->display->display_options['filters']['field_farm_date_value']['expose']['identifier'] = 'field_farm_date_value';
  $handler->display->display_options['filters']['field_farm_date_value']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
  );
  $handler->display->display_options['filters']['field_farm_date_value']['form_type'] = 'date_popup';
  /* Filter criterion: Log: Threats (field_farm_threats) */
  $handler->display->display_options['filters']['field_farm_threats_tid']['id'] = 'field_farm_threats_tid';
  $handler->display->display_options['filters']['field_farm_threats_tid']['table'] = 'field_data_field_farm_threats';
  $handler->display->display_options['filters']['field_farm_threats_tid']['field'] = 'field_farm_threats_tid';
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
    30037204 => 0,
    200153887 => 0,
    125843996 => 0,
    173712484 => 0,
    196317508 => 0,
    146437786 => 0,
  );
  $handler->display->display_options['filters']['field_farm_threats_tid']['type'] = 'select';
  $handler->display->display_options['filters']['field_farm_threats_tid']['vocabulary'] = 'farm_threats';
  $handler->display->display_options['filters']['field_farm_threats_tid']['hierarchy'] = 1;
  /* Filter criterion: Log: Crops (field_farm_crops) */
  $handler->display->display_options['filters']['field_farm_crops_tid']['id'] = 'field_farm_crops_tid';
  $handler->display->display_options['filters']['field_farm_crops_tid']['table'] = 'field_data_field_farm_crops';
  $handler->display->display_options['filters']['field_farm_crops_tid']['field'] = 'field_farm_crops_tid';
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
    30037204 => 0,
    200153887 => 0,
    125843996 => 0,
    173712484 => 0,
    196317508 => 0,
    146437786 => 0,
  );
  $handler->display->display_options['filters']['field_farm_crops_tid']['type'] = 'select';
  $handler->display->display_options['filters']['field_farm_crops_tid']['vocabulary'] = 'farm_crops';
  $handler->display->display_options['filters']['field_farm_crops_tid']['hierarchy'] = 1;
  /* Filter criterion: Log: Priority (field_farm_priority) */
  $handler->display->display_options['filters']['field_farm_priority_tid']['id'] = 'field_farm_priority_tid';
  $handler->display->display_options['filters']['field_farm_priority_tid']['table'] = 'field_data_field_farm_priority';
  $handler->display->display_options['filters']['field_farm_priority_tid']['field'] = 'field_farm_priority_tid';
  $handler->display->display_options['filters']['field_farm_priority_tid']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_priority_tid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_priority_tid']['expose']['operator_id'] = 'field_farm_priority_tid_op';
  $handler->display->display_options['filters']['field_farm_priority_tid']['expose']['label'] = 'Priorities';
  $handler->display->display_options['filters']['field_farm_priority_tid']['expose']['operator'] = 'field_farm_priority_tid_op';
  $handler->display->display_options['filters']['field_farm_priority_tid']['expose']['identifier'] = 'field_farm_priority_tid';
  $handler->display->display_options['filters']['field_farm_priority_tid']['expose']['multiple'] = TRUE;
  $handler->display->display_options['filters']['field_farm_priority_tid']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    30037204 => 0,
    200153887 => 0,
    125843996 => 0,
    173712484 => 0,
    196317508 => 0,
    146437786 => 0,
  );
  $handler->display->display_options['filters']['field_farm_priority_tid']['type'] = 'select';
  $handler->display->display_options['filters']['field_farm_priority_tid']['vocabulary'] = 'farm_priority';
  $handler->display->display_options['filters']['field_farm_priority_tid']['hierarchy'] = 1;
  $handler->display->display_options['path'] = 'admin/farm/areas/%/issues';
  $handler->display->display_options['menu']['type'] = 'tab';
  $handler->display->display_options['menu']['title'] = 'Issues';
  $handler->display->display_options['menu']['weight'] = '50';
  $handler->display->display_options['menu']['name'] = 'management';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;

  /* Display: Issues by crop (Page) */
  $handler = $view->new_display('page', 'Issues by crop (Page)', 'page_2');
  $handler->display->display_options['defaults']['style_plugin'] = FALSE;
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['columns'] = array(
    'id' => 'id',
    'field_farm_date' => 'field_farm_date',
    'field_farm_threats' => 'field_farm_threats',
    'field_farm_areas' => 'field_farm_areas',
    'field_farm_priority' => 'field_farm_priority',
    'nothing' => 'nothing',
  );
  $handler->display->display_options['style_options']['default'] = 'field_farm_date';
  $handler->display->display_options['style_options']['info'] = array(
    'id' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_date' => array(
      'sortable' => 1,
      'default_sort_order' => 'desc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_threats' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_areas' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_priority' => array(
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
  $handler->display->display_options['style_options']['empty_table'] = TRUE;
  $handler->display->display_options['defaults']['style_options'] = FALSE;
  $handler->display->display_options['defaults']['row_plugin'] = FALSE;
  $handler->display->display_options['defaults']['row_options'] = FALSE;
  $handler->display->display_options['defaults']['fields'] = FALSE;
  /* Field: Log: Log ID */
  $handler->display->display_options['fields']['id']['id'] = 'id';
  $handler->display->display_options['fields']['id']['table'] = 'log';
  $handler->display->display_options['fields']['id']['field'] = 'id';
  $handler->display->display_options['fields']['id']['exclude'] = TRUE;
  $handler->display->display_options['fields']['id']['separator'] = '';
  /* Field: Field: Date */
  $handler->display->display_options['fields']['field_farm_date']['id'] = 'field_farm_date';
  $handler->display->display_options['fields']['field_farm_date']['table'] = 'field_data_field_farm_date';
  $handler->display->display_options['fields']['field_farm_date']['field'] = 'field_farm_date';
  $handler->display->display_options['fields']['field_farm_date']['settings'] = array(
    'format_type' => 'short',
    'fromto' => 'both',
    'multiple_number' => '',
    'multiple_from' => '',
    'multiple_to' => '',
    'show_repeat_rule' => 'show',
  );
  /* Field: Log: Threats */
  $handler->display->display_options['fields']['field_farm_threats']['id'] = 'field_farm_threats';
  $handler->display->display_options['fields']['field_farm_threats']['table'] = 'field_data_field_farm_threats';
  $handler->display->display_options['fields']['field_farm_threats']['field'] = 'field_farm_threats';
  $handler->display->display_options['fields']['field_farm_threats']['delta_offset'] = '0';
  /* Field: Log: Areas */
  $handler->display->display_options['fields']['field_farm_areas']['id'] = 'field_farm_areas';
  $handler->display->display_options['fields']['field_farm_areas']['table'] = 'field_data_field_farm_areas';
  $handler->display->display_options['fields']['field_farm_areas']['field'] = 'field_farm_areas';
  $handler->display->display_options['fields']['field_farm_areas']['delta_offset'] = '0';
  /* Field: Log: Priority */
  $handler->display->display_options['fields']['field_farm_priority']['id'] = 'field_farm_priority';
  $handler->display->display_options['fields']['field_farm_priority']['table'] = 'field_data_field_farm_priority';
  $handler->display->display_options['fields']['field_farm_priority']['field'] = 'field_farm_priority';
  /* Field: Global: Custom text */
  $handler->display->display_options['fields']['nothing']['id'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['table'] = 'views';
  $handler->display->display_options['fields']['nothing']['field'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['label'] = 'Operations';
  $handler->display->display_options['fields']['nothing']['alter']['text'] = '<a href="/log/[id]/edit?destination=admin/farm/logs/issues">Edit</a> | <a href="/log/[id]/delete?destination=admin/farm/logs/issues">Delete</a>';
  $handler->display->display_options['defaults']['arguments'] = FALSE;
  /* Contextual filter: Log: Crops (field_farm_crops) */
  $handler->display->display_options['arguments']['field_farm_crops_tid']['id'] = 'field_farm_crops_tid';
  $handler->display->display_options['arguments']['field_farm_crops_tid']['table'] = 'field_data_field_farm_crops';
  $handler->display->display_options['arguments']['field_farm_crops_tid']['field'] = 'field_farm_crops_tid';
  $handler->display->display_options['arguments']['field_farm_crops_tid']['default_action'] = 'not found';
  $handler->display->display_options['arguments']['field_farm_crops_tid']['title_enable'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_crops_tid']['title'] = 'Issues with %1';
  $handler->display->display_options['arguments']['field_farm_crops_tid']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['field_farm_crops_tid']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['field_farm_crops_tid']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['field_farm_crops_tid']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['arguments']['field_farm_crops_tid']['specify_validation'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_crops_tid']['validate']['type'] = 'taxonomy_term';
  $handler->display->display_options['arguments']['field_farm_crops_tid']['validate_options']['vocabularies'] = array(
    'farm_crops' => 'farm_crops',
  );
  $handler->display->display_options['defaults']['filter_groups'] = FALSE;
  $handler->display->display_options['defaults']['filters'] = FALSE;
  /* Filter criterion: Log: Log type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'log';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'farm_issue' => 'farm_issue',
  );
  $handler->display->display_options['filters']['type']['group'] = 1;
  /* Filter criterion: Field: Date (field_farm_date) */
  $handler->display->display_options['filters']['field_farm_date_value']['id'] = 'field_farm_date_value';
  $handler->display->display_options['filters']['field_farm_date_value']['table'] = 'field_data_field_farm_date';
  $handler->display->display_options['filters']['field_farm_date_value']['field'] = 'field_farm_date_value';
  $handler->display->display_options['filters']['field_farm_date_value']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_date_value']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_date_value']['expose']['operator_id'] = 'field_farm_date_value_op';
  $handler->display->display_options['filters']['field_farm_date_value']['expose']['label'] = 'Date';
  $handler->display->display_options['filters']['field_farm_date_value']['expose']['operator'] = 'field_farm_date_value_op';
  $handler->display->display_options['filters']['field_farm_date_value']['expose']['identifier'] = 'field_farm_date_value';
  $handler->display->display_options['filters']['field_farm_date_value']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
  );
  $handler->display->display_options['filters']['field_farm_date_value']['form_type'] = 'date_popup';
  /* Filter criterion: Log: Threats (field_farm_threats) */
  $handler->display->display_options['filters']['field_farm_threats_tid']['id'] = 'field_farm_threats_tid';
  $handler->display->display_options['filters']['field_farm_threats_tid']['table'] = 'field_data_field_farm_threats';
  $handler->display->display_options['filters']['field_farm_threats_tid']['field'] = 'field_farm_threats_tid';
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
    30037204 => 0,
    200153887 => 0,
    125843996 => 0,
    173712484 => 0,
    196317508 => 0,
    146437786 => 0,
  );
  $handler->display->display_options['filters']['field_farm_threats_tid']['type'] = 'select';
  $handler->display->display_options['filters']['field_farm_threats_tid']['vocabulary'] = 'farm_threats';
  $handler->display->display_options['filters']['field_farm_threats_tid']['hierarchy'] = 1;
  /* Filter criterion: Log: Areas (field_farm_areas) */
  $handler->display->display_options['filters']['field_farm_areas_tid']['id'] = 'field_farm_areas_tid';
  $handler->display->display_options['filters']['field_farm_areas_tid']['table'] = 'field_data_field_farm_areas';
  $handler->display->display_options['filters']['field_farm_areas_tid']['field'] = 'field_farm_areas_tid';
  $handler->display->display_options['filters']['field_farm_areas_tid']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_areas_tid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_areas_tid']['expose']['operator_id'] = 'field_farm_areas_tid_op';
  $handler->display->display_options['filters']['field_farm_areas_tid']['expose']['label'] = 'Areas';
  $handler->display->display_options['filters']['field_farm_areas_tid']['expose']['operator'] = 'field_farm_areas_tid_op';
  $handler->display->display_options['filters']['field_farm_areas_tid']['expose']['identifier'] = 'field_farm_areas_tid';
  $handler->display->display_options['filters']['field_farm_areas_tid']['expose']['multiple'] = TRUE;
  $handler->display->display_options['filters']['field_farm_areas_tid']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    30037204 => 0,
    200153887 => 0,
    125843996 => 0,
    173712484 => 0,
    196317508 => 0,
    146437786 => 0,
  );
  $handler->display->display_options['filters']['field_farm_areas_tid']['type'] = 'select';
  $handler->display->display_options['filters']['field_farm_areas_tid']['vocabulary'] = 'farm_areas';
  $handler->display->display_options['filters']['field_farm_areas_tid']['hierarchy'] = 1;
  /* Filter criterion: Log: Priority (field_farm_priority) */
  $handler->display->display_options['filters']['field_farm_priority_tid']['id'] = 'field_farm_priority_tid';
  $handler->display->display_options['filters']['field_farm_priority_tid']['table'] = 'field_data_field_farm_priority';
  $handler->display->display_options['filters']['field_farm_priority_tid']['field'] = 'field_farm_priority_tid';
  $handler->display->display_options['filters']['field_farm_priority_tid']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_priority_tid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_priority_tid']['expose']['operator_id'] = 'field_farm_priority_tid_op';
  $handler->display->display_options['filters']['field_farm_priority_tid']['expose']['label'] = 'Priorities';
  $handler->display->display_options['filters']['field_farm_priority_tid']['expose']['operator'] = 'field_farm_priority_tid_op';
  $handler->display->display_options['filters']['field_farm_priority_tid']['expose']['identifier'] = 'field_farm_priority_tid';
  $handler->display->display_options['filters']['field_farm_priority_tid']['expose']['multiple'] = TRUE;
  $handler->display->display_options['filters']['field_farm_priority_tid']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    30037204 => 0,
    200153887 => 0,
    125843996 => 0,
    173712484 => 0,
    196317508 => 0,
    146437786 => 0,
  );
  $handler->display->display_options['filters']['field_farm_priority_tid']['type'] = 'select';
  $handler->display->display_options['filters']['field_farm_priority_tid']['vocabulary'] = 'farm_priority';
  $handler->display->display_options['filters']['field_farm_priority_tid']['hierarchy'] = 1;
  $handler->display->display_options['path'] = 'admin/farm/crops/%/issues';
  $handler->display->display_options['menu']['type'] = 'tab';
  $handler->display->display_options['menu']['title'] = 'Issues';
  $handler->display->display_options['menu']['weight'] = '50';
  $handler->display->display_options['menu']['name'] = 'management';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;
  $export['farm_log_issues'] = $view;

  return $export;
}
