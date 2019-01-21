<?php
/**
 * @file
 * farm_quantity_report.views_default.inc
 */

/**
 * Implements hook_views_default_views().
 */
function farm_quantity_report_views_default_views() {
  $export = array();

  $view = new view();
  $view->name = 'farm_quantity_report';
  $view->description = '';
  $view->tag = 'default';
  $view->base_table = 'field_collection_item';
  $view->human_name = 'Farm Quantity Report';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Quantity Report';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['access']['perm'] = 'view all logs';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'input_required';
  $handler->display->display_options['exposed_form']['options']['reset_button'] = TRUE;
  $handler->display->display_options['exposed_form']['options']['text_input_required'] = 'Use this form to generate a report of quantity measurement logs. Reports can be exported to a file by clicking the CSV button at the bottom. Select any filter and click on Apply to see results.';
  $handler->display->display_options['exposed_form']['options']['text_input_required_format'] = 'farm_format';
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['pager']['options']['items_per_page'] = '100';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['pager']['options']['id'] = '0';
  $handler->display->display_options['pager']['options']['quantity'] = '9';
  $handler->display->display_options['pager']['options']['expose']['items_per_page'] = TRUE;
  $handler->display->display_options['pager']['options']['expose']['items_per_page_options'] = '25, 50, 100, 250, 500';
  $handler->display->display_options['pager']['options']['expose']['items_per_page_options_all'] = TRUE;
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['columns'] = array(
    'item_id' => 'item_id',
    'done' => 'done',
    'id' => 'id',
    'timestamp' => 'timestamp',
    'type' => 'type',
    'name' => 'name',
    'field_farm_asset' => 'field_farm_asset',
    'field_farm_area' => 'field_farm_area',
    'field_farm_flags' => 'field_farm_flags',
    'field_farm_log_category' => 'field_farm_log_category',
    'field_farm_quantity_measure' => 'field_farm_quantity_measure',
    'field_farm_quantity_value' => 'field_farm_quantity_value',
    'field_farm_quantity_units' => 'field_farm_quantity_units',
    'field_farm_quantity_label' => 'field_farm_quantity_label',
  );
  $handler->display->display_options['style_options']['default'] = '-1';
  $handler->display->display_options['style_options']['info'] = array(
    'item_id' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'done' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'id' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'timestamp' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'type' => array(
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
    'field_farm_asset' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_area' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_flags' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_log_category' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_quantity_measure' => array(
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
    'field_farm_quantity_label' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
  );
  /* No results behavior: Global: Unfiltered text */
  $handler->display->display_options['empty']['area_text_custom']['id'] = 'area_text_custom';
  $handler->display->display_options['empty']['area_text_custom']['table'] = 'views';
  $handler->display->display_options['empty']['area_text_custom']['field'] = 'area_text_custom';
  $handler->display->display_options['empty']['area_text_custom']['empty'] = TRUE;
  $handler->display->display_options['empty']['area_text_custom']['content'] = 'No quantity measurements found.';
  /* Relationship: Field collection item: Entity with the Quantity (field_farm_quantity) */
  $handler->display->display_options['relationships']['field_farm_quantity_log']['id'] = 'field_farm_quantity_log';
  $handler->display->display_options['relationships']['field_farm_quantity_log']['table'] = 'field_collection_item';
  $handler->display->display_options['relationships']['field_farm_quantity_log']['field'] = 'field_farm_quantity_log';
  $handler->display->display_options['relationships']['field_farm_quantity_log']['label'] = 'log';
  $handler->display->display_options['relationships']['field_farm_quantity_log']['required'] = TRUE;
  /* Relationship: Entity Reference: Referenced Entity */
  $handler->display->display_options['relationships']['field_farm_asset_target_id']['id'] = 'field_farm_asset_target_id';
  $handler->display->display_options['relationships']['field_farm_asset_target_id']['table'] = 'field_data_field_farm_asset';
  $handler->display->display_options['relationships']['field_farm_asset_target_id']['field'] = 'field_farm_asset_target_id';
  $handler->display->display_options['relationships']['field_farm_asset_target_id']['relationship'] = 'field_farm_quantity_log';
  /* Field: Field collection item: Field collection item ID */
  $handler->display->display_options['fields']['item_id']['id'] = 'item_id';
  $handler->display->display_options['fields']['item_id']['table'] = 'field_collection_item';
  $handler->display->display_options['fields']['item_id']['field'] = 'item_id';
  $handler->display->display_options['fields']['item_id']['exclude'] = TRUE;
  /* Field: Log: Done */
  $handler->display->display_options['fields']['done']['id'] = 'done';
  $handler->display->display_options['fields']['done']['table'] = 'log';
  $handler->display->display_options['fields']['done']['field'] = 'done';
  $handler->display->display_options['fields']['done']['relationship'] = 'field_farm_quantity_log';
  $handler->display->display_options['fields']['done']['type'] = 'custom';
  $handler->display->display_options['fields']['done']['type_custom_true'] = '✔';
  $handler->display->display_options['fields']['done']['not'] = 0;
  /* Field: Log: Log ID */
  $handler->display->display_options['fields']['id']['id'] = 'id';
  $handler->display->display_options['fields']['id']['table'] = 'log';
  $handler->display->display_options['fields']['id']['field'] = 'id';
  $handler->display->display_options['fields']['id']['relationship'] = 'field_farm_quantity_log';
  $handler->display->display_options['fields']['id']['separator'] = '';
  /* Field: Log: Timestamp */
  $handler->display->display_options['fields']['timestamp']['id'] = 'timestamp';
  $handler->display->display_options['fields']['timestamp']['table'] = 'log';
  $handler->display->display_options['fields']['timestamp']['field'] = 'timestamp';
  $handler->display->display_options['fields']['timestamp']['relationship'] = 'field_farm_quantity_log';
  $handler->display->display_options['fields']['timestamp']['label'] = 'Date';
  $handler->display->display_options['fields']['timestamp']['date_format'] = 'custom';
  $handler->display->display_options['fields']['timestamp']['custom_date_format'] = 'M j Y';
  $handler->display->display_options['fields']['timestamp']['second_date_format'] = 'long';
  /* Field: Log: Log type */
  $handler->display->display_options['fields']['type']['id'] = 'type';
  $handler->display->display_options['fields']['type']['table'] = 'log';
  $handler->display->display_options['fields']['type']['field'] = 'type';
  $handler->display->display_options['fields']['type']['relationship'] = 'field_farm_quantity_log';
  /* Field: Log: Name */
  $handler->display->display_options['fields']['name']['id'] = 'name';
  $handler->display->display_options['fields']['name']['table'] = 'log';
  $handler->display->display_options['fields']['name']['field'] = 'name';
  $handler->display->display_options['fields']['name']['relationship'] = 'field_farm_quantity_log';
  $handler->display->display_options['fields']['name']['label'] = 'Log name';
  $handler->display->display_options['fields']['name']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['name']['alter']['path'] = 'log/[id]';
  /* Field: Log: Assets */
  $handler->display->display_options['fields']['field_farm_asset']['id'] = 'field_farm_asset';
  $handler->display->display_options['fields']['field_farm_asset']['table'] = 'field_data_field_farm_asset';
  $handler->display->display_options['fields']['field_farm_asset']['field'] = 'field_farm_asset';
  $handler->display->display_options['fields']['field_farm_asset']['relationship'] = 'field_farm_quantity_log';
  $handler->display->display_options['fields']['field_farm_asset']['settings'] = array(
    'bypass_access' => 0,
    'link' => 1,
  );
  $handler->display->display_options['fields']['field_farm_asset']['delta_offset'] = '0';
  /* Field: Log: Areas */
  $handler->display->display_options['fields']['field_farm_area']['id'] = 'field_farm_area';
  $handler->display->display_options['fields']['field_farm_area']['table'] = 'field_data_field_farm_area';
  $handler->display->display_options['fields']['field_farm_area']['field'] = 'field_farm_area';
  $handler->display->display_options['fields']['field_farm_area']['relationship'] = 'field_farm_quantity_log';
  $handler->display->display_options['fields']['field_farm_area']['delta_offset'] = '0';
  /* Field: Field: Flags */
  $handler->display->display_options['fields']['field_farm_flags']['id'] = 'field_farm_flags';
  $handler->display->display_options['fields']['field_farm_flags']['table'] = 'field_data_field_farm_flags';
  $handler->display->display_options['fields']['field_farm_flags']['field'] = 'field_farm_flags';
  $handler->display->display_options['fields']['field_farm_flags']['relationship'] = 'field_farm_quantity_log';
  $handler->display->display_options['fields']['field_farm_flags']['delta_offset'] = '0';
  /* Field: Log: Log category */
  $handler->display->display_options['fields']['field_farm_log_category']['id'] = 'field_farm_log_category';
  $handler->display->display_options['fields']['field_farm_log_category']['table'] = 'field_data_field_farm_log_category';
  $handler->display->display_options['fields']['field_farm_log_category']['field'] = 'field_farm_log_category';
  $handler->display->display_options['fields']['field_farm_log_category']['relationship'] = 'field_farm_quantity_log';
  $handler->display->display_options['fields']['field_farm_log_category']['type'] = 'taxonomy_term_reference_plain';
  $handler->display->display_options['fields']['field_farm_log_category']['delta_offset'] = '0';
  /* Field: Field collection item: Measure */
  $handler->display->display_options['fields']['field_farm_quantity_measure']['id'] = 'field_farm_quantity_measure';
  $handler->display->display_options['fields']['field_farm_quantity_measure']['table'] = 'field_data_field_farm_quantity_measure';
  $handler->display->display_options['fields']['field_farm_quantity_measure']['field'] = 'field_farm_quantity_measure';
  /* Field: Field: Value */
  $handler->display->display_options['fields']['field_farm_quantity_value']['id'] = 'field_farm_quantity_value';
  $handler->display->display_options['fields']['field_farm_quantity_value']['table'] = 'field_data_field_farm_quantity_value';
  $handler->display->display_options['fields']['field_farm_quantity_value']['field'] = 'field_farm_quantity_value';
  $handler->display->display_options['fields']['field_farm_quantity_value']['type'] = 'fraction_decimal';
  $handler->display->display_options['fields']['field_farm_quantity_value']['settings'] = array(
    'precision' => '0',
    'auto_precision' => 1,
    'prefix_suffix' => 1,
  );
  /* Field: Field: Units */
  $handler->display->display_options['fields']['field_farm_quantity_units']['id'] = 'field_farm_quantity_units';
  $handler->display->display_options['fields']['field_farm_quantity_units']['table'] = 'field_data_field_farm_quantity_units';
  $handler->display->display_options['fields']['field_farm_quantity_units']['field'] = 'field_farm_quantity_units';
  $handler->display->display_options['fields']['field_farm_quantity_units']['type'] = 'taxonomy_term_reference_plain';
  /* Field: Field collection item: Label */
  $handler->display->display_options['fields']['field_farm_quantity_label']['id'] = 'field_farm_quantity_label';
  $handler->display->display_options['fields']['field_farm_quantity_label']['table'] = 'field_data_field_farm_quantity_label';
  $handler->display->display_options['fields']['field_farm_quantity_label']['field'] = 'field_farm_quantity_label';
  /* Sort criterion: Log: Timestamp */
  $handler->display->display_options['sorts']['timestamp']['id'] = 'timestamp';
  $handler->display->display_options['sorts']['timestamp']['table'] = 'log';
  $handler->display->display_options['sorts']['timestamp']['field'] = 'timestamp';
  $handler->display->display_options['sorts']['timestamp']['relationship'] = 'field_farm_quantity_log';
  $handler->display->display_options['sorts']['timestamp']['order'] = 'DESC';
  $handler->display->display_options['sorts']['timestamp']['exposed'] = TRUE;
  $handler->display->display_options['sorts']['timestamp']['expose']['label'] = 'Date';
  /* Sort criterion: Field collection item: Measure (field_farm_quantity_measure) */
  $handler->display->display_options['sorts']['field_farm_quantity_measure_value']['id'] = 'field_farm_quantity_measure_value';
  $handler->display->display_options['sorts']['field_farm_quantity_measure_value']['table'] = 'field_data_field_farm_quantity_measure';
  $handler->display->display_options['sorts']['field_farm_quantity_measure_value']['field'] = 'field_farm_quantity_measure_value';
  $handler->display->display_options['sorts']['field_farm_quantity_measure_value']['exposed'] = TRUE;
  $handler->display->display_options['sorts']['field_farm_quantity_measure_value']['expose']['label'] = 'Measure';
  /* Sort criterion: Field: Value (decimal) */
  $handler->display->display_options['sorts']['field_farm_quantity_value_decimal']['id'] = 'field_farm_quantity_value_decimal';
  $handler->display->display_options['sorts']['field_farm_quantity_value_decimal']['table'] = 'field_data_field_farm_quantity_value';
  $handler->display->display_options['sorts']['field_farm_quantity_value_decimal']['field'] = 'field_farm_quantity_value_decimal';
  $handler->display->display_options['sorts']['field_farm_quantity_value_decimal']['exposed'] = TRUE;
  $handler->display->display_options['sorts']['field_farm_quantity_value_decimal']['expose']['label'] = 'Quantity value';
  /* Sort criterion: Field: Units (field_farm_quantity_units) */
  $handler->display->display_options['sorts']['field_farm_quantity_units_tid']['id'] = 'field_farm_quantity_units_tid';
  $handler->display->display_options['sorts']['field_farm_quantity_units_tid']['table'] = 'field_data_field_farm_quantity_units';
  $handler->display->display_options['sorts']['field_farm_quantity_units_tid']['field'] = 'field_farm_quantity_units_tid';
  $handler->display->display_options['sorts']['field_farm_quantity_units_tid']['exposed'] = TRUE;
  $handler->display->display_options['sorts']['field_farm_quantity_units_tid']['expose']['label'] = 'Units';
  /* Sort criterion: Field collection item: Label (field_farm_quantity_label) */
  $handler->display->display_options['sorts']['field_farm_quantity_label_value']['id'] = 'field_farm_quantity_label_value';
  $handler->display->display_options['sorts']['field_farm_quantity_label_value']['table'] = 'field_data_field_farm_quantity_label';
  $handler->display->display_options['sorts']['field_farm_quantity_label_value']['field'] = 'field_farm_quantity_label_value';
  $handler->display->display_options['sorts']['field_farm_quantity_label_value']['exposed'] = TRUE;
  $handler->display->display_options['sorts']['field_farm_quantity_label_value']['expose']['label'] = 'Label';
  /* Sort criterion: Log: Log ID */
  $handler->display->display_options['sorts']['id']['id'] = 'id';
  $handler->display->display_options['sorts']['id']['table'] = 'log';
  $handler->display->display_options['sorts']['id']['field'] = 'id';
  $handler->display->display_options['sorts']['id']['relationship'] = 'field_farm_quantity_log';
  $handler->display->display_options['sorts']['id']['order'] = 'DESC';
  $handler->display->display_options['sorts']['id']['exposed'] = TRUE;
  $handler->display->display_options['sorts']['id']['expose']['label'] = 'Log ID';
  /* Filter criterion: Field collection item: Field name */
  $handler->display->display_options['filters']['field_name']['id'] = 'field_name';
  $handler->display->display_options['filters']['field_name']['table'] = 'field_collection_item';
  $handler->display->display_options['filters']['field_name']['field'] = 'field_name';
  $handler->display->display_options['filters']['field_name']['value'] = array(
    'field_farm_quantity' => 'field_farm_quantity',
  );
  $handler->display->display_options['filters']['field_name']['group'] = 1;
  /* Filter criterion: Farm asset: Name */
  $handler->display->display_options['filters']['name_1']['id'] = 'name_1';
  $handler->display->display_options['filters']['name_1']['table'] = 'farm_asset';
  $handler->display->display_options['filters']['name_1']['field'] = 'name';
  $handler->display->display_options['filters']['name_1']['relationship'] = 'field_farm_asset_target_id';
  $handler->display->display_options['filters']['name_1']['operator'] = 'contains';
  $handler->display->display_options['filters']['name_1']['group'] = 1;
  $handler->display->display_options['filters']['name_1']['exposed'] = TRUE;
  $handler->display->display_options['filters']['name_1']['expose']['operator_id'] = 'name_1_op';
  $handler->display->display_options['filters']['name_1']['expose']['label'] = 'Asset name';
  $handler->display->display_options['filters']['name_1']['expose']['operator'] = 'name_1_op';
  $handler->display->display_options['filters']['name_1']['expose']['identifier'] = 'name_1';
  /* Filter criterion: Farm asset: Farm asset type */
  $handler->display->display_options['filters']['type_1']['id'] = 'type_1';
  $handler->display->display_options['filters']['type_1']['table'] = 'farm_asset';
  $handler->display->display_options['filters']['type_1']['field'] = 'type';
  $handler->display->display_options['filters']['type_1']['relationship'] = 'field_farm_asset_target_id';
  $handler->display->display_options['filters']['type_1']['group'] = 1;
  $handler->display->display_options['filters']['type_1']['exposed'] = TRUE;
  $handler->display->display_options['filters']['type_1']['expose']['operator_id'] = 'type_1_op';
  $handler->display->display_options['filters']['type_1']['expose']['label'] = 'Asset type';
  $handler->display->display_options['filters']['type_1']['expose']['operator'] = 'type_1_op';
  $handler->display->display_options['filters']['type_1']['expose']['identifier'] = 'type_1';
  /* Filter criterion: Log: Name */
  $handler->display->display_options['filters']['name']['id'] = 'name';
  $handler->display->display_options['filters']['name']['table'] = 'log';
  $handler->display->display_options['filters']['name']['field'] = 'name';
  $handler->display->display_options['filters']['name']['relationship'] = 'field_farm_quantity_log';
  $handler->display->display_options['filters']['name']['operator'] = 'contains';
  $handler->display->display_options['filters']['name']['group'] = 1;
  $handler->display->display_options['filters']['name']['exposed'] = TRUE;
  $handler->display->display_options['filters']['name']['expose']['operator_id'] = 'name_op';
  $handler->display->display_options['filters']['name']['expose']['label'] = 'Log name';
  $handler->display->display_options['filters']['name']['expose']['operator'] = 'name_op';
  $handler->display->display_options['filters']['name']['expose']['identifier'] = 'name';
  /* Filter criterion: Log: Log type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'log';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['relationship'] = 'field_farm_quantity_log';
  $handler->display->display_options['filters']['type']['group'] = 1;
  $handler->display->display_options['filters']['type']['exposed'] = TRUE;
  $handler->display->display_options['filters']['type']['expose']['operator_id'] = 'type_op';
  $handler->display->display_options['filters']['type']['expose']['label'] = 'Log type';
  $handler->display->display_options['filters']['type']['expose']['operator'] = 'type_op';
  $handler->display->display_options['filters']['type']['expose']['identifier'] = 'type';
  /* Filter criterion: Field: Flags (field_farm_flags) */
  $handler->display->display_options['filters']['field_farm_flags_value']['id'] = 'field_farm_flags_value';
  $handler->display->display_options['filters']['field_farm_flags_value']['table'] = 'field_data_field_farm_flags';
  $handler->display->display_options['filters']['field_farm_flags_value']['field'] = 'field_farm_flags_value';
  $handler->display->display_options['filters']['field_farm_flags_value']['relationship'] = 'field_farm_quantity_log';
  $handler->display->display_options['filters']['field_farm_flags_value']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_flags_value']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_flags_value']['expose']['operator_id'] = 'field_farm_flags_value_op';
  $handler->display->display_options['filters']['field_farm_flags_value']['expose']['label'] = 'Flags';
  $handler->display->display_options['filters']['field_farm_flags_value']['expose']['operator'] = 'field_farm_flags_value_op';
  $handler->display->display_options['filters']['field_farm_flags_value']['expose']['identifier'] = 'field_farm_flags_value';
  $handler->display->display_options['filters']['field_farm_flags_value']['expose']['multiple'] = TRUE;
  /* Filter criterion: Log: Log category (field_farm_log_category) */
  $handler->display->display_options['filters']['field_farm_log_category_tid']['id'] = 'field_farm_log_category_tid';
  $handler->display->display_options['filters']['field_farm_log_category_tid']['table'] = 'field_data_field_farm_log_category';
  $handler->display->display_options['filters']['field_farm_log_category_tid']['field'] = 'field_farm_log_category_tid';
  $handler->display->display_options['filters']['field_farm_log_category_tid']['relationship'] = 'field_farm_quantity_log';
  $handler->display->display_options['filters']['field_farm_log_category_tid']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_log_category_tid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_log_category_tid']['expose']['operator_id'] = 'field_farm_log_category_tid_op';
  $handler->display->display_options['filters']['field_farm_log_category_tid']['expose']['label'] = 'Log category';
  $handler->display->display_options['filters']['field_farm_log_category_tid']['expose']['operator'] = 'field_farm_log_category_tid_op';
  $handler->display->display_options['filters']['field_farm_log_category_tid']['expose']['identifier'] = 'field_farm_log_category_tid';
  $handler->display->display_options['filters']['field_farm_log_category_tid']['type'] = 'select';
  $handler->display->display_options['filters']['field_farm_log_category_tid']['vocabulary'] = 'farm_log_categories';
  $handler->display->display_options['filters']['field_farm_log_category_tid']['hierarchy'] = 1;
  /* Filter criterion: Log: Areas (field_farm_area) */
  $handler->display->display_options['filters']['field_farm_area_tid']['id'] = 'field_farm_area_tid';
  $handler->display->display_options['filters']['field_farm_area_tid']['table'] = 'field_data_field_farm_area';
  $handler->display->display_options['filters']['field_farm_area_tid']['field'] = 'field_farm_area_tid';
  $handler->display->display_options['filters']['field_farm_area_tid']['relationship'] = 'field_farm_quantity_log';
  $handler->display->display_options['filters']['field_farm_area_tid']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_area_tid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_area_tid']['expose']['operator_id'] = 'field_farm_area_tid_op';
  $handler->display->display_options['filters']['field_farm_area_tid']['expose']['label'] = 'Areas';
  $handler->display->display_options['filters']['field_farm_area_tid']['expose']['operator'] = 'field_farm_area_tid_op';
  $handler->display->display_options['filters']['field_farm_area_tid']['expose']['identifier'] = 'field_farm_area_tid';
  $handler->display->display_options['filters']['field_farm_area_tid']['expose']['multiple'] = TRUE;
  $handler->display->display_options['filters']['field_farm_area_tid']['type'] = 'select';
  $handler->display->display_options['filters']['field_farm_area_tid']['vocabulary'] = 'farm_areas';
  $handler->display->display_options['filters']['field_farm_area_tid']['hierarchy'] = 1;
  /* Filter criterion: Field collection item: Measure (field_farm_quantity_measure) */
  $handler->display->display_options['filters']['field_farm_quantity_measure_value']['id'] = 'field_farm_quantity_measure_value';
  $handler->display->display_options['filters']['field_farm_quantity_measure_value']['table'] = 'field_data_field_farm_quantity_measure';
  $handler->display->display_options['filters']['field_farm_quantity_measure_value']['field'] = 'field_farm_quantity_measure_value';
  $handler->display->display_options['filters']['field_farm_quantity_measure_value']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_quantity_measure_value']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_quantity_measure_value']['expose']['operator_id'] = 'field_farm_quantity_measure_value_op';
  $handler->display->display_options['filters']['field_farm_quantity_measure_value']['expose']['label'] = 'Measure';
  $handler->display->display_options['filters']['field_farm_quantity_measure_value']['expose']['operator'] = 'field_farm_quantity_measure_value_op';
  $handler->display->display_options['filters']['field_farm_quantity_measure_value']['expose']['identifier'] = 'field_farm_quantity_measure_value';
  /* Filter criterion: Field: Value (decimal) */
  $handler->display->display_options['filters']['field_farm_quantity_value_decimal']['id'] = 'field_farm_quantity_value_decimal';
  $handler->display->display_options['filters']['field_farm_quantity_value_decimal']['table'] = 'field_data_field_farm_quantity_value';
  $handler->display->display_options['filters']['field_farm_quantity_value_decimal']['field'] = 'field_farm_quantity_value_decimal';
  $handler->display->display_options['filters']['field_farm_quantity_value_decimal']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_quantity_value_decimal']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_quantity_value_decimal']['expose']['operator_id'] = 'field_farm_quantity_value_decimal_op';
  $handler->display->display_options['filters']['field_farm_quantity_value_decimal']['expose']['label'] = 'Value';
  $handler->display->display_options['filters']['field_farm_quantity_value_decimal']['expose']['use_operator'] = TRUE;
  $handler->display->display_options['filters']['field_farm_quantity_value_decimal']['expose']['operator'] = 'field_farm_quantity_value_decimal_op';
  $handler->display->display_options['filters']['field_farm_quantity_value_decimal']['expose']['identifier'] = 'field_farm_quantity_value_decimal';
  /* Filter criterion: Field: Units (field_farm_quantity_units) */
  $handler->display->display_options['filters']['field_farm_quantity_units_tid']['id'] = 'field_farm_quantity_units_tid';
  $handler->display->display_options['filters']['field_farm_quantity_units_tid']['table'] = 'field_data_field_farm_quantity_units';
  $handler->display->display_options['filters']['field_farm_quantity_units_tid']['field'] = 'field_farm_quantity_units_tid';
  $handler->display->display_options['filters']['field_farm_quantity_units_tid']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_quantity_units_tid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_quantity_units_tid']['expose']['operator_id'] = 'field_farm_quantity_units_tid_op';
  $handler->display->display_options['filters']['field_farm_quantity_units_tid']['expose']['label'] = 'Units';
  $handler->display->display_options['filters']['field_farm_quantity_units_tid']['expose']['operator'] = 'field_farm_quantity_units_tid_op';
  $handler->display->display_options['filters']['field_farm_quantity_units_tid']['expose']['identifier'] = 'field_farm_quantity_units_tid';
  $handler->display->display_options['filters']['field_farm_quantity_units_tid']['type'] = 'select';
  $handler->display->display_options['filters']['field_farm_quantity_units_tid']['vocabulary'] = 'farm_quantity_units';
  $handler->display->display_options['filters']['field_farm_quantity_units_tid']['hierarchy'] = 1;
  /* Filter criterion: Field collection item: Label (field_farm_quantity_label) */
  $handler->display->display_options['filters']['field_farm_quantity_label_value']['id'] = 'field_farm_quantity_label_value';
  $handler->display->display_options['filters']['field_farm_quantity_label_value']['table'] = 'field_data_field_farm_quantity_label';
  $handler->display->display_options['filters']['field_farm_quantity_label_value']['field'] = 'field_farm_quantity_label_value';
  $handler->display->display_options['filters']['field_farm_quantity_label_value']['operator'] = 'contains';
  $handler->display->display_options['filters']['field_farm_quantity_label_value']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_quantity_label_value']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_quantity_label_value']['expose']['operator_id'] = 'field_farm_quantity_label_value_op';
  $handler->display->display_options['filters']['field_farm_quantity_label_value']['expose']['label'] = 'Label';
  $handler->display->display_options['filters']['field_farm_quantity_label_value']['expose']['operator'] = 'field_farm_quantity_label_value_op';
  $handler->display->display_options['filters']['field_farm_quantity_label_value']['expose']['identifier'] = 'field_farm_quantity_label_value';
  /* Filter criterion: Log: Done */
  $handler->display->display_options['filters']['done']['id'] = 'done';
  $handler->display->display_options['filters']['done']['table'] = 'log';
  $handler->display->display_options['filters']['done']['field'] = 'done';
  $handler->display->display_options['filters']['done']['relationship'] = 'field_farm_quantity_log';
  $handler->display->display_options['filters']['done']['value'] = '1';
  $handler->display->display_options['filters']['done']['group'] = 1;
  $handler->display->display_options['filters']['done']['exposed'] = TRUE;
  $handler->display->display_options['filters']['done']['expose']['operator_id'] = '';
  $handler->display->display_options['filters']['done']['expose']['label'] = 'Log done';
  $handler->display->display_options['filters']['done']['expose']['operator'] = 'done_op';
  $handler->display->display_options['filters']['done']['expose']['identifier'] = 'done';
  $handler->display->display_options['filters']['done']['expose']['required'] = TRUE;

  /* Display: Page */
  $handler = $view->new_display('page', 'Page', 'page');
  $handler->display->display_options['path'] = 'farm/quantity';
  $handler->display->display_options['menu']['type'] = 'tab';
  $handler->display->display_options['menu']['title'] = 'Quantity';
  $handler->display->display_options['menu']['weight'] = '0';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;

  /* Display: Data export */
  $handler = $view->new_display('views_data_export', 'Data export', 'views_data_export_1');
  $handler->display->display_options['pager']['type'] = 'none';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['style_plugin'] = 'views_data_export_csv';
  $handler->display->display_options['style_options']['provide_file'] = 1;
  $handler->display->display_options['style_options']['filename'] = '%view-%exposed-%timestamp-full.csv';
  $handler->display->display_options['style_options']['parent_sort'] = 1;
  $handler->display->display_options['style_options']['quote'] = 1;
  $handler->display->display_options['style_options']['trim'] = 1;
  $handler->display->display_options['style_options']['replace_newlines'] = 0;
  $handler->display->display_options['style_options']['newline_token'] = '1';
  $handler->display->display_options['style_options']['header'] = 1;
  $handler->display->display_options['style_options']['keep_html'] = 0;
  $handler->display->display_options['path'] = 'farm/quantity/csv';
  $handler->display->display_options['displays'] = array(
    'page' => 'page',
    'default' => 0,
  );
  $handler->display->display_options['use_batch'] = 'batch';
  $handler->display->display_options['segment_size'] = '100';
  $translatables['farm_quantity_report'] = array(
    t('Master'),
    t('Quantity Report'),
    t('more'),
    t('Apply'),
    t('Reset'),
    t('Sort by'),
    t('Asc'),
    t('Desc'),
    t('Use this form to generate a report of quantity measurement logs. Reports can be exported to a file by clicking the CSV button at the bottom. Select any filter and click on Apply to see results.'),
    t('Items per page'),
    t('- All -'),
    t('Offset'),
    t('« first'),
    t('‹ previous'),
    t('next ›'),
    t('last »'),
    t('No quantity measurements found.'),
    t('log'),
    t('Farm asset entity referenced from field_farm_asset'),
    t('Field collection item ID'),
    t('.'),
    t(','),
    t('Done'),
    t('✔'),
    t('Log ID'),
    t('Date'),
    t('Log type'),
    t('Log name'),
    t('Assets'),
    t('Areas'),
    t('Flags'),
    t('Log category'),
    t('Measure'),
    t('Value'),
    t('Units'),
    t('Label'),
    t('Quantity value'),
    t('Asset name'),
    t('Asset type'),
    t('Log done'),
    t('Page'),
    t('Data export'),
  );
  $export['farm_quantity_report'] = $view;

  return $export;
}
