<?php
/**
 * @file
 * farm_log_crops.views_default.inc
 */

/**
 * Implements hook_views_default_views().
 */
function farm_log_crops_views_default_views() {
  $export = array();

  $view = new view();
  $view->name = 'farm_log_cultivation';
  $view->description = '';
  $view->tag = 'farm_log_crops';
  $view->base_table = 'log';
  $view->human_name = 'Farm Log: Cultivation';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Cultivations';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['access']['perm'] = 'view any farm_cultivation log entities';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['pager']['options']['items_per_page'] = '50';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['pager']['options']['id'] = '0';
  $handler->display->display_options['pager']['options']['quantity'] = '9';
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['columns'] = array(
    'id' => 'id',
    'field_farm_date' => 'field_farm_date',
    'field_farm_cultivation_type' => 'field_farm_cultivation_type',
    'field_farm_crops' => 'field_farm_crops',
    'field_farm_areas' => 'field_farm_areas',
    'field_farm_quantity' => 'field_farm_quantity',
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
    'field_farm_cultivation_type' => array(
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
    'field_farm_quantity' => array(
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
  $handler->display->display_options['footer']['area_text_custom']['content'] = '<a href="/log/add/farm_cultivation?destination=admin/farm/logs/cultivations">+ Log a cultivation</a>';
  /* No results behavior: Global: Unfiltered text */
  $handler->display->display_options['empty']['area_text_custom']['id'] = 'area_text_custom';
  $handler->display->display_options['empty']['area_text_custom']['table'] = 'views';
  $handler->display->display_options['empty']['area_text_custom']['field'] = 'area_text_custom';
  $handler->display->display_options['empty']['area_text_custom']['empty'] = TRUE;
  $handler->display->display_options['empty']['area_text_custom']['content'] = 'No cultivations found.';
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
  /* Field: Log: Cultivation Type */
  $handler->display->display_options['fields']['field_farm_cultivation_type']['id'] = 'field_farm_cultivation_type';
  $handler->display->display_options['fields']['field_farm_cultivation_type']['table'] = 'field_data_field_farm_cultivation_type';
  $handler->display->display_options['fields']['field_farm_cultivation_type']['field'] = 'field_farm_cultivation_type';
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
  /* Field: Field: Quantity */
  $handler->display->display_options['fields']['field_farm_quantity']['id'] = 'field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['table'] = 'field_data_field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['field'] = 'field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['type'] = 'field_collection_fields';
  $handler->display->display_options['fields']['field_farm_quantity']['settings'] = array(
    'view_mode' => 'full',
  );
  /* Field: Global: Custom text */
  $handler->display->display_options['fields']['nothing']['id'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['table'] = 'views';
  $handler->display->display_options['fields']['nothing']['field'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['label'] = 'Operations';
  $handler->display->display_options['fields']['nothing']['alter']['text'] = '<a href="/log/[id]/edit?destination=admin/farm/logs/cultivations">Edit</a> | <a href="/log/[id]/delete?destination=admin/farm/logs/cultivations">Delete</a>';
  /* Filter criterion: Log: Log type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'log';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'farm_cultivation' => 'farm_cultivation',
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
    30037204 => 0,
    200153887 => 0,
    125843996 => 0,
    173712484 => 0,
    196317508 => 0,
  );
  $handler->display->display_options['filters']['field_farm_date_value']['form_type'] = 'date_popup';
  /* Filter criterion: Log: Cultivation Type (field_farm_cultivation_type) */
  $handler->display->display_options['filters']['field_farm_cultivation_type_tid']['id'] = 'field_farm_cultivation_type_tid';
  $handler->display->display_options['filters']['field_farm_cultivation_type_tid']['table'] = 'field_data_field_farm_cultivation_type';
  $handler->display->display_options['filters']['field_farm_cultivation_type_tid']['field'] = 'field_farm_cultivation_type_tid';
  $handler->display->display_options['filters']['field_farm_cultivation_type_tid']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_cultivation_type_tid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_cultivation_type_tid']['expose']['operator_id'] = 'field_farm_cultivation_type_tid_op';
  $handler->display->display_options['filters']['field_farm_cultivation_type_tid']['expose']['label'] = 'Cultivation Types';
  $handler->display->display_options['filters']['field_farm_cultivation_type_tid']['expose']['operator'] = 'field_farm_cultivation_type_tid_op';
  $handler->display->display_options['filters']['field_farm_cultivation_type_tid']['expose']['identifier'] = 'field_farm_cultivation_type_tid';
  $handler->display->display_options['filters']['field_farm_cultivation_type_tid']['expose']['multiple'] = TRUE;
  $handler->display->display_options['filters']['field_farm_cultivation_type_tid']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    30037204 => 0,
    200153887 => 0,
    125843996 => 0,
    173712484 => 0,
    196317508 => 0,
    146437786 => 0,
  );
  $handler->display->display_options['filters']['field_farm_cultivation_type_tid']['type'] = 'select';
  $handler->display->display_options['filters']['field_farm_cultivation_type_tid']['vocabulary'] = 'farm_cultivation_types';
  $handler->display->display_options['filters']['field_farm_cultivation_type_tid']['hierarchy'] = 1;
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

  /* Display: All cultivations (Page) */
  $handler = $view->new_display('page', 'All cultivations (Page)', 'page');
  $handler->display->display_options['path'] = 'admin/farm/logs/cultivations';
  $handler->display->display_options['menu']['type'] = 'tab';
  $handler->display->display_options['menu']['title'] = 'Cultivations';
  $handler->display->display_options['menu']['weight'] = '20';
  $handler->display->display_options['menu']['name'] = 'management';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;
  $handler->display->display_options['tab_options']['type'] = 'tab';
  $handler->display->display_options['tab_options']['title'] = 'Crops';
  $handler->display->display_options['tab_options']['weight'] = '0';

  /* Display: Cultivations by area (Page) */
  $handler = $view->new_display('page', 'Cultivations by area (Page)', 'page_1');
  $handler->display->display_options['defaults']['style_plugin'] = FALSE;
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['columns'] = array(
    'id' => 'id',
    'field_farm_date' => 'field_farm_date',
    'field_farm_cultivation_type' => 'field_farm_cultivation_type',
    'field_farm_crops' => 'field_farm_crops',
    'field_farm_quantity' => 'field_farm_quantity',
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
    'field_farm_cultivation_type' => array(
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
    'field_farm_quantity' => array(
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
  /* Field: Log: Cultivation Type */
  $handler->display->display_options['fields']['field_farm_cultivation_type']['id'] = 'field_farm_cultivation_type';
  $handler->display->display_options['fields']['field_farm_cultivation_type']['table'] = 'field_data_field_farm_cultivation_type';
  $handler->display->display_options['fields']['field_farm_cultivation_type']['field'] = 'field_farm_cultivation_type';
  /* Field: Log: Crops */
  $handler->display->display_options['fields']['field_farm_crops']['id'] = 'field_farm_crops';
  $handler->display->display_options['fields']['field_farm_crops']['table'] = 'field_data_field_farm_crops';
  $handler->display->display_options['fields']['field_farm_crops']['field'] = 'field_farm_crops';
  $handler->display->display_options['fields']['field_farm_crops']['delta_offset'] = '0';
  /* Field: Field: Quantity */
  $handler->display->display_options['fields']['field_farm_quantity']['id'] = 'field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['table'] = 'field_data_field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['field'] = 'field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['type'] = 'field_collection_fields';
  $handler->display->display_options['fields']['field_farm_quantity']['settings'] = array(
    'view_mode' => 'full',
  );
  /* Field: Global: Custom text */
  $handler->display->display_options['fields']['nothing']['id'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['table'] = 'views';
  $handler->display->display_options['fields']['nothing']['field'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['label'] = 'Operations';
  $handler->display->display_options['fields']['nothing']['alter']['text'] = '<a href="/log/[id]/edit?destination=admin/farm/logs/cultivations">Edit</a> | <a href="/log/[id]/delete?destination=admin/farm/logs/cultivations">Delete</a>';
  $handler->display->display_options['defaults']['arguments'] = FALSE;
  /* Contextual filter: Log: Areas (field_farm_areas) */
  $handler->display->display_options['arguments']['field_farm_areas_tid']['id'] = 'field_farm_areas_tid';
  $handler->display->display_options['arguments']['field_farm_areas_tid']['table'] = 'field_data_field_farm_areas';
  $handler->display->display_options['arguments']['field_farm_areas_tid']['field'] = 'field_farm_areas_tid';
  $handler->display->display_options['arguments']['field_farm_areas_tid']['default_action'] = 'not found';
  $handler->display->display_options['arguments']['field_farm_areas_tid']['title_enable'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_areas_tid']['title'] = 'Cultivations of %1';
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
    'farm_cultivation' => 'farm_cultivation',
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
    30037204 => 0,
    200153887 => 0,
    125843996 => 0,
    173712484 => 0,
    196317508 => 0,
  );
  $handler->display->display_options['filters']['field_farm_date_value']['form_type'] = 'date_popup';
  /* Filter criterion: Log: Cultivation Type (field_farm_cultivation_type) */
  $handler->display->display_options['filters']['field_farm_cultivation_type_tid']['id'] = 'field_farm_cultivation_type_tid';
  $handler->display->display_options['filters']['field_farm_cultivation_type_tid']['table'] = 'field_data_field_farm_cultivation_type';
  $handler->display->display_options['filters']['field_farm_cultivation_type_tid']['field'] = 'field_farm_cultivation_type_tid';
  $handler->display->display_options['filters']['field_farm_cultivation_type_tid']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_cultivation_type_tid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_cultivation_type_tid']['expose']['operator_id'] = 'field_farm_cultivation_type_tid_op';
  $handler->display->display_options['filters']['field_farm_cultivation_type_tid']['expose']['label'] = 'Cultivation Types';
  $handler->display->display_options['filters']['field_farm_cultivation_type_tid']['expose']['operator'] = 'field_farm_cultivation_type_tid_op';
  $handler->display->display_options['filters']['field_farm_cultivation_type_tid']['expose']['identifier'] = 'field_farm_cultivation_type_tid';
  $handler->display->display_options['filters']['field_farm_cultivation_type_tid']['expose']['multiple'] = TRUE;
  $handler->display->display_options['filters']['field_farm_cultivation_type_tid']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    30037204 => 0,
    200153887 => 0,
    125843996 => 0,
    173712484 => 0,
    196317508 => 0,
    146437786 => 0,
  );
  $handler->display->display_options['filters']['field_farm_cultivation_type_tid']['type'] = 'select';
  $handler->display->display_options['filters']['field_farm_cultivation_type_tid']['vocabulary'] = 'farm_cultivation_types';
  $handler->display->display_options['filters']['field_farm_cultivation_type_tid']['hierarchy'] = 1;
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
  $handler->display->display_options['path'] = 'taxonomy/term/%/cultivations';
  $handler->display->display_options['menu']['title'] = 'Cultivations';
  $handler->display->display_options['menu']['weight'] = '30';
  $handler->display->display_options['menu']['name'] = 'management';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;

  /* Display: Cultivations by crop (Page) */
  $handler = $view->new_display('page', 'Cultivations by crop (Page)', 'page_2');
  $handler->display->display_options['defaults']['style_plugin'] = FALSE;
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['columns'] = array(
    'id' => 'id',
    'field_farm_date' => 'field_farm_date',
    'field_farm_cultivation_type' => 'field_farm_cultivation_type',
    'field_farm_areas' => 'field_farm_areas',
    'field_farm_quantity' => 'field_farm_quantity',
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
    'field_farm_cultivation_type' => array(
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
    'field_farm_quantity' => array(
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
  /* Field: Log: Cultivation Type */
  $handler->display->display_options['fields']['field_farm_cultivation_type']['id'] = 'field_farm_cultivation_type';
  $handler->display->display_options['fields']['field_farm_cultivation_type']['table'] = 'field_data_field_farm_cultivation_type';
  $handler->display->display_options['fields']['field_farm_cultivation_type']['field'] = 'field_farm_cultivation_type';
  /* Field: Log: Areas */
  $handler->display->display_options['fields']['field_farm_areas']['id'] = 'field_farm_areas';
  $handler->display->display_options['fields']['field_farm_areas']['table'] = 'field_data_field_farm_areas';
  $handler->display->display_options['fields']['field_farm_areas']['field'] = 'field_farm_areas';
  $handler->display->display_options['fields']['field_farm_areas']['delta_offset'] = '0';
  /* Field: Field: Quantity */
  $handler->display->display_options['fields']['field_farm_quantity']['id'] = 'field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['table'] = 'field_data_field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['field'] = 'field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['type'] = 'field_collection_fields';
  $handler->display->display_options['fields']['field_farm_quantity']['settings'] = array(
    'view_mode' => 'full',
  );
  /* Field: Global: Custom text */
  $handler->display->display_options['fields']['nothing']['id'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['table'] = 'views';
  $handler->display->display_options['fields']['nothing']['field'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['label'] = 'Operations';
  $handler->display->display_options['fields']['nothing']['alter']['text'] = '<a href="/log/[id]/edit?destination=admin/farm/logs/cultivations">Edit</a> | <a href="/log/[id]/delete?destination=admin/farm/logs/cultivations">Delete</a>';
  $handler->display->display_options['defaults']['arguments'] = FALSE;
  /* Contextual filter: Log: Crops (field_farm_crops) */
  $handler->display->display_options['arguments']['field_farm_crops_tid']['id'] = 'field_farm_crops_tid';
  $handler->display->display_options['arguments']['field_farm_crops_tid']['table'] = 'field_data_field_farm_crops';
  $handler->display->display_options['arguments']['field_farm_crops_tid']['field'] = 'field_farm_crops_tid';
  $handler->display->display_options['arguments']['field_farm_crops_tid']['default_action'] = 'not found';
  $handler->display->display_options['arguments']['field_farm_crops_tid']['title_enable'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_crops_tid']['title'] = 'Cultivations of %1';
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
    'farm_cultivation' => 'farm_cultivation',
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
    30037204 => 0,
    200153887 => 0,
    125843996 => 0,
    173712484 => 0,
    196317508 => 0,
  );
  $handler->display->display_options['filters']['field_farm_date_value']['form_type'] = 'date_popup';
  /* Filter criterion: Log: Cultivation Type (field_farm_cultivation_type) */
  $handler->display->display_options['filters']['field_farm_cultivation_type_tid']['id'] = 'field_farm_cultivation_type_tid';
  $handler->display->display_options['filters']['field_farm_cultivation_type_tid']['table'] = 'field_data_field_farm_cultivation_type';
  $handler->display->display_options['filters']['field_farm_cultivation_type_tid']['field'] = 'field_farm_cultivation_type_tid';
  $handler->display->display_options['filters']['field_farm_cultivation_type_tid']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_cultivation_type_tid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_cultivation_type_tid']['expose']['operator_id'] = 'field_farm_cultivation_type_tid_op';
  $handler->display->display_options['filters']['field_farm_cultivation_type_tid']['expose']['label'] = 'Cultivation Types';
  $handler->display->display_options['filters']['field_farm_cultivation_type_tid']['expose']['operator'] = 'field_farm_cultivation_type_tid_op';
  $handler->display->display_options['filters']['field_farm_cultivation_type_tid']['expose']['identifier'] = 'field_farm_cultivation_type_tid';
  $handler->display->display_options['filters']['field_farm_cultivation_type_tid']['expose']['multiple'] = TRUE;
  $handler->display->display_options['filters']['field_farm_cultivation_type_tid']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    30037204 => 0,
    200153887 => 0,
    125843996 => 0,
    173712484 => 0,
    196317508 => 0,
    146437786 => 0,
  );
  $handler->display->display_options['filters']['field_farm_cultivation_type_tid']['type'] = 'select';
  $handler->display->display_options['filters']['field_farm_cultivation_type_tid']['vocabulary'] = 'farm_cultivation_types';
  $handler->display->display_options['filters']['field_farm_cultivation_type_tid']['hierarchy'] = 1;
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
  $handler->display->display_options['path'] = 'taxonomy/term/%/cultivations';
  $handler->display->display_options['menu']['title'] = 'Cultivations';
  $handler->display->display_options['menu']['weight'] = '30';
  $handler->display->display_options['menu']['name'] = 'management';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;
  $export['farm_log_cultivation'] = $view;

  $view = new view();
  $view->name = 'farm_log_harvest';
  $view->description = '';
  $view->tag = 'farm_log_crops';
  $view->base_table = 'log';
  $view->human_name = 'Farm Log: Harvest';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Harvests';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['access']['perm'] = 'edit any farm_harvest log entities';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['pager']['options']['items_per_page'] = '50';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['pager']['options']['id'] = '0';
  $handler->display->display_options['pager']['options']['quantity'] = '9';
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['grouping'] = array(
    0 => array(
      'field' => 'id',
      'rendered' => 1,
      'rendered_strip' => 0,
    ),
  );
  $handler->display->display_options['style_options']['columns'] = array(
    'id' => 'id',
    'field_farm_date' => 'field_farm_date',
    'field_farm_areas' => 'field_farm_areas',
    'field_farm_crop' => 'field_farm_crop',
    'field_farm_quantity' => 'field_farm_quantity',
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
    'field_farm_areas' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_crop' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_quantity' => array(
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
  $handler->display->display_options['footer']['area_text_custom']['content'] = '<a href="/log/add/farm_harvest?destination=admin/farm/logs/harvests">+ Log a harvest</a>';
  /* No results behavior: Global: Unfiltered text */
  $handler->display->display_options['empty']['area_text_custom']['id'] = 'area_text_custom';
  $handler->display->display_options['empty']['area_text_custom']['table'] = 'views';
  $handler->display->display_options['empty']['area_text_custom']['field'] = 'area_text_custom';
  $handler->display->display_options['empty']['area_text_custom']['empty'] = TRUE;
  $handler->display->display_options['empty']['area_text_custom']['content'] = 'No harvests found.';
  /* Relationship: Log: Harvest List (field_farm_harvest_list) */
  $handler->display->display_options['relationships']['field_farm_harvest_list_value']['id'] = 'field_farm_harvest_list_value';
  $handler->display->display_options['relationships']['field_farm_harvest_list_value']['table'] = 'field_data_field_farm_harvest_list';
  $handler->display->display_options['relationships']['field_farm_harvest_list_value']['field'] = 'field_farm_harvest_list_value';
  $handler->display->display_options['relationships']['field_farm_harvest_list_value']['label'] = 'harvest_item';
  $handler->display->display_options['relationships']['field_farm_harvest_list_value']['delta'] = '-1';
  /* Field: Log: Log ID */
  $handler->display->display_options['fields']['id']['id'] = 'id';
  $handler->display->display_options['fields']['id']['table'] = 'log';
  $handler->display->display_options['fields']['id']['field'] = 'id';
  $handler->display->display_options['fields']['id']['label'] = 'Harvest ID';
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
  /* Field: Field: Crop */
  $handler->display->display_options['fields']['field_farm_crop']['id'] = 'field_farm_crop';
  $handler->display->display_options['fields']['field_farm_crop']['table'] = 'field_data_field_farm_crop';
  $handler->display->display_options['fields']['field_farm_crop']['field'] = 'field_farm_crop';
  $handler->display->display_options['fields']['field_farm_crop']['relationship'] = 'field_farm_harvest_list_value';
  /* Field: Field: Quantity */
  $handler->display->display_options['fields']['field_farm_quantity']['id'] = 'field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['table'] = 'field_data_field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['field'] = 'field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['relationship'] = 'field_farm_harvest_list_value';
  $handler->display->display_options['fields']['field_farm_quantity']['type'] = 'field_collection_fields';
  $handler->display->display_options['fields']['field_farm_quantity']['settings'] = array(
    'view_mode' => 'full',
  );
  /* Field: Log: Areas */
  $handler->display->display_options['fields']['field_farm_areas']['id'] = 'field_farm_areas';
  $handler->display->display_options['fields']['field_farm_areas']['table'] = 'field_data_field_farm_areas';
  $handler->display->display_options['fields']['field_farm_areas']['field'] = 'field_farm_areas';
  $handler->display->display_options['fields']['field_farm_areas']['delta_offset'] = '0';
  /* Field: Global: Custom text */
  $handler->display->display_options['fields']['nothing']['id'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['table'] = 'views';
  $handler->display->display_options['fields']['nothing']['field'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['label'] = 'Operations';
  $handler->display->display_options['fields']['nothing']['alter']['text'] = '<a href="/log/[id]/edit?destination=admin/farm/logs/harvests">Edit</a> | <a href="/log/[id]/delete?destination=admin/farm/logs/harvests">Delete</a>';
  /* Filter criterion: Log: Log type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'log';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'farm_harvest' => 'farm_harvest',
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
  /* Filter criterion: Field: Crop (field_farm_crop) */
  $handler->display->display_options['filters']['field_farm_crop_tid']['id'] = 'field_farm_crop_tid';
  $handler->display->display_options['filters']['field_farm_crop_tid']['table'] = 'field_data_field_farm_crop';
  $handler->display->display_options['filters']['field_farm_crop_tid']['field'] = 'field_farm_crop_tid';
  $handler->display->display_options['filters']['field_farm_crop_tid']['relationship'] = 'field_farm_harvest_list_value';
  $handler->display->display_options['filters']['field_farm_crop_tid']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_crop_tid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_crop_tid']['expose']['operator_id'] = 'field_farm_crop_tid_op';
  $handler->display->display_options['filters']['field_farm_crop_tid']['expose']['label'] = 'Crops';
  $handler->display->display_options['filters']['field_farm_crop_tid']['expose']['operator'] = 'field_farm_crop_tid_op';
  $handler->display->display_options['filters']['field_farm_crop_tid']['expose']['identifier'] = 'field_farm_crop_tid';
  $handler->display->display_options['filters']['field_farm_crop_tid']['expose']['multiple'] = TRUE;
  $handler->display->display_options['filters']['field_farm_crop_tid']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    30037204 => 0,
    200153887 => 0,
    125843996 => 0,
    173712484 => 0,
    196317508 => 0,
    146437786 => 0,
  );
  $handler->display->display_options['filters']['field_farm_crop_tid']['type'] = 'select';
  $handler->display->display_options['filters']['field_farm_crop_tid']['vocabulary'] = 'farm_crops';
  $handler->display->display_options['filters']['field_farm_crop_tid']['hierarchy'] = 1;

  /* Display: All harvests (Page) */
  $handler = $view->new_display('page', 'All harvests (Page)', 'page');
  $handler->display->display_options['path'] = 'admin/farm/logs/harvests';
  $handler->display->display_options['menu']['type'] = 'tab';
  $handler->display->display_options['menu']['title'] = 'Harvests';
  $handler->display->display_options['menu']['weight'] = '30';
  $handler->display->display_options['menu']['name'] = 'management';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;
  $handler->display->display_options['tab_options']['type'] = 'normal';
  $handler->display->display_options['tab_options']['title'] = 'Logs';
  $handler->display->display_options['tab_options']['weight'] = '0';
  $handler->display->display_options['tab_options']['name'] = 'management';

  /* Display: Harvests by area (Page) */
  $handler = $view->new_display('page', 'Harvests by area (Page)', 'page_1');
  $handler->display->display_options['defaults']['style_plugin'] = FALSE;
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['grouping'] = array(
    0 => array(
      'field' => 'id',
      'rendered' => 1,
      'rendered_strip' => 0,
    ),
  );
  $handler->display->display_options['style_options']['columns'] = array(
    'id' => 'id',
    'field_farm_date' => 'field_farm_date',
    'field_farm_crop' => 'field_farm_crop',
    'field_farm_quantity' => 'field_farm_quantity',
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
    'field_farm_crop' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_quantity' => array(
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
  $handler->display->display_options['fields']['id']['label'] = 'Harvest ID';
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
  /* Field: Field: Crop */
  $handler->display->display_options['fields']['field_farm_crop']['id'] = 'field_farm_crop';
  $handler->display->display_options['fields']['field_farm_crop']['table'] = 'field_data_field_farm_crop';
  $handler->display->display_options['fields']['field_farm_crop']['field'] = 'field_farm_crop';
  $handler->display->display_options['fields']['field_farm_crop']['relationship'] = 'field_farm_harvest_list_value';
  /* Field: Field: Quantity */
  $handler->display->display_options['fields']['field_farm_quantity']['id'] = 'field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['table'] = 'field_data_field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['field'] = 'field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['relationship'] = 'field_farm_harvest_list_value';
  $handler->display->display_options['fields']['field_farm_quantity']['type'] = 'field_collection_fields';
  $handler->display->display_options['fields']['field_farm_quantity']['settings'] = array(
    'view_mode' => 'full',
  );
  /* Field: Global: Custom text */
  $handler->display->display_options['fields']['nothing']['id'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['table'] = 'views';
  $handler->display->display_options['fields']['nothing']['field'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['label'] = 'Operations';
  $handler->display->display_options['fields']['nothing']['alter']['text'] = '<a href="/log/[id]/edit?destination=admin/farm/logs/harvests">Edit</a> | <a href="/log/[id]/delete?destination=admin/farm/logs/harvests">Delete</a>';
  $handler->display->display_options['defaults']['arguments'] = FALSE;
  /* Contextual filter: Log: Areas (field_farm_areas) */
  $handler->display->display_options['arguments']['field_farm_areas_tid']['id'] = 'field_farm_areas_tid';
  $handler->display->display_options['arguments']['field_farm_areas_tid']['table'] = 'field_data_field_farm_areas';
  $handler->display->display_options['arguments']['field_farm_areas_tid']['field'] = 'field_farm_areas_tid';
  $handler->display->display_options['arguments']['field_farm_areas_tid']['default_action'] = 'not found';
  $handler->display->display_options['arguments']['field_farm_areas_tid']['title_enable'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_areas_tid']['title'] = 'Harvests from %1';
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
    'farm_harvest' => 'farm_harvest',
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
  /* Filter criterion: Field: Crop (field_farm_crop) */
  $handler->display->display_options['filters']['field_farm_crop_tid']['id'] = 'field_farm_crop_tid';
  $handler->display->display_options['filters']['field_farm_crop_tid']['table'] = 'field_data_field_farm_crop';
  $handler->display->display_options['filters']['field_farm_crop_tid']['field'] = 'field_farm_crop_tid';
  $handler->display->display_options['filters']['field_farm_crop_tid']['relationship'] = 'field_farm_harvest_list_value';
  $handler->display->display_options['filters']['field_farm_crop_tid']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_crop_tid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_crop_tid']['expose']['operator_id'] = 'field_farm_crop_tid_op';
  $handler->display->display_options['filters']['field_farm_crop_tid']['expose']['label'] = 'Crops';
  $handler->display->display_options['filters']['field_farm_crop_tid']['expose']['operator'] = 'field_farm_crop_tid_op';
  $handler->display->display_options['filters']['field_farm_crop_tid']['expose']['identifier'] = 'field_farm_crop_tid';
  $handler->display->display_options['filters']['field_farm_crop_tid']['expose']['multiple'] = TRUE;
  $handler->display->display_options['filters']['field_farm_crop_tid']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    30037204 => 0,
    200153887 => 0,
    125843996 => 0,
    173712484 => 0,
    196317508 => 0,
    146437786 => 0,
  );
  $handler->display->display_options['filters']['field_farm_crop_tid']['type'] = 'select';
  $handler->display->display_options['filters']['field_farm_crop_tid']['vocabulary'] = 'farm_crops';
  $handler->display->display_options['filters']['field_farm_crop_tid']['hierarchy'] = 1;
  $handler->display->display_options['path'] = 'taxonomy/term/%/harvests';
  $handler->display->display_options['menu']['title'] = 'Harvests';
  $handler->display->display_options['menu']['weight'] = '40';
  $handler->display->display_options['menu']['name'] = 'management';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;

  /* Display: Harvests by crop (Page) */
  $handler = $view->new_display('page', 'Harvests by crop (Page)', 'page_2');
  $handler->display->display_options['defaults']['style_plugin'] = FALSE;
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['grouping'] = array(
    0 => array(
      'field' => 'id',
      'rendered' => 1,
      'rendered_strip' => 0,
    ),
  );
  $handler->display->display_options['style_options']['columns'] = array(
    'id' => 'id',
    'field_farm_date' => 'field_farm_date',
    'field_farm_quantity' => 'field_farm_quantity',
    'field_farm_areas' => 'field_farm_areas',
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
    'field_farm_quantity' => array(
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
  $handler->display->display_options['fields']['id']['label'] = 'Harvest ID';
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
  /* Field: Field: Quantity */
  $handler->display->display_options['fields']['field_farm_quantity']['id'] = 'field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['table'] = 'field_data_field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['field'] = 'field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['relationship'] = 'field_farm_harvest_list_value';
  $handler->display->display_options['fields']['field_farm_quantity']['type'] = 'field_collection_fields';
  $handler->display->display_options['fields']['field_farm_quantity']['settings'] = array(
    'view_mode' => 'full',
  );
  /* Field: Log: Areas */
  $handler->display->display_options['fields']['field_farm_areas']['id'] = 'field_farm_areas';
  $handler->display->display_options['fields']['field_farm_areas']['table'] = 'field_data_field_farm_areas';
  $handler->display->display_options['fields']['field_farm_areas']['field'] = 'field_farm_areas';
  $handler->display->display_options['fields']['field_farm_areas']['delta_offset'] = '0';
  /* Field: Global: Custom text */
  $handler->display->display_options['fields']['nothing']['id'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['table'] = 'views';
  $handler->display->display_options['fields']['nothing']['field'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['label'] = 'Operations';
  $handler->display->display_options['fields']['nothing']['alter']['text'] = '<a href="/log/[id]/edit?destination=admin/farm/logs/harvests">Edit</a> | <a href="/log/[id]/delete?destination=admin/farm/logs/harvests">Delete</a>';
  $handler->display->display_options['defaults']['arguments'] = FALSE;
  /* Contextual filter: Field: Crop (field_farm_crop) */
  $handler->display->display_options['arguments']['field_farm_crop_tid']['id'] = 'field_farm_crop_tid';
  $handler->display->display_options['arguments']['field_farm_crop_tid']['table'] = 'field_data_field_farm_crop';
  $handler->display->display_options['arguments']['field_farm_crop_tid']['field'] = 'field_farm_crop_tid';
  $handler->display->display_options['arguments']['field_farm_crop_tid']['relationship'] = 'field_farm_harvest_list_value';
  $handler->display->display_options['arguments']['field_farm_crop_tid']['default_action'] = 'not found';
  $handler->display->display_options['arguments']['field_farm_crop_tid']['title_enable'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_crop_tid']['title'] = 'Harvests of %1';
  $handler->display->display_options['arguments']['field_farm_crop_tid']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['field_farm_crop_tid']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['field_farm_crop_tid']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['field_farm_crop_tid']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['arguments']['field_farm_crop_tid']['specify_validation'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_crop_tid']['validate']['type'] = 'taxonomy_term';
  $handler->display->display_options['arguments']['field_farm_crop_tid']['validate_options']['vocabularies'] = array(
    'farm_crops' => 'farm_crops',
  );
  $handler->display->display_options['defaults']['filter_groups'] = FALSE;
  $handler->display->display_options['defaults']['filters'] = FALSE;
  /* Filter criterion: Log: Log type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'log';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'farm_harvest' => 'farm_harvest',
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
  $handler->display->display_options['path'] = 'taxonomy/term/%/harvests';
  $handler->display->display_options['menu']['title'] = 'Harvests';
  $handler->display->display_options['menu']['weight'] = '40';
  $handler->display->display_options['menu']['name'] = 'management';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;
  $export['farm_log_harvest'] = $view;

  $view = new view();
  $view->name = 'farm_log_input';
  $view->description = '';
  $view->tag = 'farm_log_crops';
  $view->base_table = 'log';
  $view->human_name = 'Farm Log: Input';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Inputs';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['access']['perm'] = 'view any farm_input log entities';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['pager']['options']['items_per_page'] = '50';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['pager']['options']['id'] = '0';
  $handler->display->display_options['pager']['options']['quantity'] = '9';
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['columns'] = array(
    'id' => 'id',
    'field_farm_date' => 'field_farm_date',
    'field_farm_input_type' => 'field_farm_input_type',
    'field_farm_input' => 'field_farm_input',
    'field_farm_crops' => 'field_farm_crops',
    'field_farm_areas' => 'field_farm_areas',
    'field_farm_quantity' => 'field_farm_quantity',
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
    'field_farm_input_type' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_input' => array(
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
    'field_farm_quantity' => array(
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
  $handler->display->display_options['footer']['area_text_custom']['content'] = '<a href="/log/add/farm_input?destination=admin/farm/logs/inputs">+ Log an input</a>';
  /* No results behavior: Global: Unfiltered text */
  $handler->display->display_options['empty']['area_text_custom']['id'] = 'area_text_custom';
  $handler->display->display_options['empty']['area_text_custom']['table'] = 'views';
  $handler->display->display_options['empty']['area_text_custom']['field'] = 'area_text_custom';
  $handler->display->display_options['empty']['area_text_custom']['empty'] = TRUE;
  $handler->display->display_options['empty']['area_text_custom']['content'] = 'No inputs found.';
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
  /* Field: Log: Input Type */
  $handler->display->display_options['fields']['field_farm_input_type']['id'] = 'field_farm_input_type';
  $handler->display->display_options['fields']['field_farm_input_type']['table'] = 'field_data_field_farm_input_type';
  $handler->display->display_options['fields']['field_farm_input_type']['field'] = 'field_farm_input_type';
  /* Field: Log: Input */
  $handler->display->display_options['fields']['field_farm_input']['id'] = 'field_farm_input';
  $handler->display->display_options['fields']['field_farm_input']['table'] = 'field_data_field_farm_input';
  $handler->display->display_options['fields']['field_farm_input']['field'] = 'field_farm_input';
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
  /* Field: Field: Quantity */
  $handler->display->display_options['fields']['field_farm_quantity']['id'] = 'field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['table'] = 'field_data_field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['field'] = 'field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['type'] = 'field_collection_fields';
  $handler->display->display_options['fields']['field_farm_quantity']['settings'] = array(
    'view_mode' => 'full',
  );
  /* Field: Global: Custom text */
  $handler->display->display_options['fields']['nothing']['id'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['table'] = 'views';
  $handler->display->display_options['fields']['nothing']['field'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['label'] = 'Operations';
  $handler->display->display_options['fields']['nothing']['alter']['text'] = '<a href="/log/[id]/edit?destination=admin/farm/logs/inputs">Edit</a> | <a href="/log/[id]/delete?destination=admin/farm/logs/inputs">Delete</a>';
  /* Filter criterion: Log: Log type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'log';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'farm_input' => 'farm_input',
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
  /* Filter criterion: Log: Input Type (field_farm_input_type) */
  $handler->display->display_options['filters']['field_farm_input_type_tid']['id'] = 'field_farm_input_type_tid';
  $handler->display->display_options['filters']['field_farm_input_type_tid']['table'] = 'field_data_field_farm_input_type';
  $handler->display->display_options['filters']['field_farm_input_type_tid']['field'] = 'field_farm_input_type_tid';
  $handler->display->display_options['filters']['field_farm_input_type_tid']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_input_type_tid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_input_type_tid']['expose']['operator_id'] = 'field_farm_input_type_tid_op';
  $handler->display->display_options['filters']['field_farm_input_type_tid']['expose']['label'] = 'Input Types';
  $handler->display->display_options['filters']['field_farm_input_type_tid']['expose']['operator'] = 'field_farm_input_type_tid_op';
  $handler->display->display_options['filters']['field_farm_input_type_tid']['expose']['identifier'] = 'field_farm_input_type_tid';
  $handler->display->display_options['filters']['field_farm_input_type_tid']['expose']['multiple'] = TRUE;
  $handler->display->display_options['filters']['field_farm_input_type_tid']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    30037204 => 0,
    200153887 => 0,
    125843996 => 0,
    173712484 => 0,
    196317508 => 0,
    146437786 => 0,
  );
  $handler->display->display_options['filters']['field_farm_input_type_tid']['type'] = 'select';
  $handler->display->display_options['filters']['field_farm_input_type_tid']['vocabulary'] = 'farm_input_types';
  $handler->display->display_options['filters']['field_farm_input_type_tid']['hierarchy'] = 1;
  /* Filter criterion: Log: Input (field_farm_input) */
  $handler->display->display_options['filters']['field_farm_input_tid']['id'] = 'field_farm_input_tid';
  $handler->display->display_options['filters']['field_farm_input_tid']['table'] = 'field_data_field_farm_input';
  $handler->display->display_options['filters']['field_farm_input_tid']['field'] = 'field_farm_input_tid';
  $handler->display->display_options['filters']['field_farm_input_tid']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_input_tid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_input_tid']['expose']['operator_id'] = 'field_farm_input_tid_op';
  $handler->display->display_options['filters']['field_farm_input_tid']['expose']['label'] = 'Inputs';
  $handler->display->display_options['filters']['field_farm_input_tid']['expose']['operator'] = 'field_farm_input_tid_op';
  $handler->display->display_options['filters']['field_farm_input_tid']['expose']['identifier'] = 'field_farm_input_tid';
  $handler->display->display_options['filters']['field_farm_input_tid']['expose']['multiple'] = TRUE;
  $handler->display->display_options['filters']['field_farm_input_tid']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    30037204 => 0,
    200153887 => 0,
    125843996 => 0,
    173712484 => 0,
    196317508 => 0,
    146437786 => 0,
  );
  $handler->display->display_options['filters']['field_farm_input_tid']['type'] = 'select';
  $handler->display->display_options['filters']['field_farm_input_tid']['vocabulary'] = 'farm_inputs';
  $handler->display->display_options['filters']['field_farm_input_tid']['hierarchy'] = 1;
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

  /* Display: All inputs (Page) */
  $handler = $view->new_display('page', 'All inputs (Page)', 'page');
  $handler->display->display_options['path'] = 'admin/farm/logs/inputs';
  $handler->display->display_options['menu']['type'] = 'tab';
  $handler->display->display_options['menu']['title'] = 'Inputs';
  $handler->display->display_options['menu']['weight'] = '10';
  $handler->display->display_options['menu']['name'] = 'management';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;
  $handler->display->display_options['tab_options']['type'] = 'normal';
  $handler->display->display_options['tab_options']['title'] = 'Logs';
  $handler->display->display_options['tab_options']['weight'] = '0';
  $handler->display->display_options['tab_options']['name'] = 'management';

  /* Display: Inputs by area (Page) */
  $handler = $view->new_display('page', 'Inputs by area (Page)', 'page_1');
  $handler->display->display_options['defaults']['style_plugin'] = FALSE;
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['columns'] = array(
    'id' => 'id',
    'field_farm_date' => 'field_farm_date',
    'field_farm_input_type' => 'field_farm_input_type',
    'field_farm_input' => 'field_farm_input',
    'field_farm_crops' => 'field_farm_crops',
    'field_farm_quantity' => 'field_farm_quantity',
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
    'field_farm_input_type' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_input' => array(
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
    'field_farm_quantity' => array(
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
  /* Field: Log: Input Type */
  $handler->display->display_options['fields']['field_farm_input_type']['id'] = 'field_farm_input_type';
  $handler->display->display_options['fields']['field_farm_input_type']['table'] = 'field_data_field_farm_input_type';
  $handler->display->display_options['fields']['field_farm_input_type']['field'] = 'field_farm_input_type';
  /* Field: Log: Input */
  $handler->display->display_options['fields']['field_farm_input']['id'] = 'field_farm_input';
  $handler->display->display_options['fields']['field_farm_input']['table'] = 'field_data_field_farm_input';
  $handler->display->display_options['fields']['field_farm_input']['field'] = 'field_farm_input';
  /* Field: Log: Crops */
  $handler->display->display_options['fields']['field_farm_crops']['id'] = 'field_farm_crops';
  $handler->display->display_options['fields']['field_farm_crops']['table'] = 'field_data_field_farm_crops';
  $handler->display->display_options['fields']['field_farm_crops']['field'] = 'field_farm_crops';
  $handler->display->display_options['fields']['field_farm_crops']['delta_offset'] = '0';
  /* Field: Field: Quantity */
  $handler->display->display_options['fields']['field_farm_quantity']['id'] = 'field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['table'] = 'field_data_field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['field'] = 'field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['type'] = 'field_collection_fields';
  $handler->display->display_options['fields']['field_farm_quantity']['settings'] = array(
    'view_mode' => 'full',
  );
  /* Field: Global: Custom text */
  $handler->display->display_options['fields']['nothing']['id'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['table'] = 'views';
  $handler->display->display_options['fields']['nothing']['field'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['label'] = 'Operations';
  $handler->display->display_options['fields']['nothing']['alter']['text'] = '<a href="/log/[id]/edit?destination=admin/farm/logs/inputs">Edit</a> | <a href="/log/[id]/delete?destination=admin/farm/logs/inputs">Delete</a>';
  $handler->display->display_options['defaults']['arguments'] = FALSE;
  /* Contextual filter: Log: Areas (field_farm_areas) */
  $handler->display->display_options['arguments']['field_farm_areas_tid']['id'] = 'field_farm_areas_tid';
  $handler->display->display_options['arguments']['field_farm_areas_tid']['table'] = 'field_data_field_farm_areas';
  $handler->display->display_options['arguments']['field_farm_areas_tid']['field'] = 'field_farm_areas_tid';
  $handler->display->display_options['arguments']['field_farm_areas_tid']['default_action'] = 'not found';
  $handler->display->display_options['arguments']['field_farm_areas_tid']['title_enable'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_areas_tid']['title'] = 'Inputs to %1';
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
    'farm_input' => 'farm_input',
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
  /* Filter criterion: Log: Input Type (field_farm_input_type) */
  $handler->display->display_options['filters']['field_farm_input_type_tid']['id'] = 'field_farm_input_type_tid';
  $handler->display->display_options['filters']['field_farm_input_type_tid']['table'] = 'field_data_field_farm_input_type';
  $handler->display->display_options['filters']['field_farm_input_type_tid']['field'] = 'field_farm_input_type_tid';
  $handler->display->display_options['filters']['field_farm_input_type_tid']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_input_type_tid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_input_type_tid']['expose']['operator_id'] = 'field_farm_input_type_tid_op';
  $handler->display->display_options['filters']['field_farm_input_type_tid']['expose']['label'] = 'Input Types';
  $handler->display->display_options['filters']['field_farm_input_type_tid']['expose']['operator'] = 'field_farm_input_type_tid_op';
  $handler->display->display_options['filters']['field_farm_input_type_tid']['expose']['identifier'] = 'field_farm_input_type_tid';
  $handler->display->display_options['filters']['field_farm_input_type_tid']['expose']['multiple'] = TRUE;
  $handler->display->display_options['filters']['field_farm_input_type_tid']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    30037204 => 0,
    200153887 => 0,
    125843996 => 0,
    173712484 => 0,
    196317508 => 0,
    146437786 => 0,
  );
  $handler->display->display_options['filters']['field_farm_input_type_tid']['type'] = 'select';
  $handler->display->display_options['filters']['field_farm_input_type_tid']['vocabulary'] = 'farm_input_types';
  $handler->display->display_options['filters']['field_farm_input_type_tid']['hierarchy'] = 1;
  /* Filter criterion: Log: Input (field_farm_input) */
  $handler->display->display_options['filters']['field_farm_input_tid']['id'] = 'field_farm_input_tid';
  $handler->display->display_options['filters']['field_farm_input_tid']['table'] = 'field_data_field_farm_input';
  $handler->display->display_options['filters']['field_farm_input_tid']['field'] = 'field_farm_input_tid';
  $handler->display->display_options['filters']['field_farm_input_tid']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_input_tid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_input_tid']['expose']['operator_id'] = 'field_farm_input_tid_op';
  $handler->display->display_options['filters']['field_farm_input_tid']['expose']['label'] = 'Inputs';
  $handler->display->display_options['filters']['field_farm_input_tid']['expose']['operator'] = 'field_farm_input_tid_op';
  $handler->display->display_options['filters']['field_farm_input_tid']['expose']['identifier'] = 'field_farm_input_tid';
  $handler->display->display_options['filters']['field_farm_input_tid']['expose']['multiple'] = TRUE;
  $handler->display->display_options['filters']['field_farm_input_tid']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    30037204 => 0,
    200153887 => 0,
    125843996 => 0,
    173712484 => 0,
    196317508 => 0,
    146437786 => 0,
  );
  $handler->display->display_options['filters']['field_farm_input_tid']['type'] = 'select';
  $handler->display->display_options['filters']['field_farm_input_tid']['vocabulary'] = 'farm_inputs';
  $handler->display->display_options['filters']['field_farm_input_tid']['hierarchy'] = 1;
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
  $handler->display->display_options['path'] = 'taxonomy/term/%/inputs';
  $handler->display->display_options['menu']['title'] = 'Inputs';
  $handler->display->display_options['menu']['weight'] = '20';
  $handler->display->display_options['menu']['name'] = 'management';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;

  /* Display: Inputs by crop (Page) */
  $handler = $view->new_display('page', 'Inputs by crop (Page)', 'page_2');
  $handler->display->display_options['defaults']['style_plugin'] = FALSE;
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['columns'] = array(
    'id' => 'id',
    'field_farm_date' => 'field_farm_date',
    'field_farm_input_type' => 'field_farm_input_type',
    'field_farm_input' => 'field_farm_input',
    'field_farm_areas' => 'field_farm_areas',
    'field_farm_quantity' => 'field_farm_quantity',
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
    'field_farm_input_type' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_input' => array(
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
    'field_farm_quantity' => array(
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
  /* Field: Log: Input Type */
  $handler->display->display_options['fields']['field_farm_input_type']['id'] = 'field_farm_input_type';
  $handler->display->display_options['fields']['field_farm_input_type']['table'] = 'field_data_field_farm_input_type';
  $handler->display->display_options['fields']['field_farm_input_type']['field'] = 'field_farm_input_type';
  /* Field: Log: Input */
  $handler->display->display_options['fields']['field_farm_input']['id'] = 'field_farm_input';
  $handler->display->display_options['fields']['field_farm_input']['table'] = 'field_data_field_farm_input';
  $handler->display->display_options['fields']['field_farm_input']['field'] = 'field_farm_input';
  /* Field: Log: Areas */
  $handler->display->display_options['fields']['field_farm_areas']['id'] = 'field_farm_areas';
  $handler->display->display_options['fields']['field_farm_areas']['table'] = 'field_data_field_farm_areas';
  $handler->display->display_options['fields']['field_farm_areas']['field'] = 'field_farm_areas';
  $handler->display->display_options['fields']['field_farm_areas']['delta_offset'] = '0';
  /* Field: Field: Quantity */
  $handler->display->display_options['fields']['field_farm_quantity']['id'] = 'field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['table'] = 'field_data_field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['field'] = 'field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['type'] = 'field_collection_fields';
  $handler->display->display_options['fields']['field_farm_quantity']['settings'] = array(
    'view_mode' => 'full',
  );
  /* Field: Global: Custom text */
  $handler->display->display_options['fields']['nothing']['id'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['table'] = 'views';
  $handler->display->display_options['fields']['nothing']['field'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['label'] = 'Operations';
  $handler->display->display_options['fields']['nothing']['alter']['text'] = '<a href="/log/[id]/edit?destination=admin/farm/logs/inputs">Edit</a> | <a href="/log/[id]/delete?destination=admin/farm/logs/inputs">Delete</a>';
  $handler->display->display_options['defaults']['arguments'] = FALSE;
  /* Contextual filter: Log: Crops (field_farm_crops) */
  $handler->display->display_options['arguments']['field_farm_crops_tid']['id'] = 'field_farm_crops_tid';
  $handler->display->display_options['arguments']['field_farm_crops_tid']['table'] = 'field_data_field_farm_crops';
  $handler->display->display_options['arguments']['field_farm_crops_tid']['field'] = 'field_farm_crops_tid';
  $handler->display->display_options['arguments']['field_farm_crops_tid']['default_action'] = 'not found';
  $handler->display->display_options['arguments']['field_farm_crops_tid']['title_enable'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_crops_tid']['title'] = 'Inputs to %1';
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
    'farm_input' => 'farm_input',
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
  /* Filter criterion: Log: Input Type (field_farm_input_type) */
  $handler->display->display_options['filters']['field_farm_input_type_tid']['id'] = 'field_farm_input_type_tid';
  $handler->display->display_options['filters']['field_farm_input_type_tid']['table'] = 'field_data_field_farm_input_type';
  $handler->display->display_options['filters']['field_farm_input_type_tid']['field'] = 'field_farm_input_type_tid';
  $handler->display->display_options['filters']['field_farm_input_type_tid']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_input_type_tid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_input_type_tid']['expose']['operator_id'] = 'field_farm_input_type_tid_op';
  $handler->display->display_options['filters']['field_farm_input_type_tid']['expose']['label'] = 'Input Types';
  $handler->display->display_options['filters']['field_farm_input_type_tid']['expose']['operator'] = 'field_farm_input_type_tid_op';
  $handler->display->display_options['filters']['field_farm_input_type_tid']['expose']['identifier'] = 'field_farm_input_type_tid';
  $handler->display->display_options['filters']['field_farm_input_type_tid']['expose']['multiple'] = TRUE;
  $handler->display->display_options['filters']['field_farm_input_type_tid']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    30037204 => 0,
    200153887 => 0,
    125843996 => 0,
    173712484 => 0,
    196317508 => 0,
    146437786 => 0,
  );
  $handler->display->display_options['filters']['field_farm_input_type_tid']['type'] = 'select';
  $handler->display->display_options['filters']['field_farm_input_type_tid']['vocabulary'] = 'farm_input_types';
  $handler->display->display_options['filters']['field_farm_input_type_tid']['hierarchy'] = 1;
  /* Filter criterion: Log: Input (field_farm_input) */
  $handler->display->display_options['filters']['field_farm_input_tid']['id'] = 'field_farm_input_tid';
  $handler->display->display_options['filters']['field_farm_input_tid']['table'] = 'field_data_field_farm_input';
  $handler->display->display_options['filters']['field_farm_input_tid']['field'] = 'field_farm_input_tid';
  $handler->display->display_options['filters']['field_farm_input_tid']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_input_tid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_input_tid']['expose']['operator_id'] = 'field_farm_input_tid_op';
  $handler->display->display_options['filters']['field_farm_input_tid']['expose']['label'] = 'Inputs';
  $handler->display->display_options['filters']['field_farm_input_tid']['expose']['operator'] = 'field_farm_input_tid_op';
  $handler->display->display_options['filters']['field_farm_input_tid']['expose']['identifier'] = 'field_farm_input_tid';
  $handler->display->display_options['filters']['field_farm_input_tid']['expose']['multiple'] = TRUE;
  $handler->display->display_options['filters']['field_farm_input_tid']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    30037204 => 0,
    200153887 => 0,
    125843996 => 0,
    173712484 => 0,
    196317508 => 0,
    146437786 => 0,
  );
  $handler->display->display_options['filters']['field_farm_input_tid']['type'] = 'select';
  $handler->display->display_options['filters']['field_farm_input_tid']['vocabulary'] = 'farm_inputs';
  $handler->display->display_options['filters']['field_farm_input_tid']['hierarchy'] = 1;
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
  $handler->display->display_options['path'] = 'taxonomy/term/%/inputs';
  $handler->display->display_options['menu']['title'] = 'Inputs';
  $handler->display->display_options['menu']['weight'] = '20';
  $handler->display->display_options['menu']['name'] = 'management';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;
  $export['farm_log_input'] = $view;

  return $export;
}
