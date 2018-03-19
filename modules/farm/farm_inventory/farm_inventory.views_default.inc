<?php
/**
 * @file
 * farm_inventory.views_default.inc
 */

/**
 * Implements hook_views_default_views().
 */
function farm_inventory_views_default_views() {
  $export = array();

  $view = new view();
  $view->name = 'farm_inventory_entityreference_view';
  $view->description = '';
  $view->tag = 'default';
  $view->base_table = 'farm_asset';
  $view->human_name = 'Farm Inventory Entityreference View';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['use_ajax'] = TRUE;
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['access']['perm'] = 'view farm assets';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['exposed_form']['options']['reset_button'] = TRUE;
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['pager']['options']['items_per_page'] = '25';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['pager']['options']['id'] = '0';
  $handler->display->display_options['pager']['options']['quantity'] = '9';
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['columns'] = array(
    'name' => 'name',
    'type' => 'type',
  );
  $handler->display->display_options['style_options']['default'] = '-1';
  $handler->display->display_options['style_options']['info'] = array(
    'name' => array(
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
  );
  $handler->display->display_options['style_options']['empty_table'] = TRUE;
  /* Header: Global: Unfiltered text */
  $handler->display->display_options['header']['area_text_custom']['id'] = 'area_text_custom';
  $handler->display->display_options['header']['area_text_custom']['table'] = 'views';
  $handler->display->display_options['header']['area_text_custom']['field'] = 'area_text_custom';
  $handler->display->display_options['header']['area_text_custom']['content'] = 'Select the asset you would like to reference and click "Submit". Use the filters to narrow down the list.';
  /* No results behavior: Global: Unfiltered text */
  $handler->display->display_options['empty']['area_text_custom']['id'] = 'area_text_custom';
  $handler->display->display_options['empty']['area_text_custom']['table'] = 'views';
  $handler->display->display_options['empty']['area_text_custom']['field'] = 'area_text_custom';
  $handler->display->display_options['empty']['area_text_custom']['empty'] = TRUE;
  $handler->display->display_options['empty']['area_text_custom']['content'] = 'No assets found.';
  /* Relationship: Farm asset: Latest movement */
  $handler->display->display_options['relationships']['latest_movement']['id'] = 'latest_movement';
  $handler->display->display_options['relationships']['latest_movement']['table'] = 'farm_asset';
  $handler->display->display_options['relationships']['latest_movement']['field'] = 'latest_movement';
  /* Relationship: Farm asset: Group membership */
  $handler->display->display_options['relationships']['membership']['id'] = 'membership';
  $handler->display->display_options['relationships']['membership']['table'] = 'farm_asset';
  $handler->display->display_options['relationships']['membership']['field'] = 'membership';
  /* Relationship: Entity Reference: Referenced Entity */
  $handler->display->display_options['relationships']['field_farm_group_target_id']['id'] = 'field_farm_group_target_id';
  $handler->display->display_options['relationships']['field_farm_group_target_id']['table'] = 'field_data_field_farm_group';
  $handler->display->display_options['relationships']['field_farm_group_target_id']['field'] = 'field_farm_group_target_id';
  $handler->display->display_options['relationships']['field_farm_group_target_id']['relationship'] = 'membership';
  /* Field: Entity Reference View Widget Checkbox: Farm asset */
  $handler->display->display_options['fields']['entityreference_view_widget']['id'] = 'entityreference_view_widget';
  $handler->display->display_options['fields']['entityreference_view_widget']['table'] = 'farm_asset';
  $handler->display->display_options['fields']['entityreference_view_widget']['field'] = 'entityreference_view_widget';
  $handler->display->display_options['fields']['entityreference_view_widget']['label'] = '';
  $handler->display->display_options['fields']['entityreference_view_widget']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['entityreference_view_widget']['ervw']['force_single'] = 1;
  $handler->display->display_options['fields']['entityreference_view_widget']['ervw']['label_display'] = 'after';
  /* Field: Farm asset: Farm asset type */
  $handler->display->display_options['fields']['type']['id'] = 'type';
  $handler->display->display_options['fields']['type']['table'] = 'farm_asset';
  $handler->display->display_options['fields']['type']['field'] = 'type';
  $handler->display->display_options['fields']['type']['label'] = 'Asset type';
  /* Field: Field collection item: Movement to */
  $handler->display->display_options['fields']['field_farm_move_to']['id'] = 'field_farm_move_to';
  $handler->display->display_options['fields']['field_farm_move_to']['table'] = 'field_data_field_farm_move_to';
  $handler->display->display_options['fields']['field_farm_move_to']['field'] = 'field_farm_move_to';
  $handler->display->display_options['fields']['field_farm_move_to']['relationship'] = 'latest_movement';
  $handler->display->display_options['fields']['field_farm_move_to']['label'] = 'Location';
  /* Field: Field collection item: Group */
  $handler->display->display_options['fields']['field_farm_group']['id'] = 'field_farm_group';
  $handler->display->display_options['fields']['field_farm_group']['table'] = 'field_data_field_farm_group';
  $handler->display->display_options['fields']['field_farm_group']['field'] = 'field_farm_group';
  $handler->display->display_options['fields']['field_farm_group']['relationship'] = 'membership';
  $handler->display->display_options['fields']['field_farm_group']['settings'] = array(
    'bypass_access' => 0,
    'link' => 1,
  );
  $handler->display->display_options['fields']['field_farm_group']['delta_offset'] = '0';
  /* Sort criterion: Farm asset: Name */
  $handler->display->display_options['sorts']['name']['id'] = 'name';
  $handler->display->display_options['sorts']['name']['table'] = 'farm_asset';
  $handler->display->display_options['sorts']['name']['field'] = 'name';
  $handler->display->display_options['sorts']['name']['exposed'] = TRUE;
  $handler->display->display_options['sorts']['name']['expose']['label'] = 'Name';
  /* Sort criterion: Farm asset: Farm asset type */
  $handler->display->display_options['sorts']['type']['id'] = 'type';
  $handler->display->display_options['sorts']['type']['table'] = 'farm_asset';
  $handler->display->display_options['sorts']['type']['field'] = 'type';
  $handler->display->display_options['sorts']['type']['exposed'] = TRUE;
  $handler->display->display_options['sorts']['type']['expose']['label'] = 'Asset type';
  /* Sort criterion: Farm asset: Farm asset ID */
  $handler->display->display_options['sorts']['id']['id'] = 'id';
  $handler->display->display_options['sorts']['id']['table'] = 'farm_asset';
  $handler->display->display_options['sorts']['id']['field'] = 'id';
  $handler->display->display_options['sorts']['id']['order'] = 'DESC';
  $handler->display->display_options['sorts']['id']['exposed'] = TRUE;
  $handler->display->display_options['sorts']['id']['expose']['label'] = 'Asset ID';
  /* Contextual filter: Farm asset: Farm asset ID */
  $handler->display->display_options['arguments']['id']['id'] = 'id';
  $handler->display->display_options['arguments']['id']['table'] = 'farm_asset';
  $handler->display->display_options['arguments']['id']['field'] = 'id';
  $handler->display->display_options['arguments']['id']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['id']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['id']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['id']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['arguments']['id']['not'] = TRUE;
  /* Filter criterion: Inventory asset type: Inventory enabled */
  $handler->display->display_options['filters']['enabled']['id'] = 'enabled';
  $handler->display->display_options['filters']['enabled']['table'] = 'farm_inventory_asset_type';
  $handler->display->display_options['filters']['enabled']['field'] = 'enabled';
  $handler->display->display_options['filters']['enabled']['value'] = '1';
  $handler->display->display_options['filters']['enabled']['group'] = 1;
  /* Filter criterion: Farm asset: Asset type (inventory) */
  $handler->display->display_options['filters']['inventory_asset_type']['id'] = 'inventory_asset_type';
  $handler->display->display_options['filters']['inventory_asset_type']['table'] = 'farm_asset';
  $handler->display->display_options['filters']['inventory_asset_type']['field'] = 'inventory_asset_type';
  $handler->display->display_options['filters']['inventory_asset_type']['group'] = 1;
  $handler->display->display_options['filters']['inventory_asset_type']['exposed'] = TRUE;
  $handler->display->display_options['filters']['inventory_asset_type']['expose']['operator_id'] = 'inventory_asset_type_op';
  $handler->display->display_options['filters']['inventory_asset_type']['expose']['label'] = 'Asset type';
  $handler->display->display_options['filters']['inventory_asset_type']['expose']['operator'] = 'inventory_asset_type_op';
  $handler->display->display_options['filters']['inventory_asset_type']['expose']['identifier'] = 'inventory_asset_type';
  /* Filter criterion: Farm asset: Name */
  $handler->display->display_options['filters']['name']['id'] = 'name';
  $handler->display->display_options['filters']['name']['table'] = 'farm_asset';
  $handler->display->display_options['filters']['name']['field'] = 'name';
  $handler->display->display_options['filters']['name']['operator'] = 'contains';
  $handler->display->display_options['filters']['name']['group'] = 1;
  $handler->display->display_options['filters']['name']['exposed'] = TRUE;
  $handler->display->display_options['filters']['name']['expose']['operator_id'] = 'name_op';
  $handler->display->display_options['filters']['name']['expose']['label'] = 'Name';
  $handler->display->display_options['filters']['name']['expose']['operator'] = 'name_op';
  $handler->display->display_options['filters']['name']['expose']['identifier'] = 'name';
  /* Filter criterion: Farm asset: Name */
  $handler->display->display_options['filters']['name_1']['id'] = 'name_1';
  $handler->display->display_options['filters']['name_1']['table'] = 'farm_asset';
  $handler->display->display_options['filters']['name_1']['field'] = 'name';
  $handler->display->display_options['filters']['name_1']['relationship'] = 'field_farm_group_target_id';
  $handler->display->display_options['filters']['name_1']['operator'] = 'contains';
  $handler->display->display_options['filters']['name_1']['group'] = 1;
  $handler->display->display_options['filters']['name_1']['exposed'] = TRUE;
  $handler->display->display_options['filters']['name_1']['expose']['operator_id'] = 'name_1_op';
  $handler->display->display_options['filters']['name_1']['expose']['label'] = 'Group';
  $handler->display->display_options['filters']['name_1']['expose']['operator'] = 'name_1_op';
  $handler->display->display_options['filters']['name_1']['expose']['identifier'] = 'name_1';
  /* Filter criterion: Field collection item: Movement to (field_farm_move_to) */
  $handler->display->display_options['filters']['field_farm_move_to_tid']['id'] = 'field_farm_move_to_tid';
  $handler->display->display_options['filters']['field_farm_move_to_tid']['table'] = 'field_data_field_farm_move_to';
  $handler->display->display_options['filters']['field_farm_move_to_tid']['field'] = 'field_farm_move_to_tid';
  $handler->display->display_options['filters']['field_farm_move_to_tid']['relationship'] = 'latest_movement';
  $handler->display->display_options['filters']['field_farm_move_to_tid']['value'] = '';
  $handler->display->display_options['filters']['field_farm_move_to_tid']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_move_to_tid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_move_to_tid']['expose']['operator_id'] = 'field_farm_move_to_tid_op';
  $handler->display->display_options['filters']['field_farm_move_to_tid']['expose']['label'] = 'Location';
  $handler->display->display_options['filters']['field_farm_move_to_tid']['expose']['operator'] = 'field_farm_move_to_tid_op';
  $handler->display->display_options['filters']['field_farm_move_to_tid']['expose']['identifier'] = 'field_farm_move_to_tid';
  $handler->display->display_options['filters']['field_farm_move_to_tid']['vocabulary'] = 'farm_areas';
  /* Filter criterion: Farm asset: Archived (boolean) */
  $handler->display->display_options['filters']['archived_boolean']['id'] = 'archived_boolean';
  $handler->display->display_options['filters']['archived_boolean']['table'] = 'farm_asset';
  $handler->display->display_options['filters']['archived_boolean']['field'] = 'archived_boolean';
  $handler->display->display_options['filters']['archived_boolean']['value'] = '0';
  $handler->display->display_options['filters']['archived_boolean']['exposed'] = TRUE;
  $handler->display->display_options['filters']['archived_boolean']['expose']['operator_id'] = '';
  $handler->display->display_options['filters']['archived_boolean']['expose']['label'] = 'Archived';
  $handler->display->display_options['filters']['archived_boolean']['expose']['operator'] = 'archived_boolean_op';
  $handler->display->display_options['filters']['archived_boolean']['expose']['identifier'] = 'archived_boolean';

  /* Display: Entity Reference View Widget */
  $handler = $view->new_display('entityreference_view_widget', 'Entity Reference View Widget', 'entityreference_view_widget');
  $handler->display->display_options['defaults']['title'] = FALSE;
  $handler->display->display_options['title'] = 'Select asset';
  $handler->display->display_options['defaults']['use_ajax'] = FALSE;
  $handler->display->display_options['defaults']['style_plugin'] = FALSE;
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['columns'] = array(
    'entityreference_view_widget' => 'entityreference_view_widget',
    'type' => 'type',
    'field_farm_move_to' => 'field_farm_move_to',
    'field_farm_group' => 'field_farm_group',
  );
  $handler->display->display_options['style_options']['default'] = '-1';
  $handler->display->display_options['style_options']['info'] = array(
    'entityreference_view_widget' => array(
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
    'field_farm_move_to' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_group' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
  );
  $handler->display->display_options['style_options']['empty_table'] = TRUE;
  $handler->display->display_options['defaults']['style_options'] = FALSE;
  $handler->display->display_options['defaults']['row_plugin'] = FALSE;
  $handler->display->display_options['defaults']['row_options'] = FALSE;
  $translatables['farm_inventory_entityreference_view'] = array(
    t('Master'),
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
    t('Select the asset you would like to reference and click "Submit". Use the filters to narrow down the list.'),
    t('No assets found.'),
    t('Latest movement'),
    t('Group membership'),
    t('Farm asset entity referenced from field_farm_group'),
    t('Asset type'),
    t('Location'),
    t('Group'),
    t('Name'),
    t('Asset ID'),
    t('All'),
    t('Archived'),
    t('Entity Reference View Widget'),
    t('Select asset'),
  );
  $export['farm_inventory_entityreference_view'] = $view;

  $view = new view();
  $view->name = 'farm_inventory_log';
  $view->description = '';
  $view->tag = 'farm_inventory';
  $view->base_table = 'log';
  $view->human_name = 'Farm inventory logs';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Logs of inventory adjustments';
  $handler->display->display_options['use_more_always'] = FALSE;
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
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['columns'] = array(
    'views_bulk_operations' => 'views_bulk_operations',
    'done' => 'done',
    'id' => 'id',
    'timestamp' => 'timestamp',
    'name' => 'name',
    'field_farm_inventory_asset' => 'field_farm_inventory_asset',
    'field_farm_inventory_value' => 'field_farm_inventory_value',
    'type' => 'type',
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
    'field_farm_inventory_asset' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_inventory_value' => array(
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
  /* Header: Global: Unfiltered text */
  $handler->display->display_options['header']['area_text_custom']['id'] = 'area_text_custom';
  $handler->display->display_options['header']['area_text_custom']['table'] = 'views';
  $handler->display->display_options['header']['area_text_custom']['field'] = 'area_text_custom';
  $handler->display->display_options['header']['area_text_custom']['empty'] = TRUE;
  $handler->display->display_options['header']['area_text_custom']['content'] = 'This lists all logs that include asset inventory adjustments. If a log contains more than one inventory adjustment, it will be listed more than once in this list. Multiple log types are included in this list, and are specified in the "Log type" column.';
  /* No results behavior: Global: Unfiltered text */
  $handler->display->display_options['empty']['area_text_custom']['id'] = 'area_text_custom';
  $handler->display->display_options['empty']['area_text_custom']['table'] = 'views';
  $handler->display->display_options['empty']['area_text_custom']['field'] = 'area_text_custom';
  $handler->display->display_options['empty']['area_text_custom']['empty'] = TRUE;
  $handler->display->display_options['empty']['area_text_custom']['content'] = 'No logs of inventory adjustments found.';
  /* Relationship: Log: Inventory adjustments (field_farm_inventory) */
  $handler->display->display_options['relationships']['field_farm_inventory_value']['id'] = 'field_farm_inventory_value';
  $handler->display->display_options['relationships']['field_farm_inventory_value']['table'] = 'field_data_field_farm_inventory';
  $handler->display->display_options['relationships']['field_farm_inventory_value']['field'] = 'field_farm_inventory_value';
  $handler->display->display_options['relationships']['field_farm_inventory_value']['label'] = 'field_farm_inventory';
  $handler->display->display_options['relationships']['field_farm_inventory_value']['required'] = TRUE;
  $handler->display->display_options['relationships']['field_farm_inventory_value']['delta'] = '-1';
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
  /* Field: Field collection item: Asset */
  $handler->display->display_options['fields']['field_farm_inventory_asset']['id'] = 'field_farm_inventory_asset';
  $handler->display->display_options['fields']['field_farm_inventory_asset']['table'] = 'field_data_field_farm_inventory_asset';
  $handler->display->display_options['fields']['field_farm_inventory_asset']['field'] = 'field_farm_inventory_asset';
  $handler->display->display_options['fields']['field_farm_inventory_asset']['relationship'] = 'field_farm_inventory_value';
  $handler->display->display_options['fields']['field_farm_inventory_asset']['settings'] = array(
    'bypass_access' => 0,
    'link' => 1,
  );
  /* Field: Field collection item: Value */
  $handler->display->display_options['fields']['field_farm_inventory_value']['id'] = 'field_farm_inventory_value';
  $handler->display->display_options['fields']['field_farm_inventory_value']['table'] = 'field_data_field_farm_inventory_value';
  $handler->display->display_options['fields']['field_farm_inventory_value']['field'] = 'field_farm_inventory_value';
  $handler->display->display_options['fields']['field_farm_inventory_value']['relationship'] = 'field_farm_inventory_value';
  $handler->display->display_options['fields']['field_farm_inventory_value']['label'] = 'Adjustment value';
  $handler->display->display_options['fields']['field_farm_inventory_value']['type'] = 'fraction_decimal';
  $handler->display->display_options['fields']['field_farm_inventory_value']['settings'] = array(
    'precision' => '0',
    'auto_precision' => 1,
    'prefix_suffix' => 1,
  );
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
  /* Sort criterion: Log: Timestamp */
  $handler->display->display_options['sorts']['timestamp']['id'] = 'timestamp';
  $handler->display->display_options['sorts']['timestamp']['table'] = 'log';
  $handler->display->display_options['sorts']['timestamp']['field'] = 'timestamp';
  $handler->display->display_options['sorts']['timestamp']['order'] = 'DESC';
  $handler->display->display_options['sorts']['timestamp']['exposed'] = TRUE;
  $handler->display->display_options['sorts']['timestamp']['expose']['label'] = 'Date';
  /* Sort criterion: Log: Name */
  $handler->display->display_options['sorts']['name']['id'] = 'name';
  $handler->display->display_options['sorts']['name']['table'] = 'log';
  $handler->display->display_options['sorts']['name']['field'] = 'name';
  $handler->display->display_options['sorts']['name']['exposed'] = TRUE;
  $handler->display->display_options['sorts']['name']['expose']['label'] = 'Log name';
  /* Sort criterion: Log: Log type */
  $handler->display->display_options['sorts']['type']['id'] = 'type';
  $handler->display->display_options['sorts']['type']['table'] = 'log';
  $handler->display->display_options['sorts']['type']['field'] = 'type';
  $handler->display->display_options['sorts']['type']['exposed'] = TRUE;
  $handler->display->display_options['sorts']['type']['expose']['label'] = 'Log type';
  /* Sort criterion: Log: Log ID */
  $handler->display->display_options['sorts']['id']['id'] = 'id';
  $handler->display->display_options['sorts']['id']['table'] = 'log';
  $handler->display->display_options['sorts']['id']['field'] = 'id';
  $handler->display->display_options['sorts']['id']['order'] = 'DESC';
  $handler->display->display_options['sorts']['id']['exposed'] = TRUE;
  $handler->display->display_options['sorts']['id']['expose']['label'] = 'Log ID';
  /* Contextual filter: Field collection item: Asset (field_farm_inventory_asset) */
  $handler->display->display_options['arguments']['field_farm_inventory_asset_target_id']['id'] = 'field_farm_inventory_asset_target_id';
  $handler->display->display_options['arguments']['field_farm_inventory_asset_target_id']['table'] = 'field_data_field_farm_inventory_asset';
  $handler->display->display_options['arguments']['field_farm_inventory_asset_target_id']['field'] = 'field_farm_inventory_asset_target_id';
  $handler->display->display_options['arguments']['field_farm_inventory_asset_target_id']['relationship'] = 'field_farm_inventory_value';
  $handler->display->display_options['arguments']['field_farm_inventory_asset_target_id']['title_enable'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_inventory_asset_target_id']['title'] = '%1\'s inventory adjustments';
  $handler->display->display_options['arguments']['field_farm_inventory_asset_target_id']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['field_farm_inventory_asset_target_id']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['field_farm_inventory_asset_target_id']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['field_farm_inventory_asset_target_id']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['arguments']['field_farm_inventory_asset_target_id']['specify_validation'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_inventory_asset_target_id']['validate']['type'] = 'farm_asset';
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

  /* Display: Page */
  $handler = $view->new_display('page', 'Page', 'page');
  $handler->display->display_options['path'] = 'farm/logs/inventory';
  $handler->display->display_options['menu']['type'] = 'normal';
  $handler->display->display_options['menu']['title'] = 'Logs of inventory adjustments';
  $handler->display->display_options['menu']['weight'] = '100';
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
  $handler->display->display_options['path'] = 'farm/logs/inventory/csv';
  $handler->display->display_options['displays'] = array(
    'page' => 'page',
    'default' => 0,
  );
  $translatables['farm_inventory_log'] = array(
    t('Master'),
    t('Logs of inventory adjustments'),
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
    t('This lists all logs that include asset inventory adjustments. If a log contains more than one inventory adjustment, it will be listed more than once in this list. Multiple log types are included in this list, and are specified in the "Log type" column.'),
    t('No logs of inventory adjustments found.'),
    t('field_farm_inventory'),
    t('Log'),
    t('- Choose an operation -'),
    t('Done'),
    t('Not Done'),
    t('✔'),
    t('Log ID'),
    t('.'),
    t('Date'),
    t('Log name'),
    t('Asset'),
    t('Adjustment value'),
    t('Log type'),
    t('Flags'),
    t('Categories'),
    t('Assigned to'),
    t('All'),
    t('%1\'s inventory adjustments'),
    t('Start date'),
    t('End date'),
    t('Page'),
    t('Data export'),
  );
  $export['farm_inventory_log'] = $view;

  return $export;
}
