<?php
/**
 * @file
 * farm_log_sales.views_default.inc
 */

/**
 * Implements hook_views_default_views().
 */
function farm_log_sales_views_default_views() {
  $export = array();

  $view = new view();
  $view->name = 'farm_log_loss';
  $view->description = '';
  $view->tag = 'farm_log_money';
  $view->base_table = 'log';
  $view->human_name = 'Farm Loss Log';
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
    'id' => 'id',
    'timestamp' => 'timestamp',
    'type' => 'type',
    'name' => 'name',
    'nothing' => 'nothing',
  );
  $handler->display->display_options['style_options']['default'] = '-1';
  $handler->display->display_options['style_options']['info'] = array(
    'id' => array(
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
  /* Field: Log: Name */
  $handler->display->display_options['fields']['name']['id'] = 'name';
  $handler->display->display_options['fields']['name']['table'] = 'log';
  $handler->display->display_options['fields']['name']['field'] = 'name';
  $handler->display->display_options['fields']['name']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['name']['alter']['path'] = 'log/[id]';
  /* Sort criterion: Log: Date (field_farm_date) */
  $handler->display->display_options['sorts']['field_farm_date_value']['id'] = 'field_farm_date_value';
  $handler->display->display_options['sorts']['field_farm_date_value']['table'] = 'field_data_field_farm_date';
  $handler->display->display_options['sorts']['field_farm_date_value']['field'] = 'field_farm_date_value';
  $handler->display->display_options['sorts']['field_farm_date_value']['order'] = 'DESC';
  /* Filter criterion: Log: Type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'log';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'farm_loss' => 'farm_loss',
  );

  /* Display: Loss log (page) */
  $handler = $view->new_display('page', 'Loss log (page)', 'page');
  $handler->display->display_options['defaults']['title'] = FALSE;
  $handler->display->display_options['title'] = 'Loss Log';
  $handler->display->display_options['defaults']['style_plugin'] = FALSE;
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['columns'] = array(
    'id' => 'id',
    'field_farm_date' => 'field_farm_date',
    'name' => 'name',
    'field_farm_value' => 'field_farm_value',
    'field_farm_quantity_value' => 'field_farm_quantity_value',
    'field_farm_quantity_units' => 'field_farm_quantity_units',
    'field_farm_crops' => 'field_farm_crops',
    'field_farm_areas' => 'field_farm_areas',
    'field_farm_issues' => 'field_farm_issues',
    'field_farm_plantings' => 'field_farm_plantings',
    'field_farm_losses' => 'field_farm_losses',
    'nothing' => 'nothing',
  );
  $handler->display->display_options['style_options']['default'] = '-1';
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
    'name' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_value' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_quantity_value' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_quantity_units' => array(
      'sortable' => 0,
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
    'field_farm_plantings' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_losses' => array(
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
  $handler->display->display_options['defaults']['header'] = FALSE;
  /* Header: Global: Unfiltered text */
  $handler->display->display_options['header']['area_text_custom']['id'] = 'area_text_custom';
  $handler->display->display_options['header']['area_text_custom']['table'] = 'views';
  $handler->display->display_options['header']['area_text_custom']['field'] = 'area_text_custom';
  $handler->display->display_options['header']['area_text_custom']['empty'] = TRUE;
  $handler->display->display_options['header']['area_text_custom']['content'] = '<a href="/log/add/farm_loss">+ Log a loss</a>';
  $handler->display->display_options['defaults']['fields'] = FALSE;
  /* Field: Log: Log ID */
  $handler->display->display_options['fields']['id']['id'] = 'id';
  $handler->display->display_options['fields']['id']['table'] = 'log';
  $handler->display->display_options['fields']['id']['field'] = 'id';
  $handler->display->display_options['fields']['id']['exclude'] = TRUE;
  /* Field: Log: Date */
  $handler->display->display_options['fields']['field_farm_date']['id'] = 'field_farm_date';
  $handler->display->display_options['fields']['field_farm_date']['table'] = 'field_data_field_farm_date';
  $handler->display->display_options['fields']['field_farm_date']['field'] = 'field_farm_date';
  $handler->display->display_options['fields']['field_farm_date']['settings'] = array(
    'format_type' => 'short',
    'fromto' => 'both',
    'multiple_number' => '',
    'multiple_from' => '',
    'multiple_to' => '',
  );
  /* Field: Log: Name */
  $handler->display->display_options['fields']['name']['id'] = 'name';
  $handler->display->display_options['fields']['name']['table'] = 'log';
  $handler->display->display_options['fields']['name']['field'] = 'name';
  $handler->display->display_options['fields']['name']['label'] = 'Loss name';
  $handler->display->display_options['fields']['name']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['name']['alter']['path'] = 'log/[id]';
  /* Field: Log: Value */
  $handler->display->display_options['fields']['field_farm_value']['id'] = 'field_farm_value';
  $handler->display->display_options['fields']['field_farm_value']['table'] = 'field_data_field_farm_value';
  $handler->display->display_options['fields']['field_farm_value']['field'] = 'field_farm_value';
  $handler->display->display_options['fields']['field_farm_value']['type'] = 'fraction_decimal';
  $handler->display->display_options['fields']['field_farm_value']['settings'] = array(
    'precision' => '2',
    'auto_precision' => 1,
  );
  /* Field: Log: Quantity */
  $handler->display->display_options['fields']['field_farm_quantity_value']['id'] = 'field_farm_quantity_value';
  $handler->display->display_options['fields']['field_farm_quantity_value']['table'] = 'field_data_field_farm_quantity_value';
  $handler->display->display_options['fields']['field_farm_quantity_value']['field'] = 'field_farm_quantity_value';
  $handler->display->display_options['fields']['field_farm_quantity_value']['type'] = 'fraction_decimal';
  $handler->display->display_options['fields']['field_farm_quantity_value']['settings'] = array(
    'precision' => '3',
    'auto_precision' => 1,
  );
  /* Field: Log: Units */
  $handler->display->display_options['fields']['field_farm_quantity_units']['id'] = 'field_farm_quantity_units';
  $handler->display->display_options['fields']['field_farm_quantity_units']['table'] = 'field_data_field_farm_quantity_units';
  $handler->display->display_options['fields']['field_farm_quantity_units']['field'] = 'field_farm_quantity_units';
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
  /* Field: Log: Related Issues */
  $handler->display->display_options['fields']['field_farm_issues']['id'] = 'field_farm_issues';
  $handler->display->display_options['fields']['field_farm_issues']['table'] = 'field_data_field_farm_issues';
  $handler->display->display_options['fields']['field_farm_issues']['field'] = 'field_farm_issues';
  $handler->display->display_options['fields']['field_farm_issues']['settings'] = array(
    'link' => 1,
  );
  $handler->display->display_options['fields']['field_farm_issues']['delta_offset'] = '0';
  /* Field: Log: Related Plantings */
  $handler->display->display_options['fields']['field_farm_plantings']['id'] = 'field_farm_plantings';
  $handler->display->display_options['fields']['field_farm_plantings']['table'] = 'field_data_field_farm_plantings';
  $handler->display->display_options['fields']['field_farm_plantings']['field'] = 'field_farm_plantings';
  $handler->display->display_options['fields']['field_farm_plantings']['settings'] = array(
    'link' => 1,
  );
  $handler->display->display_options['fields']['field_farm_plantings']['delta_offset'] = '0';
  /* Field: Log: Related Losses */
  $handler->display->display_options['fields']['field_farm_losses']['id'] = 'field_farm_losses';
  $handler->display->display_options['fields']['field_farm_losses']['table'] = 'field_data_field_farm_losses';
  $handler->display->display_options['fields']['field_farm_losses']['field'] = 'field_farm_losses';
  $handler->display->display_options['fields']['field_farm_losses']['settings'] = array(
    'link' => 1,
  );
  $handler->display->display_options['fields']['field_farm_losses']['delta_offset'] = '0';
  /* Field: Global: Custom text */
  $handler->display->display_options['fields']['nothing']['id'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['table'] = 'views';
  $handler->display->display_options['fields']['nothing']['field'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['label'] = 'Operations';
  $handler->display->display_options['fields']['nothing']['alter']['text'] = '<a href="/log/[id]/edit">Edit</a> | <a href="/log/[id]/delete">Delete</a>';
  $handler->display->display_options['defaults']['filter_groups'] = FALSE;
  $handler->display->display_options['defaults']['filters'] = FALSE;
  /* Filter criterion: Log: Type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'log';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'farm_loss' => 'farm_loss',
  );
  $handler->display->display_options['filters']['type']['group'] = 1;
  /* Filter criterion: Log: Date (field_farm_date) */
  $handler->display->display_options['filters']['field_farm_date_value']['id'] = 'field_farm_date_value';
  $handler->display->display_options['filters']['field_farm_date_value']['table'] = 'field_data_field_farm_date';
  $handler->display->display_options['filters']['field_farm_date_value']['field'] = 'field_farm_date_value';
  $handler->display->display_options['filters']['field_farm_date_value']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_date_value']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_date_value']['expose']['operator_id'] = 'field_farm_date_value_op';
  $handler->display->display_options['filters']['field_farm_date_value']['expose']['label'] = 'Date';
  $handler->display->display_options['filters']['field_farm_date_value']['expose']['use_operator'] = TRUE;
  $handler->display->display_options['filters']['field_farm_date_value']['expose']['operator'] = 'field_farm_date_value_op';
  $handler->display->display_options['filters']['field_farm_date_value']['expose']['identifier'] = 'field_farm_date_value';
  $handler->display->display_options['filters']['field_farm_date_value']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
  );
  $handler->display->display_options['filters']['field_farm_date_value']['form_type'] = 'date_popup';
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
  $handler->display->display_options['path'] = 'logs/money/losses';
  $handler->display->display_options['menu']['type'] = 'tab';
  $handler->display->display_options['menu']['title'] = 'Losses';
  $handler->display->display_options['menu']['weight'] = '20';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;
  $handler->display->display_options['tab_options']['type'] = 'normal';
  $handler->display->display_options['tab_options']['title'] = 'Logs';
  $handler->display->display_options['tab_options']['weight'] = '0';
  $handler->display->display_options['tab_options']['name'] = 'management';

  /* Display: Entity Reference */
  $handler = $view->new_display('entityreference', 'Entity Reference', 'entityreference_1');
  $handler->display->display_options['defaults']['title'] = FALSE;
  $handler->display->display_options['pager']['type'] = 'some';
  $handler->display->display_options['pager']['options']['items_per_page'] = '25';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['defaults']['style_plugin'] = FALSE;
  $handler->display->display_options['style_plugin'] = 'entityreference_style';
  $handler->display->display_options['style_options']['search_fields'] = array(
    'name' => 'name',
  );
  $handler->display->display_options['defaults']['style_options'] = FALSE;
  $handler->display->display_options['defaults']['row_plugin'] = FALSE;
  $handler->display->display_options['row_plugin'] = 'entityreference_fields';
  $handler->display->display_options['defaults']['row_options'] = FALSE;
  $export['farm_log_loss'] = $view;

  $view = new view();
  $view->name = 'farm_log_purchase';
  $view->description = '';
  $view->tag = 'farm_log_money';
  $view->base_table = 'log';
  $view->human_name = 'Farm Purchase Log';
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
    'id' => 'id',
    'timestamp' => 'timestamp',
    'type' => 'type',
    'name' => 'name',
    'nothing' => 'nothing',
  );
  $handler->display->display_options['style_options']['default'] = '-1';
  $handler->display->display_options['style_options']['info'] = array(
    'id' => array(
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
  /* Field: Log: Name */
  $handler->display->display_options['fields']['name']['id'] = 'name';
  $handler->display->display_options['fields']['name']['table'] = 'log';
  $handler->display->display_options['fields']['name']['field'] = 'name';
  $handler->display->display_options['fields']['name']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['name']['alter']['path'] = 'log/[id]';
  /* Sort criterion: Log: Date (field_farm_date) */
  $handler->display->display_options['sorts']['field_farm_date_value']['id'] = 'field_farm_date_value';
  $handler->display->display_options['sorts']['field_farm_date_value']['table'] = 'field_data_field_farm_date';
  $handler->display->display_options['sorts']['field_farm_date_value']['field'] = 'field_farm_date_value';
  $handler->display->display_options['sorts']['field_farm_date_value']['order'] = 'DESC';
  /* Filter criterion: Log: Type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'log';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'farm_purchase' => 'farm_purchase',
  );

  /* Display: Purchase log (page) */
  $handler = $view->new_display('page', 'Purchase log (page)', 'page');
  $handler->display->display_options['defaults']['title'] = FALSE;
  $handler->display->display_options['title'] = 'Purchase Log';
  $handler->display->display_options['defaults']['style_plugin'] = FALSE;
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['columns'] = array(
    'id' => 'id',
    'name' => 'name',
    'field_farm_value' => 'field_farm_value',
    'field_farm_quantity_value' => 'field_farm_quantity_value',
    'field_farm_quantity_units' => 'field_farm_quantity_units',
    'field_farm_crops' => 'field_farm_crops',
    'field_farm_areas' => 'field_farm_areas',
    'field_farm_issues' => 'field_farm_issues',
    'field_farm_plantings' => 'field_farm_plantings',
    'field_farm_purchases' => 'field_farm_purchases',
    'nothing' => 'nothing',
    'field_farm_date' => 'field_farm_date',
  );
  $handler->display->display_options['style_options']['default'] = '-1';
  $handler->display->display_options['style_options']['info'] = array(
    'id' => array(
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
    'field_farm_value' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_quantity_value' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_quantity_units' => array(
      'sortable' => 0,
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
    'field_farm_plantings' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_purchases' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'nothing' => array(
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
  );
  $handler->display->display_options['defaults']['style_options'] = FALSE;
  $handler->display->display_options['defaults']['row_plugin'] = FALSE;
  $handler->display->display_options['defaults']['row_options'] = FALSE;
  $handler->display->display_options['defaults']['header'] = FALSE;
  /* Header: Global: Unfiltered text */
  $handler->display->display_options['header']['area_text_custom']['id'] = 'area_text_custom';
  $handler->display->display_options['header']['area_text_custom']['table'] = 'views';
  $handler->display->display_options['header']['area_text_custom']['field'] = 'area_text_custom';
  $handler->display->display_options['header']['area_text_custom']['empty'] = TRUE;
  $handler->display->display_options['header']['area_text_custom']['content'] = '<a href="/log/add/farm_purchase">+ Log a purchase</a>';
  $handler->display->display_options['defaults']['fields'] = FALSE;
  /* Field: Log: Log ID */
  $handler->display->display_options['fields']['id']['id'] = 'id';
  $handler->display->display_options['fields']['id']['table'] = 'log';
  $handler->display->display_options['fields']['id']['field'] = 'id';
  $handler->display->display_options['fields']['id']['exclude'] = TRUE;
  /* Field: Log: Date */
  $handler->display->display_options['fields']['field_farm_date']['id'] = 'field_farm_date';
  $handler->display->display_options['fields']['field_farm_date']['table'] = 'field_data_field_farm_date';
  $handler->display->display_options['fields']['field_farm_date']['field'] = 'field_farm_date';
  $handler->display->display_options['fields']['field_farm_date']['settings'] = array(
    'format_type' => 'short',
    'fromto' => 'both',
    'multiple_number' => '',
    'multiple_from' => '',
    'multiple_to' => '',
  );
  /* Field: Log: Name */
  $handler->display->display_options['fields']['name']['id'] = 'name';
  $handler->display->display_options['fields']['name']['table'] = 'log';
  $handler->display->display_options['fields']['name']['field'] = 'name';
  $handler->display->display_options['fields']['name']['label'] = 'Purchase name';
  $handler->display->display_options['fields']['name']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['name']['alter']['path'] = 'log/[id]';
  /* Field: Log: Value */
  $handler->display->display_options['fields']['field_farm_value']['id'] = 'field_farm_value';
  $handler->display->display_options['fields']['field_farm_value']['table'] = 'field_data_field_farm_value';
  $handler->display->display_options['fields']['field_farm_value']['field'] = 'field_farm_value';
  $handler->display->display_options['fields']['field_farm_value']['type'] = 'fraction_decimal';
  $handler->display->display_options['fields']['field_farm_value']['settings'] = array(
    'precision' => '2',
    'auto_precision' => 1,
  );
  /* Field: Log: Quantity */
  $handler->display->display_options['fields']['field_farm_quantity_value']['id'] = 'field_farm_quantity_value';
  $handler->display->display_options['fields']['field_farm_quantity_value']['table'] = 'field_data_field_farm_quantity_value';
  $handler->display->display_options['fields']['field_farm_quantity_value']['field'] = 'field_farm_quantity_value';
  $handler->display->display_options['fields']['field_farm_quantity_value']['type'] = 'fraction_decimal';
  $handler->display->display_options['fields']['field_farm_quantity_value']['settings'] = array(
    'precision' => '3',
    'auto_precision' => 1,
  );
  /* Field: Log: Units */
  $handler->display->display_options['fields']['field_farm_quantity_units']['id'] = 'field_farm_quantity_units';
  $handler->display->display_options['fields']['field_farm_quantity_units']['table'] = 'field_data_field_farm_quantity_units';
  $handler->display->display_options['fields']['field_farm_quantity_units']['field'] = 'field_farm_quantity_units';
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
  /* Field: Log: Related Issues */
  $handler->display->display_options['fields']['field_farm_issues']['id'] = 'field_farm_issues';
  $handler->display->display_options['fields']['field_farm_issues']['table'] = 'field_data_field_farm_issues';
  $handler->display->display_options['fields']['field_farm_issues']['field'] = 'field_farm_issues';
  $handler->display->display_options['fields']['field_farm_issues']['settings'] = array(
    'link' => 1,
  );
  $handler->display->display_options['fields']['field_farm_issues']['delta_offset'] = '0';
  /* Field: Log: Related Plantings */
  $handler->display->display_options['fields']['field_farm_plantings']['id'] = 'field_farm_plantings';
  $handler->display->display_options['fields']['field_farm_plantings']['table'] = 'field_data_field_farm_plantings';
  $handler->display->display_options['fields']['field_farm_plantings']['field'] = 'field_farm_plantings';
  $handler->display->display_options['fields']['field_farm_plantings']['settings'] = array(
    'link' => 1,
  );
  $handler->display->display_options['fields']['field_farm_plantings']['delta_offset'] = '0';
  /* Field: Log: Related Purchases */
  $handler->display->display_options['fields']['field_farm_purchases']['id'] = 'field_farm_purchases';
  $handler->display->display_options['fields']['field_farm_purchases']['table'] = 'field_data_field_farm_purchases';
  $handler->display->display_options['fields']['field_farm_purchases']['field'] = 'field_farm_purchases';
  $handler->display->display_options['fields']['field_farm_purchases']['settings'] = array(
    'link' => 1,
  );
  $handler->display->display_options['fields']['field_farm_purchases']['delta_offset'] = '0';
  /* Field: Global: Custom text */
  $handler->display->display_options['fields']['nothing']['id'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['table'] = 'views';
  $handler->display->display_options['fields']['nothing']['field'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['label'] = 'Operations';
  $handler->display->display_options['fields']['nothing']['alter']['text'] = '<a href="/log/[id]/edit">Edit</a> | <a href="/log/[id]/delete">Delete</a>';
  $handler->display->display_options['defaults']['filter_groups'] = FALSE;
  $handler->display->display_options['defaults']['filters'] = FALSE;
  /* Filter criterion: Log: Type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'log';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'farm_purchase' => 'farm_purchase',
  );
  $handler->display->display_options['filters']['type']['group'] = 1;
  /* Filter criterion: Log: Date (field_farm_date) */
  $handler->display->display_options['filters']['field_farm_date_value']['id'] = 'field_farm_date_value';
  $handler->display->display_options['filters']['field_farm_date_value']['table'] = 'field_data_field_farm_date';
  $handler->display->display_options['filters']['field_farm_date_value']['field'] = 'field_farm_date_value';
  $handler->display->display_options['filters']['field_farm_date_value']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_date_value']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_date_value']['expose']['operator_id'] = 'field_farm_date_value_op';
  $handler->display->display_options['filters']['field_farm_date_value']['expose']['label'] = 'Date';
  $handler->display->display_options['filters']['field_farm_date_value']['expose']['use_operator'] = TRUE;
  $handler->display->display_options['filters']['field_farm_date_value']['expose']['operator'] = 'field_farm_date_value_op';
  $handler->display->display_options['filters']['field_farm_date_value']['expose']['identifier'] = 'field_farm_date_value';
  $handler->display->display_options['filters']['field_farm_date_value']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
  );
  $handler->display->display_options['filters']['field_farm_date_value']['form_type'] = 'date_popup';
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
  $handler->display->display_options['path'] = 'logs/money/purchases';
  $handler->display->display_options['menu']['type'] = 'tab';
  $handler->display->display_options['menu']['title'] = 'Purchases';
  $handler->display->display_options['menu']['weight'] = '10';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;
  $handler->display->display_options['tab_options']['type'] = 'normal';
  $handler->display->display_options['tab_options']['title'] = 'Logs';
  $handler->display->display_options['tab_options']['weight'] = '0';
  $handler->display->display_options['tab_options']['name'] = 'management';

  /* Display: Entity Reference */
  $handler = $view->new_display('entityreference', 'Entity Reference', 'entityreference_1');
  $handler->display->display_options['defaults']['title'] = FALSE;
  $handler->display->display_options['pager']['type'] = 'some';
  $handler->display->display_options['pager']['options']['items_per_page'] = '25';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['defaults']['style_plugin'] = FALSE;
  $handler->display->display_options['style_plugin'] = 'entityreference_style';
  $handler->display->display_options['style_options']['search_fields'] = array(
    'name' => 'name',
  );
  $handler->display->display_options['defaults']['style_options'] = FALSE;
  $handler->display->display_options['defaults']['row_plugin'] = FALSE;
  $handler->display->display_options['row_plugin'] = 'entityreference_fields';
  $handler->display->display_options['defaults']['row_options'] = FALSE;
  $export['farm_log_purchase'] = $view;

  $view = new view();
  $view->name = 'farm_log_sale';
  $view->description = '';
  $view->tag = 'farm_log_money';
  $view->base_table = 'log';
  $view->human_name = 'Farm Sale Log';
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
    'id' => 'id',
    'timestamp' => 'timestamp',
    'type' => 'type',
    'name' => 'name',
    'nothing' => 'nothing',
  );
  $handler->display->display_options['style_options']['default'] = '-1';
  $handler->display->display_options['style_options']['info'] = array(
    'id' => array(
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
  /* Field: Log: Name */
  $handler->display->display_options['fields']['name']['id'] = 'name';
  $handler->display->display_options['fields']['name']['table'] = 'log';
  $handler->display->display_options['fields']['name']['field'] = 'name';
  $handler->display->display_options['fields']['name']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['name']['alter']['path'] = 'log/[id]';
  /* Sort criterion: Log: Date (field_farm_date) */
  $handler->display->display_options['sorts']['field_farm_date_value']['id'] = 'field_farm_date_value';
  $handler->display->display_options['sorts']['field_farm_date_value']['table'] = 'field_data_field_farm_date';
  $handler->display->display_options['sorts']['field_farm_date_value']['field'] = 'field_farm_date_value';
  $handler->display->display_options['sorts']['field_farm_date_value']['order'] = 'DESC';
  /* Filter criterion: Log: Type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'log';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'farm_sale' => 'farm_sale',
  );

  /* Display: Sale log (page) */
  $handler = $view->new_display('page', 'Sale log (page)', 'page');
  $handler->display->display_options['defaults']['title'] = FALSE;
  $handler->display->display_options['title'] = 'Sale Log';
  $handler->display->display_options['defaults']['style_plugin'] = FALSE;
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['columns'] = array(
    'id' => 'id',
    'field_farm_date' => 'field_farm_date',
    'name' => 'name',
    'field_farm_value' => 'field_farm_value',
    'field_farm_quantity_value' => 'field_farm_quantity_value',
    'field_farm_quantity_units' => 'field_farm_quantity_units',
    'field_farm_crops' => 'field_farm_crops',
    'field_farm_areas' => 'field_farm_areas',
    'field_farm_issues' => 'field_farm_issues',
    'field_farm_plantings' => 'field_farm_plantings',
    'field_farm_sales' => 'field_farm_sales',
    'nothing' => 'nothing',
  );
  $handler->display->display_options['style_options']['default'] = '-1';
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
    'name' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_value' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_quantity_value' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_quantity_units' => array(
      'sortable' => 0,
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
    'field_farm_plantings' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_sales' => array(
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
  $handler->display->display_options['defaults']['header'] = FALSE;
  /* Header: Global: Unfiltered text */
  $handler->display->display_options['header']['area_text_custom']['id'] = 'area_text_custom';
  $handler->display->display_options['header']['area_text_custom']['table'] = 'views';
  $handler->display->display_options['header']['area_text_custom']['field'] = 'area_text_custom';
  $handler->display->display_options['header']['area_text_custom']['empty'] = TRUE;
  $handler->display->display_options['header']['area_text_custom']['content'] = '<a href="/log/add/farm_sale">+ Log a sale</a>';
  $handler->display->display_options['defaults']['fields'] = FALSE;
  /* Field: Log: Log ID */
  $handler->display->display_options['fields']['id']['id'] = 'id';
  $handler->display->display_options['fields']['id']['table'] = 'log';
  $handler->display->display_options['fields']['id']['field'] = 'id';
  $handler->display->display_options['fields']['id']['exclude'] = TRUE;
  /* Field: Log: Date */
  $handler->display->display_options['fields']['field_farm_date']['id'] = 'field_farm_date';
  $handler->display->display_options['fields']['field_farm_date']['table'] = 'field_data_field_farm_date';
  $handler->display->display_options['fields']['field_farm_date']['field'] = 'field_farm_date';
  $handler->display->display_options['fields']['field_farm_date']['settings'] = array(
    'format_type' => 'short',
    'fromto' => 'both',
    'multiple_number' => '',
    'multiple_from' => '',
    'multiple_to' => '',
  );
  /* Field: Log: Name */
  $handler->display->display_options['fields']['name']['id'] = 'name';
  $handler->display->display_options['fields']['name']['table'] = 'log';
  $handler->display->display_options['fields']['name']['field'] = 'name';
  $handler->display->display_options['fields']['name']['label'] = 'Sale name';
  $handler->display->display_options['fields']['name']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['name']['alter']['path'] = 'log/[id]';
  /* Field: Log: Value */
  $handler->display->display_options['fields']['field_farm_value']['id'] = 'field_farm_value';
  $handler->display->display_options['fields']['field_farm_value']['table'] = 'field_data_field_farm_value';
  $handler->display->display_options['fields']['field_farm_value']['field'] = 'field_farm_value';
  $handler->display->display_options['fields']['field_farm_value']['type'] = 'fraction_decimal';
  $handler->display->display_options['fields']['field_farm_value']['settings'] = array(
    'precision' => '2',
    'auto_precision' => 1,
  );
  /* Field: Log: Quantity */
  $handler->display->display_options['fields']['field_farm_quantity_value']['id'] = 'field_farm_quantity_value';
  $handler->display->display_options['fields']['field_farm_quantity_value']['table'] = 'field_data_field_farm_quantity_value';
  $handler->display->display_options['fields']['field_farm_quantity_value']['field'] = 'field_farm_quantity_value';
  $handler->display->display_options['fields']['field_farm_quantity_value']['type'] = 'fraction_decimal';
  $handler->display->display_options['fields']['field_farm_quantity_value']['settings'] = array(
    'precision' => '3',
    'auto_precision' => 1,
  );
  /* Field: Log: Units */
  $handler->display->display_options['fields']['field_farm_quantity_units']['id'] = 'field_farm_quantity_units';
  $handler->display->display_options['fields']['field_farm_quantity_units']['table'] = 'field_data_field_farm_quantity_units';
  $handler->display->display_options['fields']['field_farm_quantity_units']['field'] = 'field_farm_quantity_units';
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
  /* Field: Log: Related Issues */
  $handler->display->display_options['fields']['field_farm_issues']['id'] = 'field_farm_issues';
  $handler->display->display_options['fields']['field_farm_issues']['table'] = 'field_data_field_farm_issues';
  $handler->display->display_options['fields']['field_farm_issues']['field'] = 'field_farm_issues';
  $handler->display->display_options['fields']['field_farm_issues']['settings'] = array(
    'link' => 1,
  );
  $handler->display->display_options['fields']['field_farm_issues']['delta_offset'] = '0';
  /* Field: Log: Related Plantings */
  $handler->display->display_options['fields']['field_farm_plantings']['id'] = 'field_farm_plantings';
  $handler->display->display_options['fields']['field_farm_plantings']['table'] = 'field_data_field_farm_plantings';
  $handler->display->display_options['fields']['field_farm_plantings']['field'] = 'field_farm_plantings';
  $handler->display->display_options['fields']['field_farm_plantings']['settings'] = array(
    'link' => 1,
  );
  $handler->display->display_options['fields']['field_farm_plantings']['delta_offset'] = '0';
  /* Field: Log: Related Sales */
  $handler->display->display_options['fields']['field_farm_sales']['id'] = 'field_farm_sales';
  $handler->display->display_options['fields']['field_farm_sales']['table'] = 'field_data_field_farm_sales';
  $handler->display->display_options['fields']['field_farm_sales']['field'] = 'field_farm_sales';
  $handler->display->display_options['fields']['field_farm_sales']['settings'] = array(
    'link' => 1,
  );
  $handler->display->display_options['fields']['field_farm_sales']['delta_offset'] = '0';
  /* Field: Global: Custom text */
  $handler->display->display_options['fields']['nothing']['id'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['table'] = 'views';
  $handler->display->display_options['fields']['nothing']['field'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['label'] = 'Operations';
  $handler->display->display_options['fields']['nothing']['alter']['text'] = '<a href="/log/[id]/edit">Edit</a> | <a href="/log/[id]/delete">Delete</a>';
  $handler->display->display_options['defaults']['filter_groups'] = FALSE;
  $handler->display->display_options['defaults']['filters'] = FALSE;
  /* Filter criterion: Log: Type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'log';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'farm_sale' => 'farm_sale',
  );
  $handler->display->display_options['filters']['type']['group'] = 1;
  /* Filter criterion: Log: Date (field_farm_date) */
  $handler->display->display_options['filters']['field_farm_date_value']['id'] = 'field_farm_date_value';
  $handler->display->display_options['filters']['field_farm_date_value']['table'] = 'field_data_field_farm_date';
  $handler->display->display_options['filters']['field_farm_date_value']['field'] = 'field_farm_date_value';
  $handler->display->display_options['filters']['field_farm_date_value']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_date_value']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_date_value']['expose']['operator_id'] = 'field_farm_date_value_op';
  $handler->display->display_options['filters']['field_farm_date_value']['expose']['label'] = 'Date';
  $handler->display->display_options['filters']['field_farm_date_value']['expose']['use_operator'] = TRUE;
  $handler->display->display_options['filters']['field_farm_date_value']['expose']['operator'] = 'field_farm_date_value_op';
  $handler->display->display_options['filters']['field_farm_date_value']['expose']['identifier'] = 'field_farm_date_value';
  $handler->display->display_options['filters']['field_farm_date_value']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
  );
  $handler->display->display_options['filters']['field_farm_date_value']['form_type'] = 'date_popup';
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
  $handler->display->display_options['path'] = 'logs/money/sales';
  $handler->display->display_options['menu']['type'] = 'default tab';
  $handler->display->display_options['menu']['title'] = 'Sales';
  $handler->display->display_options['menu']['weight'] = '';
  $handler->display->display_options['menu']['name'] = 'management';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;
  $handler->display->display_options['tab_options']['type'] = 'tab';
  $handler->display->display_options['tab_options']['title'] = 'Money';
  $handler->display->display_options['tab_options']['weight'] = '0';

  /* Display: Entity Reference */
  $handler = $view->new_display('entityreference', 'Entity Reference', 'entityreference_1');
  $handler->display->display_options['defaults']['title'] = FALSE;
  $handler->display->display_options['pager']['type'] = 'some';
  $handler->display->display_options['pager']['options']['items_per_page'] = '25';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['defaults']['style_plugin'] = FALSE;
  $handler->display->display_options['style_plugin'] = 'entityreference_style';
  $handler->display->display_options['style_options']['search_fields'] = array(
    'name' => 'name',
  );
  $handler->display->display_options['defaults']['style_options'] = FALSE;
  $handler->display->display_options['defaults']['row_plugin'] = FALSE;
  $handler->display->display_options['row_plugin'] = 'entityreference_fields';
  $handler->display->display_options['defaults']['row_options'] = FALSE;
  $export['farm_log_sale'] = $view;

  return $export;
}
