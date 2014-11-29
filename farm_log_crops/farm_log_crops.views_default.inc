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
    'name' => 'name',
    'field_farm_quantity' => 'field_farm_quantity',
  );
  $handler->display->display_options['style_options']['default'] = '-1';
  $handler->display->display_options['style_options']['info'] = array(
    'id' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_date' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'name' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_quantity' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
  );
  $handler->display->display_options['style_options']['empty_table'] = TRUE;
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
  /* Field: Log: Name */
  $handler->display->display_options['fields']['name']['id'] = 'name';
  $handler->display->display_options['fields']['name']['table'] = 'log';
  $handler->display->display_options['fields']['name']['field'] = 'name';
  $handler->display->display_options['fields']['name']['label'] = 'Input';
  $handler->display->display_options['fields']['name']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['name']['alter']['path'] = 'log/[id]';
  /* Field: Log: Quantity */
  $handler->display->display_options['fields']['field_farm_quantity']['id'] = 'field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['table'] = 'field_data_field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['field'] = 'field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['type'] = 'field_collection_fields';
  $handler->display->display_options['fields']['field_farm_quantity']['settings'] = array(
    'view_mode' => 'full',
  );
  /* Sort criterion: Field: Date (field_farm_date) */
  $handler->display->display_options['sorts']['field_farm_date_value']['id'] = 'field_farm_date_value';
  $handler->display->display_options['sorts']['field_farm_date_value']['table'] = 'field_data_field_farm_date';
  $handler->display->display_options['sorts']['field_farm_date_value']['field'] = 'field_farm_date_value';
  $handler->display->display_options['sorts']['field_farm_date_value']['order'] = 'DESC';
  $handler->display->display_options['sorts']['field_farm_date_value']['exposed'] = TRUE;
  $handler->display->display_options['sorts']['field_farm_date_value']['expose']['label'] = 'Date';
  /* Sort criterion: Log: Log ID */
  $handler->display->display_options['sorts']['id']['id'] = 'id';
  $handler->display->display_options['sorts']['id']['table'] = 'log';
  $handler->display->display_options['sorts']['id']['field'] = 'id';
  $handler->display->display_options['sorts']['id']['order'] = 'DESC';
  /* Filter criterion: Log: Log type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'log';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'farm_input' => 'farm_input',
  );
  $handler->display->display_options['filters']['type']['group'] = 1;
  /* Filter criterion: Log: Name */
  $handler->display->display_options['filters']['name']['id'] = 'name';
  $handler->display->display_options['filters']['name']['table'] = 'log';
  $handler->display->display_options['filters']['name']['field'] = 'name';
  $handler->display->display_options['filters']['name']['operator'] = 'contains';
  $handler->display->display_options['filters']['name']['group'] = 1;
  $handler->display->display_options['filters']['name']['exposed'] = TRUE;
  $handler->display->display_options['filters']['name']['expose']['operator_id'] = 'name_op';
  $handler->display->display_options['filters']['name']['expose']['label'] = 'Name';
  $handler->display->display_options['filters']['name']['expose']['operator'] = 'name_op';
  $handler->display->display_options['filters']['name']['expose']['identifier'] = 'name';
  $handler->display->display_options['filters']['name']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    30037204 => 0,
    200153887 => 0,
    125843996 => 0,
    173712484 => 0,
    196317508 => 0,
    146437786 => 0,
  );
  /* Filter criterion: Field: Date (field_farm_date) */
  $handler->display->display_options['filters']['field_farm_date_value']['id'] = 'field_farm_date_value';
  $handler->display->display_options['filters']['field_farm_date_value']['table'] = 'field_data_field_farm_date';
  $handler->display->display_options['filters']['field_farm_date_value']['field'] = 'field_farm_date_value';
  $handler->display->display_options['filters']['field_farm_date_value']['operator'] = '<=';
  $handler->display->display_options['filters']['field_farm_date_value']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_date_value']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_date_value']['expose']['operator_id'] = 'field_farm_date_value_op';
  $handler->display->display_options['filters']['field_farm_date_value']['expose']['label'] = 'Start date';
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
    146437786 => 0,
  );
  $handler->display->display_options['filters']['field_farm_date_value']['default_date'] = 'now';
  $handler->display->display_options['filters']['field_farm_date_value']['year_range'] = '-10:+3';
  /* Filter criterion: Field: Date (field_farm_date) */
  $handler->display->display_options['filters']['field_farm_date_value_1']['id'] = 'field_farm_date_value_1';
  $handler->display->display_options['filters']['field_farm_date_value_1']['table'] = 'field_data_field_farm_date';
  $handler->display->display_options['filters']['field_farm_date_value_1']['field'] = 'field_farm_date_value';
  $handler->display->display_options['filters']['field_farm_date_value_1']['operator'] = '>=';
  $handler->display->display_options['filters']['field_farm_date_value_1']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_date_value_1']['expose']['operator_id'] = 'field_farm_date_value_1_op';
  $handler->display->display_options['filters']['field_farm_date_value_1']['expose']['label'] = 'End date';
  $handler->display->display_options['filters']['field_farm_date_value_1']['expose']['operator'] = 'field_farm_date_value_1_op';
  $handler->display->display_options['filters']['field_farm_date_value_1']['expose']['identifier'] = 'field_farm_date_value_1';
  $handler->display->display_options['filters']['field_farm_date_value_1']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    30037204 => 0,
    200153887 => 0,
    125843996 => 0,
    173712484 => 0,
    196317508 => 0,
    146437786 => 0,
  );
  $handler->display->display_options['filters']['field_farm_date_value_1']['default_date'] = 'now';
  $handler->display->display_options['filters']['field_farm_date_value_1']['year_range'] = '-10:+3';

  /* Display: All inputs (Page) */
  $handler = $view->new_display('page', 'All inputs (Page)', 'page');
  $handler->display->display_options['path'] = 'admin/farm/plan/inputs';
  $handler->display->display_options['menu']['type'] = 'tab';
  $handler->display->display_options['menu']['title'] = 'Inputs';
  $handler->display->display_options['menu']['weight'] = '0';
  $handler->display->display_options['menu']['name'] = 'management';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;
  $handler->display->display_options['tab_options']['type'] = 'normal';
  $handler->display->display_options['tab_options']['title'] = 'Logs';
  $handler->display->display_options['tab_options']['weight'] = '0';
  $handler->display->display_options['tab_options']['name'] = 'management';
  $export['farm_log_input'] = $view;

  return $export;
}
