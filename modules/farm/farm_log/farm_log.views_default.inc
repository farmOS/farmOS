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
  $view->name = 'farm_log';
  $view->description = '';
  $view->tag = 'farm_log';
  $view->base_table = 'log';
  $view->human_name = 'Farm Logs';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Logs';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['link_display'] = 'page';
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['access']['perm'] = 'view all logs';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['exposed_form']['options']['reset_button'] = TRUE;
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['pager']['options']['items_per_page'] = '50';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['pager']['options']['id'] = '0';
  $handler->display->display_options['pager']['options']['quantity'] = '9';
  $handler->display->display_options['pager']['options']['expose']['items_per_page'] = TRUE;
  $handler->display->display_options['pager']['options']['expose']['items_per_page_options'] = '25, 50, 100, 250, 500';
  $handler->display->display_options['pager']['options']['expose']['items_per_page_options_all'] = TRUE;
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['columns'] = array(
    'views_bulk_operations' => 'views_bulk_operations',
    'done' => 'done',
    'id' => 'id',
    'timestamp' => 'timestamp',
    'name' => 'name',
    'field_farm_asset' => 'field_farm_asset',
    'field_farm_area' => 'field_farm_area',
    'type' => 'type',
    'field_farm_flags' => 'field_farm_flags',
    'field_farm_log_category' => 'field_farm_log_category',
    'field_farm_log_owner' => 'field_farm_log_owner',
  );
  $handler->display->display_options['style_options']['class'] = '';
  $handler->display->display_options['style_options']['default'] = 'timestamp';
  $handler->display->display_options['style_options']['info'] = array(
    'views_bulk_operations' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'done' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'id' => array(
      'sortable' => 1,
      'default_sort_order' => 'desc',
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
    'type' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
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
    'field_farm_log_owner' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
  );
  $handler->display->display_options['style_options']['empty_table'] = TRUE;
  /* Footer: Global: Result summary */
  $handler->display->display_options['footer']['result']['id'] = 'result';
  $handler->display->display_options['footer']['result']['table'] = 'views';
  $handler->display->display_options['footer']['result']['field'] = 'result';
  /* No results behavior: Global: Unfiltered text */
  $handler->display->display_options['empty']['area_text_custom']['id'] = 'area_text_custom';
  $handler->display->display_options['empty']['area_text_custom']['table'] = 'views';
  $handler->display->display_options['empty']['area_text_custom']['field'] = 'area_text_custom';
  $handler->display->display_options['empty']['area_text_custom']['empty'] = TRUE;
  $handler->display->display_options['empty']['area_text_custom']['content'] = 'No logs found.';
  /* Field: Bulk operations: Log */
  $handler->display->display_options['fields']['views_bulk_operations']['id'] = 'views_bulk_operations';
  $handler->display->display_options['fields']['views_bulk_operations']['table'] = 'views_entity_log';
  $handler->display->display_options['fields']['views_bulk_operations']['field'] = 'views_bulk_operations';
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['display_type'] = '1';
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['enable_select_all_pages'] = 1;
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['row_clickable'] = 1;
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['force_single'] = 0;
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['entity_load_capacity'] = '10';
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['skip_batching'] = 1;
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_operations'] = array(
    'action::farm_log_assign_action' => array(
      'selected' => 1,
      'postpone_processing' => 0,
      'skip_confirmation' => 1,
      'skip_permission_check' => 0,
      'override_label' => 0,
      'label' => '',
    ),
    'action::log_clone_action' => array(
      'selected' => 1,
      'postpone_processing' => 0,
      'skip_confirmation' => 1,
      'skip_permission_check' => 0,
      'override_label' => 0,
      'label' => '',
    ),
    'action::views_bulk_operations_delete_item' => array(
      'selected' => 1,
      'postpone_processing' => 0,
      'skip_confirmation' => 0,
      'skip_permission_check' => 0,
      'override_label' => 1,
      'label' => 'Delete',
      'settings' => array(
        'log' => 0,
      ),
    ),
    'action::farm_flags_action' => array(
      'selected' => 1,
      'postpone_processing' => 0,
      'skip_confirmation' => 1,
      'skip_permission_check' => 0,
      'override_label' => 0,
      'label' => '',
    ),
    'action::farm_map_kml_action' => array(
      'selected' => 1,
      'skip_confirmation' => 1,
      'skip_permission_check' => 0,
      'override_label' => 0,
      'label' => '',
    ),
    'action::log_done_action' => array(
      'selected' => 1,
      'postpone_processing' => 0,
      'skip_confirmation' => 1,
      'skip_permission_check' => 0,
      'override_label' => 1,
      'label' => 'Done',
    ),
    'action::log_undone_action' => array(
      'selected' => 1,
      'postpone_processing' => 0,
      'skip_confirmation' => 1,
      'skip_permission_check' => 0,
      'override_label' => 1,
      'label' => 'Not Done',
    ),
    'action::log_reschedule_action' => array(
      'selected' => 1,
      'postpone_processing' => 0,
      'skip_confirmation' => 1,
      'skip_permission_check' => 0,
      'override_label' => 0,
      'label' => '',
    ),
  );
  /* Field: Log: Done */
  $handler->display->display_options['fields']['done']['id'] = 'done';
  $handler->display->display_options['fields']['done']['table'] = 'log';
  $handler->display->display_options['fields']['done']['field'] = 'done';
  $handler->display->display_options['fields']['done']['type'] = 'custom';
  $handler->display->display_options['fields']['done']['type_custom_true'] = '✔';
  $handler->display->display_options['fields']['done']['not'] = 0;
  /* Field: Log: Log ID */
  $handler->display->display_options['fields']['id']['id'] = 'id';
  $handler->display->display_options['fields']['id']['table'] = 'log';
  $handler->display->display_options['fields']['id']['field'] = 'id';
  $handler->display->display_options['fields']['id']['separator'] = '';
  /* Field: Log: Timestamp */
  $handler->display->display_options['fields']['timestamp']['id'] = 'timestamp';
  $handler->display->display_options['fields']['timestamp']['table'] = 'log';
  $handler->display->display_options['fields']['timestamp']['field'] = 'timestamp';
  $handler->display->display_options['fields']['timestamp']['label'] = 'Date';
  $handler->display->display_options['fields']['timestamp']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['timestamp']['alter']['path'] = 'log/[id]';
  $handler->display->display_options['fields']['timestamp']['date_format'] = 'custom';
  $handler->display->display_options['fields']['timestamp']['custom_date_format'] = 'M j Y';
  $handler->display->display_options['fields']['timestamp']['second_date_format'] = 'long';
  /* Field: Log: Name */
  $handler->display->display_options['fields']['name']['id'] = 'name';
  $handler->display->display_options['fields']['name']['table'] = 'log';
  $handler->display->display_options['fields']['name']['field'] = 'name';
  $handler->display->display_options['fields']['name']['label'] = 'Log name';
  $handler->display->display_options['fields']['name']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['name']['alter']['path'] = 'log/[id]';
  /* Field: Log: Assets */
  $handler->display->display_options['fields']['field_farm_asset']['id'] = 'field_farm_asset';
  $handler->display->display_options['fields']['field_farm_asset']['table'] = 'field_data_field_farm_asset';
  $handler->display->display_options['fields']['field_farm_asset']['field'] = 'field_farm_asset';
  $handler->display->display_options['fields']['field_farm_asset']['settings'] = array(
    'link' => 1,
  );
  /* Field: Log: Areas */
  $handler->display->display_options['fields']['field_farm_area']['id'] = 'field_farm_area';
  $handler->display->display_options['fields']['field_farm_area']['table'] = 'field_data_field_farm_area';
  $handler->display->display_options['fields']['field_farm_area']['field'] = 'field_farm_area';
  /* Field: Log: Log type */
  $handler->display->display_options['fields']['type']['id'] = 'type';
  $handler->display->display_options['fields']['type']['table'] = 'log';
  $handler->display->display_options['fields']['type']['field'] = 'type';
  /* Field: Field: Flags */
  $handler->display->display_options['fields']['field_farm_flags']['id'] = 'field_farm_flags';
  $handler->display->display_options['fields']['field_farm_flags']['table'] = 'field_data_field_farm_flags';
  $handler->display->display_options['fields']['field_farm_flags']['field'] = 'field_farm_flags';
  $handler->display->display_options['fields']['field_farm_flags']['delta_offset'] = '0';
  /* Field: Log: Log category */
  $handler->display->display_options['fields']['field_farm_log_category']['id'] = 'field_farm_log_category';
  $handler->display->display_options['fields']['field_farm_log_category']['table'] = 'field_data_field_farm_log_category';
  $handler->display->display_options['fields']['field_farm_log_category']['field'] = 'field_farm_log_category';
  $handler->display->display_options['fields']['field_farm_log_category']['label'] = 'Categories';
  $handler->display->display_options['fields']['field_farm_log_category']['type'] = 'taxonomy_term_reference_plain';
  $handler->display->display_options['fields']['field_farm_log_category']['delta_offset'] = '0';
  /* Field: Log: Assigned to */
  $handler->display->display_options['fields']['field_farm_log_owner']['id'] = 'field_farm_log_owner';
  $handler->display->display_options['fields']['field_farm_log_owner']['table'] = 'field_data_field_farm_log_owner';
  $handler->display->display_options['fields']['field_farm_log_owner']['field'] = 'field_farm_log_owner';
  $handler->display->display_options['fields']['field_farm_log_owner']['settings'] = array(
    'bypass_access' => 0,
    'link' => 0,
  );
  $handler->display->display_options['fields']['field_farm_log_owner']['delta_offset'] = '0';
  /* Filter criterion: Log: Name */
  $handler->display->display_options['filters']['name']['id'] = 'name';
  $handler->display->display_options['filters']['name']['table'] = 'log';
  $handler->display->display_options['filters']['name']['field'] = 'name';
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
  $handler->display->display_options['filters']['type']['group'] = 1;
  $handler->display->display_options['filters']['type']['exposed'] = TRUE;
  $handler->display->display_options['filters']['type']['expose']['operator_id'] = 'type_op';
  $handler->display->display_options['filters']['type']['expose']['label'] = 'Log type';
  $handler->display->display_options['filters']['type']['expose']['operator'] = 'type_op';
  $handler->display->display_options['filters']['type']['expose']['identifier'] = 'type';
  $handler->display->display_options['filters']['type']['expose']['multiple'] = TRUE;
  /* Filter criterion: Log: Areas (field_farm_area) */
  $handler->display->display_options['filters']['field_farm_area_tid']['id'] = 'field_farm_area_tid';
  $handler->display->display_options['filters']['field_farm_area_tid']['table'] = 'field_data_field_farm_area';
  $handler->display->display_options['filters']['field_farm_area_tid']['field'] = 'field_farm_area_tid';
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
  /* Filter criterion: Field: Flags (field_farm_flags) */
  $handler->display->display_options['filters']['field_farm_flags_value']['id'] = 'field_farm_flags_value';
  $handler->display->display_options['filters']['field_farm_flags_value']['table'] = 'field_data_field_farm_flags';
  $handler->display->display_options['filters']['field_farm_flags_value']['field'] = 'field_farm_flags_value';
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
  $handler->display->display_options['filters']['field_farm_log_category_tid']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_log_category_tid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_log_category_tid']['expose']['operator_id'] = 'field_farm_log_category_tid_op';
  $handler->display->display_options['filters']['field_farm_log_category_tid']['expose']['label'] = 'Categories';
  $handler->display->display_options['filters']['field_farm_log_category_tid']['expose']['operator'] = 'field_farm_log_category_tid_op';
  $handler->display->display_options['filters']['field_farm_log_category_tid']['expose']['identifier'] = 'field_farm_log_category_tid';
  $handler->display->display_options['filters']['field_farm_log_category_tid']['expose']['multiple'] = TRUE;
  $handler->display->display_options['filters']['field_farm_log_category_tid']['type'] = 'select';
  $handler->display->display_options['filters']['field_farm_log_category_tid']['vocabulary'] = 'farm_log_categories';
  $handler->display->display_options['filters']['field_farm_log_category_tid']['hierarchy'] = 1;
  /* Filter criterion: Log: Assigned to (field_farm_log_owner) */
  $handler->display->display_options['filters']['field_farm_log_owner_target_id']['id'] = 'field_farm_log_owner_target_id';
  $handler->display->display_options['filters']['field_farm_log_owner_target_id']['table'] = 'field_data_field_farm_log_owner';
  $handler->display->display_options['filters']['field_farm_log_owner_target_id']['field'] = 'field_farm_log_owner_target_id';
  $handler->display->display_options['filters']['field_farm_log_owner_target_id']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_log_owner_target_id']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_log_owner_target_id']['expose']['operator_id'] = 'field_farm_log_owner_target_id_op';
  $handler->display->display_options['filters']['field_farm_log_owner_target_id']['expose']['label'] = 'Assigned to';
  $handler->display->display_options['filters']['field_farm_log_owner_target_id']['expose']['operator'] = 'field_farm_log_owner_target_id_op';
  $handler->display->display_options['filters']['field_farm_log_owner_target_id']['expose']['identifier'] = 'field_farm_log_owner_target_id';
  $handler->display->display_options['filters']['field_farm_log_owner_target_id']['expose']['multiple'] = TRUE;
  /* Filter criterion: Date: Date (log) */
  $handler->display->display_options['filters']['date_filter']['id'] = 'date_filter';
  $handler->display->display_options['filters']['date_filter']['table'] = 'log';
  $handler->display->display_options['filters']['date_filter']['field'] = 'date_filter';
  $handler->display->display_options['filters']['date_filter']['operator'] = '>=';
  $handler->display->display_options['filters']['date_filter']['group'] = 1;
  $handler->display->display_options['filters']['date_filter']['exposed'] = TRUE;
  $handler->display->display_options['filters']['date_filter']['expose']['operator_id'] = 'date_filter_op';
  $handler->display->display_options['filters']['date_filter']['expose']['label'] = 'Start date';
  $handler->display->display_options['filters']['date_filter']['expose']['operator'] = 'date_filter_op';
  $handler->display->display_options['filters']['date_filter']['expose']['identifier'] = 'date_filter';
  $handler->display->display_options['filters']['date_filter']['year_range'] = '-10:+3';
  $handler->display->display_options['filters']['date_filter']['date_fields'] = array(
    'log.timestamp' => 'log.timestamp',
  );
  /* Filter criterion: Date: Date (log) */
  $handler->display->display_options['filters']['date_filter_1']['id'] = 'date_filter_1';
  $handler->display->display_options['filters']['date_filter_1']['table'] = 'log';
  $handler->display->display_options['filters']['date_filter_1']['field'] = 'date_filter';
  $handler->display->display_options['filters']['date_filter_1']['operator'] = '<=';
  $handler->display->display_options['filters']['date_filter_1']['group'] = 1;
  $handler->display->display_options['filters']['date_filter_1']['exposed'] = TRUE;
  $handler->display->display_options['filters']['date_filter_1']['expose']['operator_id'] = 'date_filter_1_op';
  $handler->display->display_options['filters']['date_filter_1']['expose']['label'] = 'End date';
  $handler->display->display_options['filters']['date_filter_1']['expose']['operator'] = 'date_filter_1_op';
  $handler->display->display_options['filters']['date_filter_1']['expose']['identifier'] = 'date_filter_1';
  $handler->display->display_options['filters']['date_filter_1']['year_range'] = '-10:+3';
  $handler->display->display_options['filters']['date_filter_1']['date_fields'] = array(
    'log.timestamp' => 'log.timestamp',
  );
  /* Filter criterion: Log: Done */
  $handler->display->display_options['filters']['done']['id'] = 'done';
  $handler->display->display_options['filters']['done']['table'] = 'log';
  $handler->display->display_options['filters']['done']['field'] = 'done';
  $handler->display->display_options['filters']['done']['value'] = 'All';
  $handler->display->display_options['filters']['done']['group'] = 1;
  $handler->display->display_options['filters']['done']['exposed'] = TRUE;
  $handler->display->display_options['filters']['done']['expose']['operator_id'] = '';
  $handler->display->display_options['filters']['done']['expose']['label'] = 'Done';
  $handler->display->display_options['filters']['done']['expose']['operator'] = 'done_op';
  $handler->display->display_options['filters']['done']['expose']['identifier'] = 'done';

  /* Display: Page */
  $handler = $view->new_display('page', 'Page', 'page');
  $handler->display->display_options['path'] = 'farm/logs';
  $handler->display->display_options['menu']['type'] = 'normal';
  $handler->display->display_options['menu']['title'] = 'Logs';
  $handler->display->display_options['menu']['weight'] = '';
  $handler->display->display_options['menu']['name'] = 'farm';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;
  $handler->display->display_options['tab_options']['type'] = 'normal';
  $handler->display->display_options['tab_options']['title'] = 'Logs';
  $handler->display->display_options['tab_options']['weight'] = '0';
  $handler->display->display_options['tab_options']['name'] = 'farm';

  /* Display: Page: User Logs */
  $handler = $view->new_display('page', 'Page: User Logs', 'page_user');
  $handler->display->display_options['defaults']['arguments'] = FALSE;
  /* Contextual filter: Log: Assigned to (field_farm_log_owner) */
  $handler->display->display_options['arguments']['field_farm_log_owner_target_id']['id'] = 'field_farm_log_owner_target_id';
  $handler->display->display_options['arguments']['field_farm_log_owner_target_id']['table'] = 'field_data_field_farm_log_owner';
  $handler->display->display_options['arguments']['field_farm_log_owner_target_id']['field'] = 'field_farm_log_owner_target_id';
  $handler->display->display_options['arguments']['field_farm_log_owner_target_id']['default_action'] = 'not found';
  $handler->display->display_options['arguments']['field_farm_log_owner_target_id']['title_enable'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_log_owner_target_id']['title'] = 'Logs assigned to %1';
  $handler->display->display_options['arguments']['field_farm_log_owner_target_id']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['field_farm_log_owner_target_id']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['field_farm_log_owner_target_id']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['field_farm_log_owner_target_id']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['arguments']['field_farm_log_owner_target_id']['specify_validation'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_log_owner_target_id']['validate']['type'] = 'user';
  $handler->display->display_options['defaults']['filter_groups'] = FALSE;
  $handler->display->display_options['defaults']['filters'] = FALSE;
  /* Filter criterion: Log: Name */
  $handler->display->display_options['filters']['name']['id'] = 'name';
  $handler->display->display_options['filters']['name']['table'] = 'log';
  $handler->display->display_options['filters']['name']['field'] = 'name';
  $handler->display->display_options['filters']['name']['operator'] = 'contains';
  $handler->display->display_options['filters']['name']['group'] = 1;
  $handler->display->display_options['filters']['name']['exposed'] = TRUE;
  $handler->display->display_options['filters']['name']['expose']['operator_id'] = 'name_op';
  $handler->display->display_options['filters']['name']['expose']['label'] = 'Search';
  $handler->display->display_options['filters']['name']['expose']['operator'] = 'name_op';
  $handler->display->display_options['filters']['name']['expose']['identifier'] = 'name';
  /* Filter criterion: Log: Log type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'log';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['group'] = 1;
  $handler->display->display_options['filters']['type']['exposed'] = TRUE;
  $handler->display->display_options['filters']['type']['expose']['operator_id'] = 'type_op';
  $handler->display->display_options['filters']['type']['expose']['label'] = 'Log type';
  $handler->display->display_options['filters']['type']['expose']['operator'] = 'type_op';
  $handler->display->display_options['filters']['type']['expose']['identifier'] = 'type';
  $handler->display->display_options['filters']['type']['expose']['multiple'] = TRUE;
  /* Filter criterion: Log: Areas (field_farm_area) */
  $handler->display->display_options['filters']['field_farm_area_tid']['id'] = 'field_farm_area_tid';
  $handler->display->display_options['filters']['field_farm_area_tid']['table'] = 'field_data_field_farm_area';
  $handler->display->display_options['filters']['field_farm_area_tid']['field'] = 'field_farm_area_tid';
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
  /* Filter criterion: Field: Flags (field_farm_flags) */
  $handler->display->display_options['filters']['field_farm_flags_value']['id'] = 'field_farm_flags_value';
  $handler->display->display_options['filters']['field_farm_flags_value']['table'] = 'field_data_field_farm_flags';
  $handler->display->display_options['filters']['field_farm_flags_value']['field'] = 'field_farm_flags_value';
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
  $handler->display->display_options['filters']['field_farm_log_category_tid']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_log_category_tid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_log_category_tid']['expose']['operator_id'] = 'field_farm_log_category_tid_op';
  $handler->display->display_options['filters']['field_farm_log_category_tid']['expose']['label'] = 'Categories';
  $handler->display->display_options['filters']['field_farm_log_category_tid']['expose']['operator'] = 'field_farm_log_category_tid_op';
  $handler->display->display_options['filters']['field_farm_log_category_tid']['expose']['identifier'] = 'field_farm_log_category_tid';
  $handler->display->display_options['filters']['field_farm_log_category_tid']['expose']['multiple'] = TRUE;
  $handler->display->display_options['filters']['field_farm_log_category_tid']['type'] = 'select';
  $handler->display->display_options['filters']['field_farm_log_category_tid']['vocabulary'] = 'farm_log_categories';
  $handler->display->display_options['filters']['field_farm_log_category_tid']['hierarchy'] = 1;
  /* Filter criterion: Date: Date (log) */
  $handler->display->display_options['filters']['date_filter']['id'] = 'date_filter';
  $handler->display->display_options['filters']['date_filter']['table'] = 'log';
  $handler->display->display_options['filters']['date_filter']['field'] = 'date_filter';
  $handler->display->display_options['filters']['date_filter']['operator'] = '>=';
  $handler->display->display_options['filters']['date_filter']['group'] = 1;
  $handler->display->display_options['filters']['date_filter']['exposed'] = TRUE;
  $handler->display->display_options['filters']['date_filter']['expose']['operator_id'] = 'date_filter_op';
  $handler->display->display_options['filters']['date_filter']['expose']['label'] = 'Start date';
  $handler->display->display_options['filters']['date_filter']['expose']['operator'] = 'date_filter_op';
  $handler->display->display_options['filters']['date_filter']['expose']['identifier'] = 'date_filter';
  $handler->display->display_options['filters']['date_filter']['year_range'] = '-10:+3';
  $handler->display->display_options['filters']['date_filter']['date_fields'] = array(
    'log.timestamp' => 'log.timestamp',
  );
  /* Filter criterion: Date: Date (log) */
  $handler->display->display_options['filters']['date_filter_1']['id'] = 'date_filter_1';
  $handler->display->display_options['filters']['date_filter_1']['table'] = 'log';
  $handler->display->display_options['filters']['date_filter_1']['field'] = 'date_filter';
  $handler->display->display_options['filters']['date_filter_1']['operator'] = '<=';
  $handler->display->display_options['filters']['date_filter_1']['group'] = 1;
  $handler->display->display_options['filters']['date_filter_1']['exposed'] = TRUE;
  $handler->display->display_options['filters']['date_filter_1']['expose']['operator_id'] = 'date_filter_1_op';
  $handler->display->display_options['filters']['date_filter_1']['expose']['label'] = 'End date';
  $handler->display->display_options['filters']['date_filter_1']['expose']['operator'] = 'date_filter_1_op';
  $handler->display->display_options['filters']['date_filter_1']['expose']['identifier'] = 'date_filter_1';
  $handler->display->display_options['filters']['date_filter_1']['year_range'] = '-10:+3';
  $handler->display->display_options['filters']['date_filter_1']['date_fields'] = array(
    'log.timestamp' => 'log.timestamp',
  );
  /* Filter criterion: Log: Done */
  $handler->display->display_options['filters']['done']['id'] = 'done';
  $handler->display->display_options['filters']['done']['table'] = 'log';
  $handler->display->display_options['filters']['done']['field'] = 'done';
  $handler->display->display_options['filters']['done']['value'] = 'All';
  $handler->display->display_options['filters']['done']['group'] = 1;
  $handler->display->display_options['filters']['done']['exposed'] = TRUE;
  $handler->display->display_options['filters']['done']['expose']['operator_id'] = '';
  $handler->display->display_options['filters']['done']['expose']['label'] = 'Done';
  $handler->display->display_options['filters']['done']['expose']['operator'] = 'done_op';
  $handler->display->display_options['filters']['done']['expose']['identifier'] = 'done';
  $handler->display->display_options['path'] = 'user/%/logs';
  $handler->display->display_options['menu']['type'] = 'tab';
  $handler->display->display_options['menu']['title'] = 'Logs';
  $handler->display->display_options['menu']['weight'] = '0';
  $handler->display->display_options['menu']['name'] = 'user-menu';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;

  /* Display: Page: Upcoming */
  $handler = $view->new_display('page', 'Page: Upcoming', 'page_upcoming');
  $handler->display->display_options['defaults']['title'] = FALSE;
  $handler->display->display_options['title'] = 'Upcoming tasks';
  $handler->display->display_options['defaults']['style_plugin'] = FALSE;
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['columns'] = array(
    'views_bulk_operations' => 'views_bulk_operations',
    'done' => 'done',
    'id' => 'id',
    'timestamp' => 'timestamp',
    'name' => 'name',
    'field_farm_asset' => 'field_farm_asset',
    'field_farm_area' => 'field_farm_area',
    'type' => 'type',
    'field_farm_flags' => 'field_farm_flags',
    'field_farm_log_category' => 'field_farm_log_category',
    'field_farm_log_owner' => 'field_farm_log_owner',
  );
  $handler->display->display_options['style_options']['class'] = '';
  $handler->display->display_options['style_options']['default'] = 'timestamp';
  $handler->display->display_options['style_options']['info'] = array(
    'views_bulk_operations' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'done' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'id' => array(
      'sortable' => 1,
      'default_sort_order' => 'desc',
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
    'name' => array(
      'sortable' => 1,
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
    'type' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
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
    'field_farm_log_owner' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
  );
  $handler->display->display_options['style_options']['empty_table'] = TRUE;
  $handler->display->display_options['defaults']['style_options'] = FALSE;
  $handler->display->display_options['defaults']['row_plugin'] = FALSE;
  $handler->display->display_options['defaults']['row_options'] = FALSE;
  $handler->display->display_options['defaults']['empty'] = FALSE;
  /* No results behavior: Global: Unfiltered text */
  $handler->display->display_options['empty']['area_text_custom']['id'] = 'area_text_custom';
  $handler->display->display_options['empty']['area_text_custom']['table'] = 'views';
  $handler->display->display_options['empty']['area_text_custom']['field'] = 'area_text_custom';
  $handler->display->display_options['empty']['area_text_custom']['empty'] = TRUE;
  $handler->display->display_options['empty']['area_text_custom']['content'] = 'No tasks are currently planned.';
  $handler->display->display_options['defaults']['filter_groups'] = FALSE;
  $handler->display->display_options['defaults']['filters'] = FALSE;
  /* Filter criterion: Date: Date (log) */
  $handler->display->display_options['filters']['date_filter']['id'] = 'date_filter';
  $handler->display->display_options['filters']['date_filter']['table'] = 'log';
  $handler->display->display_options['filters']['date_filter']['field'] = 'date_filter';
  $handler->display->display_options['filters']['date_filter']['operator'] = '>=';
  $handler->display->display_options['filters']['date_filter']['group'] = 1;
  $handler->display->display_options['filters']['date_filter']['expose']['operator_id'] = 'date_filter_op';
  $handler->display->display_options['filters']['date_filter']['expose']['label'] = 'Start date';
  $handler->display->display_options['filters']['date_filter']['expose']['operator'] = 'date_filter_op';
  $handler->display->display_options['filters']['date_filter']['expose']['identifier'] = 'date_filter';
  $handler->display->display_options['filters']['date_filter']['default_date'] = 'today';
  $handler->display->display_options['filters']['date_filter']['year_range'] = '-10:+3';
  $handler->display->display_options['filters']['date_filter']['date_fields'] = array(
    'log.timestamp' => 'log.timestamp',
  );
  /* Filter criterion: Log: Done */
  $handler->display->display_options['filters']['done']['id'] = 'done';
  $handler->display->display_options['filters']['done']['table'] = 'log';
  $handler->display->display_options['filters']['done']['field'] = 'done';
  $handler->display->display_options['filters']['done']['value'] = '0';
  $handler->display->display_options['filters']['done']['group'] = 1;
  $handler->display->display_options['filters']['done']['expose']['operator_id'] = '';
  $handler->display->display_options['filters']['done']['expose']['label'] = 'Done';
  $handler->display->display_options['filters']['done']['expose']['operator'] = 'done_op';
  $handler->display->display_options['filters']['done']['expose']['identifier'] = 'done';
  /* Filter criterion: Field: Flags (field_farm_flags) */
  $handler->display->display_options['filters']['field_farm_flags_value']['id'] = 'field_farm_flags_value';
  $handler->display->display_options['filters']['field_farm_flags_value']['table'] = 'field_data_field_farm_flags';
  $handler->display->display_options['filters']['field_farm_flags_value']['field'] = 'field_farm_flags_value';
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
  $handler->display->display_options['filters']['field_farm_log_category_tid']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_log_category_tid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_log_category_tid']['expose']['operator_id'] = 'field_farm_log_category_tid_op';
  $handler->display->display_options['filters']['field_farm_log_category_tid']['expose']['label'] = 'Log category';
  $handler->display->display_options['filters']['field_farm_log_category_tid']['expose']['operator'] = 'field_farm_log_category_tid_op';
  $handler->display->display_options['filters']['field_farm_log_category_tid']['expose']['identifier'] = 'field_farm_log_category_tid';
  $handler->display->display_options['filters']['field_farm_log_category_tid']['expose']['multiple'] = TRUE;
  $handler->display->display_options['filters']['field_farm_log_category_tid']['type'] = 'select';
  $handler->display->display_options['filters']['field_farm_log_category_tid']['vocabulary'] = 'farm_log_categories';
  $handler->display->display_options['filters']['field_farm_log_category_tid']['hierarchy'] = 1;
  $handler->display->display_options['path'] = 'farm/logs/plan';
  $handler->display->display_options['menu']['title'] = 'Logs';
  $handler->display->display_options['menu']['weight'] = '';
  $handler->display->display_options['menu']['name'] = 'farm';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;
  $handler->display->display_options['tab_options']['type'] = 'normal';
  $handler->display->display_options['tab_options']['title'] = 'Logs';
  $handler->display->display_options['tab_options']['weight'] = '0';
  $handler->display->display_options['tab_options']['name'] = 'farm';

  /* Display: Block: Upcoming */
  $handler = $view->new_display('block', 'Block: Upcoming', 'block_upcoming');
  $handler->display->display_options['defaults']['title'] = FALSE;
  $handler->display->display_options['title'] = 'Upcoming tasks';
  $handler->display->display_options['defaults']['use_more'] = FALSE;
  $handler->display->display_options['use_more'] = TRUE;
  $handler->display->display_options['defaults']['use_more_always'] = FALSE;
  $handler->display->display_options['defaults']['use_more_always'] = FALSE;
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['defaults']['use_more_text'] = FALSE;
  $handler->display->display_options['defaults']['link_display'] = FALSE;
  $handler->display->display_options['link_display'] = 'page_upcoming';
  $handler->display->display_options['defaults']['pager'] = FALSE;
  $handler->display->display_options['pager']['type'] = 'some';
  $handler->display->display_options['pager']['options']['items_per_page'] = '10';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['defaults']['style_plugin'] = FALSE;
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['columns'] = array(
    'views_bulk_operations' => 'views_bulk_operations',
    'done' => 'done',
    'id' => 'id',
    'timestamp' => 'timestamp',
    'name' => 'name',
    'type' => 'type',
  );
  $handler->display->display_options['style_options']['class'] = '';
  $handler->display->display_options['style_options']['default'] = 'timestamp';
  $handler->display->display_options['style_options']['info'] = array(
    'views_bulk_operations' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'done' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'id' => array(
      'sortable' => 1,
      'default_sort_order' => 'desc',
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
    'name' => array(
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
  );
  $handler->display->display_options['style_options']['empty_table'] = TRUE;
  $handler->display->display_options['defaults']['style_options'] = FALSE;
  $handler->display->display_options['defaults']['row_plugin'] = FALSE;
  $handler->display->display_options['defaults']['row_options'] = FALSE;
  $handler->display->display_options['defaults']['footer'] = FALSE;
  $handler->display->display_options['defaults']['empty'] = FALSE;
  /* No results behavior: Global: Unfiltered text */
  $handler->display->display_options['empty']['area_text_custom']['id'] = 'area_text_custom';
  $handler->display->display_options['empty']['area_text_custom']['table'] = 'views';
  $handler->display->display_options['empty']['area_text_custom']['field'] = 'area_text_custom';
  $handler->display->display_options['empty']['area_text_custom']['empty'] = TRUE;
  $handler->display->display_options['empty']['area_text_custom']['content'] = 'No tasks are currently planned.';
  $handler->display->display_options['defaults']['fields'] = FALSE;
  /* Field: Bulk operations: Log */
  $handler->display->display_options['fields']['views_bulk_operations']['id'] = 'views_bulk_operations';
  $handler->display->display_options['fields']['views_bulk_operations']['table'] = 'views_entity_log';
  $handler->display->display_options['fields']['views_bulk_operations']['field'] = 'views_bulk_operations';
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['display_type'] = '1';
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['enable_select_all_pages'] = 1;
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['row_clickable'] = 1;
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['force_single'] = 0;
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['entity_load_capacity'] = '10';
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['skip_batching'] = 1;
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_operations'] = array(
    'action::farm_log_assign_action' => array(
      'selected' => 1,
      'postpone_processing' => 0,
      'skip_confirmation' => 1,
      'skip_permission_check' => 0,
      'override_label' => 0,
      'label' => '',
    ),
    'action::log_clone_action' => array(
      'selected' => 1,
      'postpone_processing' => 0,
      'skip_confirmation' => 1,
      'skip_permission_check' => 0,
      'override_label' => 0,
      'label' => '',
    ),
    'action::views_bulk_operations_delete_item' => array(
      'selected' => 1,
      'postpone_processing' => 0,
      'skip_confirmation' => 0,
      'skip_permission_check' => 0,
      'override_label' => 1,
      'label' => 'Delete',
      'settings' => array(
        'log' => 0,
      ),
    ),
    'action::log_done_action' => array(
      'selected' => 1,
      'postpone_processing' => 0,
      'skip_confirmation' => 1,
      'skip_permission_check' => 0,
      'override_label' => 1,
      'label' => 'Done',
    ),
    'action::log_undone_action' => array(
      'selected' => 1,
      'postpone_processing' => 0,
      'skip_confirmation' => 1,
      'skip_permission_check' => 0,
      'override_label' => 1,
      'label' => 'Not Done',
    ),
    'action::log_reschedule_action' => array(
      'selected' => 1,
      'postpone_processing' => 0,
      'skip_confirmation' => 1,
      'skip_permission_check' => 0,
      'override_label' => 0,
      'label' => '',
    ),
  );
  /* Field: Log: Done */
  $handler->display->display_options['fields']['done']['id'] = 'done';
  $handler->display->display_options['fields']['done']['table'] = 'log';
  $handler->display->display_options['fields']['done']['field'] = 'done';
  $handler->display->display_options['fields']['done']['type'] = 'custom';
  $handler->display->display_options['fields']['done']['type_custom_true'] = '✔';
  $handler->display->display_options['fields']['done']['not'] = 0;
  /* Field: Log: Log ID */
  $handler->display->display_options['fields']['id']['id'] = 'id';
  $handler->display->display_options['fields']['id']['table'] = 'log';
  $handler->display->display_options['fields']['id']['field'] = 'id';
  $handler->display->display_options['fields']['id']['exclude'] = TRUE;
  $handler->display->display_options['fields']['id']['separator'] = '';
  /* Field: Log: Timestamp */
  $handler->display->display_options['fields']['timestamp']['id'] = 'timestamp';
  $handler->display->display_options['fields']['timestamp']['table'] = 'log';
  $handler->display->display_options['fields']['timestamp']['field'] = 'timestamp';
  $handler->display->display_options['fields']['timestamp']['label'] = 'Date';
  $handler->display->display_options['fields']['timestamp']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['timestamp']['alter']['path'] = 'log/[id]';
  $handler->display->display_options['fields']['timestamp']['date_format'] = 'custom';
  $handler->display->display_options['fields']['timestamp']['custom_date_format'] = 'M j Y';
  $handler->display->display_options['fields']['timestamp']['second_date_format'] = 'long';
  /* Field: Log: Name */
  $handler->display->display_options['fields']['name']['id'] = 'name';
  $handler->display->display_options['fields']['name']['table'] = 'log';
  $handler->display->display_options['fields']['name']['field'] = 'name';
  $handler->display->display_options['fields']['name']['label'] = 'Log name';
  $handler->display->display_options['fields']['name']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['name']['alter']['path'] = 'log/[id]';
  /* Field: Log: Log type */
  $handler->display->display_options['fields']['type']['id'] = 'type';
  $handler->display->display_options['fields']['type']['table'] = 'log';
  $handler->display->display_options['fields']['type']['field'] = 'type';
  $handler->display->display_options['defaults']['filter_groups'] = FALSE;
  $handler->display->display_options['defaults']['filters'] = FALSE;
  /* Filter criterion: Date: Date (log) */
  $handler->display->display_options['filters']['date_filter']['id'] = 'date_filter';
  $handler->display->display_options['filters']['date_filter']['table'] = 'log';
  $handler->display->display_options['filters']['date_filter']['field'] = 'date_filter';
  $handler->display->display_options['filters']['date_filter']['operator'] = '>=';
  $handler->display->display_options['filters']['date_filter']['group'] = 1;
  $handler->display->display_options['filters']['date_filter']['expose']['operator_id'] = 'date_filter_op';
  $handler->display->display_options['filters']['date_filter']['expose']['label'] = 'Start date';
  $handler->display->display_options['filters']['date_filter']['expose']['operator'] = 'date_filter_op';
  $handler->display->display_options['filters']['date_filter']['expose']['identifier'] = 'date_filter';
  $handler->display->display_options['filters']['date_filter']['default_date'] = 'today';
  $handler->display->display_options['filters']['date_filter']['year_range'] = '-10:+3';
  $handler->display->display_options['filters']['date_filter']['date_fields'] = array(
    'log.timestamp' => 'log.timestamp',
  );
  /* Filter criterion: Log: Done */
  $handler->display->display_options['filters']['done']['id'] = 'done';
  $handler->display->display_options['filters']['done']['table'] = 'log';
  $handler->display->display_options['filters']['done']['field'] = 'done';
  $handler->display->display_options['filters']['done']['value'] = '0';
  $handler->display->display_options['filters']['done']['group'] = 1;
  $handler->display->display_options['filters']['done']['expose']['operator_id'] = '';
  $handler->display->display_options['filters']['done']['expose']['label'] = 'Done';
  $handler->display->display_options['filters']['done']['expose']['operator'] = 'done_op';
  $handler->display->display_options['filters']['done']['expose']['identifier'] = 'done';

  /* Display: Page: Late */
  $handler = $view->new_display('page', 'Page: Late', 'page_late');
  $handler->display->display_options['defaults']['title'] = FALSE;
  $handler->display->display_options['title'] = 'Late tasks';
  $handler->display->display_options['defaults']['filter_groups'] = FALSE;
  $handler->display->display_options['defaults']['filters'] = FALSE;
  /* Filter criterion: Date: Date (log) */
  $handler->display->display_options['filters']['date_filter']['id'] = 'date_filter';
  $handler->display->display_options['filters']['date_filter']['table'] = 'log';
  $handler->display->display_options['filters']['date_filter']['field'] = 'date_filter';
  $handler->display->display_options['filters']['date_filter']['operator'] = '<';
  $handler->display->display_options['filters']['date_filter']['group'] = 1;
  $handler->display->display_options['filters']['date_filter']['expose']['operator_id'] = 'date_filter_op';
  $handler->display->display_options['filters']['date_filter']['expose']['label'] = 'Start date';
  $handler->display->display_options['filters']['date_filter']['expose']['operator'] = 'date_filter_op';
  $handler->display->display_options['filters']['date_filter']['expose']['identifier'] = 'date_filter';
  $handler->display->display_options['filters']['date_filter']['default_date'] = 'today';
  $handler->display->display_options['filters']['date_filter']['year_range'] = '-10:+3';
  $handler->display->display_options['filters']['date_filter']['date_fields'] = array(
    'log.timestamp' => 'log.timestamp',
  );
  /* Filter criterion: Log: Done */
  $handler->display->display_options['filters']['done']['id'] = 'done';
  $handler->display->display_options['filters']['done']['table'] = 'log';
  $handler->display->display_options['filters']['done']['field'] = 'done';
  $handler->display->display_options['filters']['done']['value'] = '0';
  $handler->display->display_options['filters']['done']['group'] = 1;
  $handler->display->display_options['filters']['done']['expose']['operator_id'] = '';
  $handler->display->display_options['filters']['done']['expose']['label'] = 'Done';
  $handler->display->display_options['filters']['done']['expose']['operator'] = 'done_op';
  $handler->display->display_options['filters']['done']['expose']['identifier'] = 'done';
  /* Filter criterion: Field: Flags (field_farm_flags) */
  $handler->display->display_options['filters']['field_farm_flags_value']['id'] = 'field_farm_flags_value';
  $handler->display->display_options['filters']['field_farm_flags_value']['table'] = 'field_data_field_farm_flags';
  $handler->display->display_options['filters']['field_farm_flags_value']['field'] = 'field_farm_flags_value';
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
  $handler->display->display_options['filters']['field_farm_log_category_tid']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_log_category_tid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_log_category_tid']['expose']['operator_id'] = 'field_farm_log_category_tid_op';
  $handler->display->display_options['filters']['field_farm_log_category_tid']['expose']['label'] = 'Log category';
  $handler->display->display_options['filters']['field_farm_log_category_tid']['expose']['operator'] = 'field_farm_log_category_tid_op';
  $handler->display->display_options['filters']['field_farm_log_category_tid']['expose']['identifier'] = 'field_farm_log_category_tid';
  $handler->display->display_options['filters']['field_farm_log_category_tid']['expose']['multiple'] = TRUE;
  $handler->display->display_options['filters']['field_farm_log_category_tid']['type'] = 'select';
  $handler->display->display_options['filters']['field_farm_log_category_tid']['vocabulary'] = 'farm_log_categories';
  $handler->display->display_options['filters']['field_farm_log_category_tid']['hierarchy'] = 1;
  /* Filter criterion: Log: Assigned to (field_farm_log_owner) */
  $handler->display->display_options['filters']['field_farm_log_owner_target_id']['id'] = 'field_farm_log_owner_target_id';
  $handler->display->display_options['filters']['field_farm_log_owner_target_id']['table'] = 'field_data_field_farm_log_owner';
  $handler->display->display_options['filters']['field_farm_log_owner_target_id']['field'] = 'field_farm_log_owner_target_id';
  $handler->display->display_options['filters']['field_farm_log_owner_target_id']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_log_owner_target_id']['expose']['operator_id'] = 'field_farm_log_owner_target_id_op';
  $handler->display->display_options['filters']['field_farm_log_owner_target_id']['expose']['label'] = 'Assigned to';
  $handler->display->display_options['filters']['field_farm_log_owner_target_id']['expose']['operator'] = 'field_farm_log_owner_target_id_op';
  $handler->display->display_options['filters']['field_farm_log_owner_target_id']['expose']['identifier'] = 'field_farm_log_owner_target_id';
  $handler->display->display_options['filters']['field_farm_log_owner_target_id']['expose']['multiple'] = TRUE;
  $handler->display->display_options['path'] = 'farm/logs/late';
  $handler->display->display_options['menu']['title'] = 'Logs';
  $handler->display->display_options['menu']['weight'] = '';
  $handler->display->display_options['menu']['name'] = 'farm';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;
  $handler->display->display_options['tab_options']['type'] = 'normal';
  $handler->display->display_options['tab_options']['title'] = 'Logs';
  $handler->display->display_options['tab_options']['weight'] = '0';
  $handler->display->display_options['tab_options']['name'] = 'farm';

  /* Display: Block: Late */
  $handler = $view->new_display('block', 'Block: Late', 'block_late');
  $handler->display->display_options['defaults']['title'] = FALSE;
  $handler->display->display_options['title'] = 'Late tasks';
  $handler->display->display_options['defaults']['use_more'] = FALSE;
  $handler->display->display_options['use_more'] = TRUE;
  $handler->display->display_options['defaults']['use_more_always'] = FALSE;
  $handler->display->display_options['defaults']['use_more_always'] = FALSE;
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['defaults']['use_more_text'] = FALSE;
  $handler->display->display_options['defaults']['link_display'] = FALSE;
  $handler->display->display_options['link_display'] = 'page_late';
  $handler->display->display_options['defaults']['pager'] = FALSE;
  $handler->display->display_options['pager']['type'] = 'some';
  $handler->display->display_options['pager']['options']['items_per_page'] = '10';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['defaults']['footer'] = FALSE;
  $handler->display->display_options['defaults']['fields'] = FALSE;
  /* Field: Bulk operations: Log */
  $handler->display->display_options['fields']['views_bulk_operations']['id'] = 'views_bulk_operations';
  $handler->display->display_options['fields']['views_bulk_operations']['table'] = 'views_entity_log';
  $handler->display->display_options['fields']['views_bulk_operations']['field'] = 'views_bulk_operations';
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['display_type'] = '1';
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['enable_select_all_pages'] = 1;
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['row_clickable'] = 1;
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['force_single'] = 0;
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['entity_load_capacity'] = '10';
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['skip_batching'] = 1;
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_operations'] = array(
    'action::farm_log_assign_action' => array(
      'selected' => 1,
      'postpone_processing' => 0,
      'skip_confirmation' => 1,
      'skip_permission_check' => 0,
      'override_label' => 0,
      'label' => '',
    ),
    'action::log_clone_action' => array(
      'selected' => 1,
      'postpone_processing' => 0,
      'skip_confirmation' => 1,
      'skip_permission_check' => 0,
      'override_label' => 0,
      'label' => '',
    ),
    'action::views_bulk_operations_delete_item' => array(
      'selected' => 1,
      'postpone_processing' => 0,
      'skip_confirmation' => 0,
      'skip_permission_check' => 0,
      'override_label' => 1,
      'label' => 'Delete',
      'settings' => array(
        'log' => 0,
      ),
    ),
    'action::log_done_action' => array(
      'selected' => 1,
      'postpone_processing' => 0,
      'skip_confirmation' => 1,
      'skip_permission_check' => 0,
      'override_label' => 1,
      'label' => 'Done',
    ),
    'action::log_undone_action' => array(
      'selected' => 1,
      'postpone_processing' => 0,
      'skip_confirmation' => 1,
      'skip_permission_check' => 0,
      'override_label' => 1,
      'label' => 'Not Done',
    ),
    'action::log_reschedule_action' => array(
      'selected' => 1,
      'postpone_processing' => 0,
      'skip_confirmation' => 1,
      'skip_permission_check' => 0,
      'override_label' => 0,
      'label' => '',
    ),
  );
  /* Field: Log: Done */
  $handler->display->display_options['fields']['done']['id'] = 'done';
  $handler->display->display_options['fields']['done']['table'] = 'log';
  $handler->display->display_options['fields']['done']['field'] = 'done';
  $handler->display->display_options['fields']['done']['type'] = 'custom';
  $handler->display->display_options['fields']['done']['type_custom_true'] = '✔';
  $handler->display->display_options['fields']['done']['not'] = 0;
  /* Field: Log: Log ID */
  $handler->display->display_options['fields']['id']['id'] = 'id';
  $handler->display->display_options['fields']['id']['table'] = 'log';
  $handler->display->display_options['fields']['id']['field'] = 'id';
  $handler->display->display_options['fields']['id']['exclude'] = TRUE;
  $handler->display->display_options['fields']['id']['separator'] = '';
  /* Field: Log: Timestamp */
  $handler->display->display_options['fields']['timestamp']['id'] = 'timestamp';
  $handler->display->display_options['fields']['timestamp']['table'] = 'log';
  $handler->display->display_options['fields']['timestamp']['field'] = 'timestamp';
  $handler->display->display_options['fields']['timestamp']['label'] = 'Date';
  $handler->display->display_options['fields']['timestamp']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['timestamp']['alter']['path'] = 'log/[id]';
  $handler->display->display_options['fields']['timestamp']['date_format'] = 'custom';
  $handler->display->display_options['fields']['timestamp']['custom_date_format'] = 'M j Y';
  $handler->display->display_options['fields']['timestamp']['second_date_format'] = 'long';
  /* Field: Log: Name */
  $handler->display->display_options['fields']['name']['id'] = 'name';
  $handler->display->display_options['fields']['name']['table'] = 'log';
  $handler->display->display_options['fields']['name']['field'] = 'name';
  $handler->display->display_options['fields']['name']['label'] = 'Log name';
  $handler->display->display_options['fields']['name']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['name']['alter']['path'] = 'log/[id]';
  /* Field: Log: Log type */
  $handler->display->display_options['fields']['type']['id'] = 'type';
  $handler->display->display_options['fields']['type']['table'] = 'log';
  $handler->display->display_options['fields']['type']['field'] = 'type';
  $handler->display->display_options['defaults']['filter_groups'] = FALSE;
  $handler->display->display_options['defaults']['filters'] = FALSE;
  /* Filter criterion: Date: Date (log) */
  $handler->display->display_options['filters']['date_filter']['id'] = 'date_filter';
  $handler->display->display_options['filters']['date_filter']['table'] = 'log';
  $handler->display->display_options['filters']['date_filter']['field'] = 'date_filter';
  $handler->display->display_options['filters']['date_filter']['operator'] = '<';
  $handler->display->display_options['filters']['date_filter']['group'] = 1;
  $handler->display->display_options['filters']['date_filter']['expose']['operator_id'] = 'date_filter_op';
  $handler->display->display_options['filters']['date_filter']['expose']['label'] = 'Date';
  $handler->display->display_options['filters']['date_filter']['expose']['operator'] = 'date_filter_op';
  $handler->display->display_options['filters']['date_filter']['expose']['identifier'] = 'date_filter';
  $handler->display->display_options['filters']['date_filter']['default_date'] = 'today';
  $handler->display->display_options['filters']['date_filter']['year_range'] = '-10:+3';
  $handler->display->display_options['filters']['date_filter']['date_fields'] = array(
    'log.timestamp' => 'log.timestamp',
  );
  /* Filter criterion: Log: Done */
  $handler->display->display_options['filters']['done']['id'] = 'done';
  $handler->display->display_options['filters']['done']['table'] = 'log';
  $handler->display->display_options['filters']['done']['field'] = 'done';
  $handler->display->display_options['filters']['done']['value'] = '0';
  $handler->display->display_options['filters']['done']['group'] = 1;
  $handler->display->display_options['filters']['done']['expose']['operator_id'] = '';
  $handler->display->display_options['filters']['done']['expose']['label'] = 'Done';
  $handler->display->display_options['filters']['done']['expose']['operator'] = 'done_op';
  $handler->display->display_options['filters']['done']['expose']['identifier'] = 'done';

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
  $handler->display->display_options['path'] = 'farm/logs/csv';
  $handler->display->display_options['displays'] = array(
    'page' => 'page',
    'page_user' => 'page_user',
    'page_upcoming' => 'page_upcoming',
    'page_late' => 'page_late',
    'default' => 0,
    'block_upcoming' => 0,
    'block_late' => 0,
  );
  $translatables['farm_log'] = array(
    t('Master'),
    t('Logs'),
    t('more'),
    t('Apply'),
    t('Reset'),
    t('Sort by'),
    t('Asc'),
    t('Desc'),
    t('Items per page'),
    t('- All -'),
    t('Offset'),
    t('« first'),
    t('‹ previous'),
    t('next ›'),
    t('last »'),
    t('Displaying @start - @end of @total'),
    t('No logs found.'),
    t('Log'),
    t('- Choose an operation -'),
    t('Delete'),
    t('Done'),
    t('Not Done'),
    t('✔'),
    t('Log ID'),
    t('.'),
    t('Date'),
    t('Log name'),
    t('Assets'),
    t('Areas'),
    t('Log type'),
    t('Flags'),
    t('Categories'),
    t('Assigned to'),
    t('Start date'),
    t('End date'),
    t('Page'),
    t('Page: User Logs'),
    t('All'),
    t('Logs assigned to %1'),
    t('Search'),
    t('Page: Upcoming'),
    t('Upcoming tasks'),
    t('No tasks are currently planned.'),
    t('Log category'),
    t('Block: Upcoming'),
    t('Page: Late'),
    t('Late tasks'),
    t('Block: Late'),
    t('Data export'),
  );
  $export['farm_log'] = $view;

  return $export;
}
