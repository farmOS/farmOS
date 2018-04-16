<?php
/**
 * @file
 * farm_log_activity.views_default.inc
 */

/**
 * Implements hook_views_default_views().
 */
function farm_log_activity_views_default_views() {
  $export = array();

  $view = new view();
  $view->name = 'farm_log_activity';
  $view->description = '';
  $view->tag = 'farm_log';
  $view->base_table = 'log';
  $view->human_name = 'Farm Log: Activity';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Activities';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['access']['perm'] = 'view any farm_activity log entities';
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
    'field_farm_notes' => 'field_farm_notes',
    'field_farm_flags' => 'field_farm_flags',
    'field_farm_log_category' => 'field_farm_log_category',
    'field_farm_log_owner' => 'field_farm_log_owner',
  );
  $handler->display->display_options['style_options']['default'] = '-1';
  $handler->display->display_options['style_options']['info'] = array(
    'views_bulk_operations' => array(
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
    'field_farm_notes' => array(
      'sortable' => 0,
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
  $handler->display->display_options['empty']['area_text_custom']['content'] = 'No activities found.';
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
  /* Field: Log: Notes */
  $handler->display->display_options['fields']['field_farm_notes']['id'] = 'field_farm_notes';
  $handler->display->display_options['fields']['field_farm_notes']['table'] = 'field_data_field_farm_notes';
  $handler->display->display_options['fields']['field_farm_notes']['field'] = 'field_farm_notes';
  $handler->display->display_options['fields']['field_farm_notes']['alter']['trim_whitespace'] = TRUE;
  $handler->display->display_options['fields']['field_farm_notes']['alter']['max_length'] = '140';
  $handler->display->display_options['fields']['field_farm_notes']['alter']['strip_tags'] = TRUE;
  $handler->display->display_options['fields']['field_farm_notes']['alter']['trim'] = TRUE;
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
  /* Sort criterion: Log: Timestamp */
  $handler->display->display_options['sorts']['timestamp']['id'] = 'timestamp';
  $handler->display->display_options['sorts']['timestamp']['table'] = 'log';
  $handler->display->display_options['sorts']['timestamp']['field'] = 'timestamp';
  $handler->display->display_options['sorts']['timestamp']['order'] = 'DESC';
  $handler->display->display_options['sorts']['timestamp']['exposed'] = TRUE;
  $handler->display->display_options['sorts']['timestamp']['expose']['label'] = 'Date';
  /* Sort criterion: Log: Name */
  $handler->display->display_options['sorts']['name_1']['id'] = 'name_1';
  $handler->display->display_options['sorts']['name_1']['table'] = 'log';
  $handler->display->display_options['sorts']['name_1']['field'] = 'name';
  $handler->display->display_options['sorts']['name_1']['exposed'] = TRUE;
  $handler->display->display_options['sorts']['name_1']['expose']['label'] = 'Log name';
  /* Sort criterion: Log: Log ID */
  $handler->display->display_options['sorts']['id']['id'] = 'id';
  $handler->display->display_options['sorts']['id']['table'] = 'log';
  $handler->display->display_options['sorts']['id']['field'] = 'id';
  $handler->display->display_options['sorts']['id']['order'] = 'DESC';
  $handler->display->display_options['sorts']['id']['exposed'] = TRUE;
  $handler->display->display_options['sorts']['id']['expose']['label'] = 'Log ID';
  /* Contextual filter: Log: Assets (field_farm_asset) */
  $handler->display->display_options['arguments']['field_farm_asset_target_id']['id'] = 'field_farm_asset_target_id';
  $handler->display->display_options['arguments']['field_farm_asset_target_id']['table'] = 'field_data_field_farm_asset';
  $handler->display->display_options['arguments']['field_farm_asset_target_id']['field'] = 'field_farm_asset_target_id';
  $handler->display->display_options['arguments']['field_farm_asset_target_id']['title_enable'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_asset_target_id']['title'] = 'Activities of %1';
  $handler->display->display_options['arguments']['field_farm_asset_target_id']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['field_farm_asset_target_id']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['field_farm_asset_target_id']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['field_farm_asset_target_id']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['arguments']['field_farm_asset_target_id']['specify_validation'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_asset_target_id']['validate']['type'] = 'farm_asset';
  /* Contextual filter: Log: Areas (field_farm_area) */
  $handler->display->display_options['arguments']['field_farm_area_tid']['id'] = 'field_farm_area_tid';
  $handler->display->display_options['arguments']['field_farm_area_tid']['table'] = 'field_data_field_farm_area';
  $handler->display->display_options['arguments']['field_farm_area_tid']['field'] = 'field_farm_area_tid';
  $handler->display->display_options['arguments']['field_farm_area_tid']['title_enable'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_area_tid']['title'] = 'Activities in %2';
  $handler->display->display_options['arguments']['field_farm_area_tid']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['field_farm_area_tid']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['field_farm_area_tid']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['field_farm_area_tid']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['arguments']['field_farm_area_tid']['specify_validation'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_area_tid']['validate']['type'] = 'taxonomy_term';
  $handler->display->display_options['arguments']['field_farm_area_tid']['validate_options']['vocabularies'] = array(
    'farm_areas' => 'farm_areas',
  );
  /* Filter criterion: Log: Log type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'log';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'farm_activity' => 'farm_activity',
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
  $handler->display->display_options['filters']['name']['expose']['label'] = 'Log name';
  $handler->display->display_options['filters']['name']['expose']['operator'] = 'name_op';
  $handler->display->display_options['filters']['name']['expose']['identifier'] = 'name';
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
  $handler->display->display_options['path'] = 'farm/logs/activities';
  $handler->display->display_options['menu']['type'] = 'normal';
  $handler->display->display_options['menu']['title'] = 'Activities';
  $handler->display->display_options['menu']['weight'] = '';
  $handler->display->display_options['menu']['name'] = 'farm';
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
  $handler->display->display_options['path'] = 'farm/logs/activities/csv';
  $handler->display->display_options['displays'] = array(
    'page' => 'page',
    'default' => 0,
  );
  $translatables['farm_log_activity'] = array(
    t('Master'),
    t('Activities'),
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
    t('No activities found.'),
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
    t('Notes'),
    t('Flags'),
    t('Categories'),
    t('Assigned to'),
    t('All'),
    t('Activities of %1'),
    t('Activities in %2'),
    t('Start date'),
    t('End date'),
    t('Page'),
    t('Data export'),
  );
  $export['farm_log_activity'] = $view;

  return $export;
}
