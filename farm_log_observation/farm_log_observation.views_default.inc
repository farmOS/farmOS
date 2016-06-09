<?php
/**
 * @file
 * farm_log_observation.views_default.inc
 */

/**
 * Implements hook_views_default_views().
 */
function farm_log_observation_views_default_views() {
  $export = array();

  $view = new view();
  $view->name = 'farm_log_observation';
  $view->description = '';
  $view->tag = 'farm_log';
  $view->base_table = 'log';
  $view->human_name = 'Farm Log: Observation';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Observations';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['access']['perm'] = 'view any farm_observation log entities';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['exposed_form']['options']['reset_button'] = TRUE;
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['pager']['options']['items_per_page'] = '50';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['pager']['options']['id'] = '0';
  $handler->display->display_options['pager']['options']['quantity'] = '9';
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['columns'] = array(
    'views_bulk_operations' => 'views_bulk_operations',
    'id' => 'id',
    'done' => 'done',
    'field_farm_images' => 'field_farm_images',
    'timestamp' => 'timestamp',
    'name' => 'name',
    'field_farm_asset' => 'field_farm_asset',
    'field_farm_area' => 'field_farm_area',
    'field_farm_quantity' => 'field_farm_quantity',
    'field_farm_observation_type' => 'field_farm_observation_type',
  );
  $handler->display->display_options['style_options']['default'] = '-1';
  $handler->display->display_options['style_options']['info'] = array(
    'views_bulk_operations' => array(
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
    'done' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_images' => array(
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
    'field_farm_quantity' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_observation_type' => array(
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
  $handler->display->display_options['empty']['area_text_custom']['content'] = 'No observations found.';
  /* Field: Bulk operations: Log */
  $handler->display->display_options['fields']['views_bulk_operations']['id'] = 'views_bulk_operations';
  $handler->display->display_options['fields']['views_bulk_operations']['table'] = 'log';
  $handler->display->display_options['fields']['views_bulk_operations']['field'] = 'views_bulk_operations';
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['display_type'] = '1';
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['enable_select_all_pages'] = 1;
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['force_single'] = 0;
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['entity_load_capacity'] = '10';
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['skip_batching'] = 1;
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_operations'] = array(
    'action::log_clone_action' => array(
      'selected' => 1,
      'postpone_processing' => 0,
      'skip_confirmation' => 1,
      'override_label' => 0,
      'label' => '',
    ),
    'action::log_done_action' => array(
      'selected' => 1,
      'postpone_processing' => 0,
      'skip_confirmation' => 1,
      'override_label' => 1,
      'label' => 'Done',
    ),
    'action::log_undone_action' => array(
      'selected' => 1,
      'postpone_processing' => 0,
      'skip_confirmation' => 1,
      'override_label' => 1,
      'label' => 'Not Done',
    ),
    'action::log_reschedule_action' => array(
      'selected' => 1,
      'postpone_processing' => 0,
      'skip_confirmation' => 1,
      'override_label' => 0,
      'label' => '',
    ),
  );
  /* Field: Log: Log ID */
  $handler->display->display_options['fields']['id']['id'] = 'id';
  $handler->display->display_options['fields']['id']['table'] = 'log';
  $handler->display->display_options['fields']['id']['field'] = 'id';
  $handler->display->display_options['fields']['id']['exclude'] = TRUE;
  $handler->display->display_options['fields']['id']['separator'] = '';
  /* Field: Log: Done */
  $handler->display->display_options['fields']['done']['id'] = 'done';
  $handler->display->display_options['fields']['done']['table'] = 'log';
  $handler->display->display_options['fields']['done']['field'] = 'done';
  $handler->display->display_options['fields']['done']['type'] = 'custom';
  $handler->display->display_options['fields']['done']['type_custom_true'] = '✔';
  $handler->display->display_options['fields']['done']['not'] = 0;
  /* Field: Field: Photo(s) */
  $handler->display->display_options['fields']['field_farm_images']['id'] = 'field_farm_images';
  $handler->display->display_options['fields']['field_farm_images']['table'] = 'field_data_field_farm_images';
  $handler->display->display_options['fields']['field_farm_images']['field'] = 'field_farm_images';
  $handler->display->display_options['fields']['field_farm_images']['label'] = '';
  $handler->display->display_options['fields']['field_farm_images']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_farm_images']['click_sort_column'] = 'fid';
  $handler->display->display_options['fields']['field_farm_images']['settings'] = array(
    'image_style' => 'thumbnail',
    'image_link' => 'content',
  );
  $handler->display->display_options['fields']['field_farm_images']['delta_limit'] = '1';
  $handler->display->display_options['fields']['field_farm_images']['delta_offset'] = '0';
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
  $handler->display->display_options['fields']['name']['label'] = 'Observation';
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
  /* Field: Field: Quantity */
  $handler->display->display_options['fields']['field_farm_quantity']['id'] = 'field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['table'] = 'field_data_field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['field'] = 'field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['type'] = 'field_collection_fields';
  $handler->display->display_options['fields']['field_farm_quantity']['settings'] = array(
    'view_mode' => 'full',
  );
  /* Field: Log: Type */
  $handler->display->display_options['fields']['field_farm_observation_type']['id'] = 'field_farm_observation_type';
  $handler->display->display_options['fields']['field_farm_observation_type']['table'] = 'field_data_field_farm_observation_type';
  $handler->display->display_options['fields']['field_farm_observation_type']['field'] = 'field_farm_observation_type';
  $handler->display->display_options['fields']['field_farm_observation_type']['type'] = 'taxonomy_term_reference_plain';
  /* Sort criterion: Log: Timestamp */
  $handler->display->display_options['sorts']['timestamp']['id'] = 'timestamp';
  $handler->display->display_options['sorts']['timestamp']['table'] = 'log';
  $handler->display->display_options['sorts']['timestamp']['field'] = 'timestamp';
  $handler->display->display_options['sorts']['timestamp']['order'] = 'DESC';
  $handler->display->display_options['sorts']['timestamp']['exposed'] = TRUE;
  $handler->display->display_options['sorts']['timestamp']['expose']['label'] = 'Date';
  /* Sort criterion: Log: Log ID */
  $handler->display->display_options['sorts']['id']['id'] = 'id';
  $handler->display->display_options['sorts']['id']['table'] = 'log';
  $handler->display->display_options['sorts']['id']['field'] = 'id';
  $handler->display->display_options['sorts']['id']['order'] = 'DESC';
  /* Contextual filter: Log: Assets (field_farm_asset) */
  $handler->display->display_options['arguments']['field_farm_asset_target_id']['id'] = 'field_farm_asset_target_id';
  $handler->display->display_options['arguments']['field_farm_asset_target_id']['table'] = 'field_data_field_farm_asset';
  $handler->display->display_options['arguments']['field_farm_asset_target_id']['field'] = 'field_farm_asset_target_id';
  $handler->display->display_options['arguments']['field_farm_asset_target_id']['title_enable'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_asset_target_id']['title'] = '%1\'s observations';
  $handler->display->display_options['arguments']['field_farm_asset_target_id']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['field_farm_asset_target_id']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['field_farm_asset_target_id']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['field_farm_asset_target_id']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['arguments']['field_farm_asset_target_id']['specify_validation'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_asset_target_id']['validate']['type'] = 'php';
  $handler->display->display_options['arguments']['field_farm_asset_target_id']['validate_options']['code'] = '// Attempt to load the asset.
$farm_asset = farm_asset_load($argument);
if (!empty($farm_asset)) {
  // Set the title.
  $handler->validated_title = entity_label(\'farm_asset\', $farm_asset);
  return TRUE;
}';
  /* Contextual filter: Log: Areas (field_farm_area) */
  $handler->display->display_options['arguments']['field_farm_area_tid']['id'] = 'field_farm_area_tid';
  $handler->display->display_options['arguments']['field_farm_area_tid']['table'] = 'field_data_field_farm_area';
  $handler->display->display_options['arguments']['field_farm_area_tid']['field'] = 'field_farm_area_tid';
  $handler->display->display_options['arguments']['field_farm_area_tid']['title_enable'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_area_tid']['title'] = 'Observations in %2';
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
    'farm_observation' => 'farm_observation',
  );
  $handler->display->display_options['filters']['type']['group'] = 1;
  /* Filter criterion: Log: Type (field_farm_observation_type) */
  $handler->display->display_options['filters']['field_farm_observation_type_tid']['id'] = 'field_farm_observation_type_tid';
  $handler->display->display_options['filters']['field_farm_observation_type_tid']['table'] = 'field_data_field_farm_observation_type';
  $handler->display->display_options['filters']['field_farm_observation_type_tid']['field'] = 'field_farm_observation_type_tid';
  $handler->display->display_options['filters']['field_farm_observation_type_tid']['value'] = '';
  $handler->display->display_options['filters']['field_farm_observation_type_tid']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_observation_type_tid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_observation_type_tid']['expose']['operator_id'] = 'field_farm_observation_type_tid_op';
  $handler->display->display_options['filters']['field_farm_observation_type_tid']['expose']['label'] = 'Type';
  $handler->display->display_options['filters']['field_farm_observation_type_tid']['expose']['operator'] = 'field_farm_observation_type_tid_op';
  $handler->display->display_options['filters']['field_farm_observation_type_tid']['expose']['identifier'] = 'field_farm_observation_type_tid';
  $handler->display->display_options['filters']['field_farm_observation_type_tid']['type'] = 'select';
  $handler->display->display_options['filters']['field_farm_observation_type_tid']['vocabulary'] = 'farm_observation_types';
  $handler->display->display_options['filters']['field_farm_observation_type_tid']['hierarchy'] = 1;
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
  $handler = $view->new_display('page', 'Page', 'page_1');
  $handler->display->display_options['path'] = 'farm/logs/observations';
  $handler->display->display_options['menu']['type'] = 'normal';
  $handler->display->display_options['menu']['title'] = 'Observations';
  $handler->display->display_options['menu']['weight'] = '';
  $handler->display->display_options['menu']['name'] = 'farm';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;
  $translatables['farm_log_observation'] = array(
    t('Master'),
    t('Observations'),
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
    t('No observations found.'),
    t('Log'),
    t('- Choose an operation -'),
    t('Done'),
    t('Not Done'),
    t('Log ID'),
    t('.'),
    t('✔'),
    t('Date'),
    t('Observation'),
    t('Assets'),
    t('Areas'),
    t('Quantity'),
    t('Type'),
    t('All'),
    t('%1\'s observations'),
    t('Observations in %2'),
    t('Start date'),
    t('End date'),
    t('Page'),
  );
  $export['farm_log_observation'] = $view;

  return $export;
}
