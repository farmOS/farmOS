<?php
/**
 * @file
 * farm_asset_views.views_default.inc
 */

/**
 * Implements hook_views_default_views().
 */
function farm_asset_views_views_default_views() {
  $export = array();

  $view = new view();
  $view->name = 'farm_asset';
  $view->description = '';
  $view->tag = 'default';
  $view->base_table = 'farm_asset';
  $view->human_name = 'Farm Assets';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Assets';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['access']['perm'] = 'view farm assets';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['exposed_form']['options']['reset_button'] = TRUE;
  $handler->display->display_options['exposed_form']['options']['autosubmit_hide'] = FALSE;
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['pager']['options']['items_per_page'] = '50';
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['columns'] = array(
    'views_bulk_operations' => 'views_bulk_operations',
    'id' => 'id',
    'name' => 'name',
    'type' => 'type',
    'field_farm_group' => 'field_farm_group',
    'field_farm_move_to' => 'field_farm_move_to',
    'archived' => 'archived',
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
    'field_farm_group' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_move_to' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'archived' => array(
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
  /* Field: Bulk operations: Farm asset */
  $handler->display->display_options['fields']['views_bulk_operations']['id'] = 'views_bulk_operations';
  $handler->display->display_options['fields']['views_bulk_operations']['table'] = 'views_entity_farm_asset';
  $handler->display->display_options['fields']['views_bulk_operations']['field'] = 'views_bulk_operations';
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['display_type'] = '1';
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['enable_select_all_pages'] = 1;
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['row_clickable'] = 1;
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['force_single'] = 0;
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['entity_load_capacity'] = '10';
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['skip_batching'] = 1;
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_operations'] = array(
    'action::farm_asset_archive_action' => array(
      'selected' => 1,
      'postpone_processing' => 0,
      'skip_confirmation' => 0,
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
    'action::farm_group_asset_membership_action' => array(
      'selected' => 1,
      'skip_confirmation' => 1,
      'skip_permission_check' => 0,
      'override_label' => 0,
      'label' => '',
    ),
    'action::farm_movement_asset_move_action' => array(
      'selected' => 1,
      'skip_confirmation' => 1,
      'skip_permission_check' => 0,
      'override_label' => 0,
      'label' => '',
    ),
    'action::farm_asset_unarchive_action' => array(
      'selected' => 1,
      'postpone_processing' => 0,
      'skip_confirmation' => 0,
      'skip_permission_check' => 0,
      'override_label' => 0,
      'label' => '',
    ),
  );
  /* Field: Farm asset: Farm asset ID */
  $handler->display->display_options['fields']['id']['id'] = 'id';
  $handler->display->display_options['fields']['id']['table'] = 'farm_asset';
  $handler->display->display_options['fields']['id']['field'] = 'id';
  $handler->display->display_options['fields']['id']['label'] = 'Asset ID';
  $handler->display->display_options['fields']['id']['separator'] = '';
  /* Field: Farm asset: Name */
  $handler->display->display_options['fields']['name']['id'] = 'name';
  $handler->display->display_options['fields']['name']['table'] = 'farm_asset';
  $handler->display->display_options['fields']['name']['field'] = 'name';
  $handler->display->display_options['fields']['name']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['name']['alter']['path'] = 'farm/asset/[id]';
  /* Field: Farm asset: Farm asset type */
  $handler->display->display_options['fields']['type']['id'] = 'type';
  $handler->display->display_options['fields']['type']['table'] = 'farm_asset';
  $handler->display->display_options['fields']['type']['field'] = 'type';
  $handler->display->display_options['fields']['type']['label'] = 'Asset type';
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
  /* Field: Field collection item: Movement to */
  $handler->display->display_options['fields']['field_farm_move_to']['id'] = 'field_farm_move_to';
  $handler->display->display_options['fields']['field_farm_move_to']['table'] = 'field_data_field_farm_move_to';
  $handler->display->display_options['fields']['field_farm_move_to']['field'] = 'field_farm_move_to';
  $handler->display->display_options['fields']['field_farm_move_to']['relationship'] = 'latest_movement';
  $handler->display->display_options['fields']['field_farm_move_to']['label'] = 'Location';
  $handler->display->display_options['fields']['field_farm_move_to']['delta_offset'] = '0';
  /* Field: Farm asset: Archived */
  $handler->display->display_options['fields']['archived']['id'] = 'archived';
  $handler->display->display_options['fields']['archived']['table'] = 'farm_asset';
  $handler->display->display_options['fields']['archived']['field'] = 'archived';
  $handler->display->display_options['fields']['archived']['date_format'] = 'custom';
  $handler->display->display_options['fields']['archived']['custom_date_format'] = 'M j Y';
  $handler->display->display_options['fields']['archived']['second_date_format'] = 'long';
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
  $handler->display->display_options['sorts']['type']['expose']['label'] = 'Type';
  /* Sort criterion: Farm asset: Archived */
  $handler->display->display_options['sorts']['archived']['id'] = 'archived';
  $handler->display->display_options['sorts']['archived']['table'] = 'farm_asset';
  $handler->display->display_options['sorts']['archived']['field'] = 'archived';
  $handler->display->display_options['sorts']['archived']['order'] = 'DESC';
  $handler->display->display_options['sorts']['archived']['exposed'] = TRUE;
  $handler->display->display_options['sorts']['archived']['expose']['label'] = 'Archived date';
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
  /* Filter criterion: Farm asset: Farm asset type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'farm_asset';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['group'] = 1;
  $handler->display->display_options['filters']['type']['exposed'] = TRUE;
  $handler->display->display_options['filters']['type']['expose']['operator_id'] = 'type_op';
  $handler->display->display_options['filters']['type']['expose']['label'] = 'Asset type';
  $handler->display->display_options['filters']['type']['expose']['operator'] = 'type_op';
  $handler->display->display_options['filters']['type']['expose']['identifier'] = 'type';
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

  /* Display: Page */
  $handler = $view->new_display('page', 'Page', 'page');
  $handler->display->display_options['path'] = 'farm/assets';
  $handler->display->display_options['menu']['type'] = 'normal';
  $handler->display->display_options['menu']['title'] = 'Assets';
  $handler->display->display_options['menu']['weight'] = '0';
  $handler->display->display_options['menu']['name'] = 'farm';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;
  $translatables['farm_asset'] = array(
    t('Master'),
    t('Assets'),
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
    t('No assets found.'),
    t('Latest movement'),
    t('Group membership'),
    t('Farm asset entity referenced from field_farm_group'),
    t('Farm asset'),
    t('- Choose an operation -'),
    t('Delete'),
    t('Asset ID'),
    t('.'),
    t('Name'),
    t('Asset type'),
    t('Group'),
    t('Location'),
    t('Archived'),
    t('Type'),
    t('Archived date'),
    t('Page'),
  );
  $export['farm_asset'] = $view;

  $view = new view();
  $view->name = 'farm_asset_entityreference_view';
  $view->description = '';
  $view->tag = 'default';
  $view->base_table = 'farm_asset';
  $view->human_name = 'Farm Asset Entityreference View';
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
  $handler->display->display_options['header']['area_text_custom']['content'] = 'Select the asset(s) you would like to reference and click "Submit". Use the filters to narrow down the list.';
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
  $handler->display->display_options['fields']['entityreference_view_widget']['label'] = 'Asset name';
  $handler->display->display_options['fields']['entityreference_view_widget']['ervw']['force_single'] = 0;
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
  /* Contextual filter: Farm asset: Farm asset ID */
  $handler->display->display_options['arguments']['id']['id'] = 'id';
  $handler->display->display_options['arguments']['id']['table'] = 'farm_asset';
  $handler->display->display_options['arguments']['id']['field'] = 'id';
  $handler->display->display_options['arguments']['id']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['id']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['id']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['id']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['arguments']['id']['not'] = TRUE;
  /* Contextual filter: Farm asset: Farm asset type */
  $handler->display->display_options['arguments']['type']['id'] = 'type';
  $handler->display->display_options['arguments']['type']['table'] = 'farm_asset';
  $handler->display->display_options['arguments']['type']['field'] = 'type';
  $handler->display->display_options['arguments']['type']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['type']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['type']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['type']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['arguments']['type']['limit'] = '0';
  /* Filter criterion: Farm asset: Farm asset type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'farm_asset';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['group'] = 1;
  $handler->display->display_options['filters']['type']['exposed'] = TRUE;
  $handler->display->display_options['filters']['type']['expose']['operator_id'] = 'type_op';
  $handler->display->display_options['filters']['type']['expose']['label'] = 'Asset type';
  $handler->display->display_options['filters']['type']['expose']['operator'] = 'type_op';
  $handler->display->display_options['filters']['type']['expose']['identifier'] = 'type';
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
  $handler->display->display_options['title'] = 'Select assets';
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
  $translatables['farm_asset_entityreference_view'] = array(
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
    t('Select the asset(s) you would like to reference and click "Submit". Use the filters to narrow down the list.'),
    t('No assets found.'),
    t('Latest movement'),
    t('Group membership'),
    t('Farm asset entity referenced from field_farm_group'),
    t('Asset name'),
    t('Asset type'),
    t('Location'),
    t('Group'),
    t('Name'),
    t('All'),
    t('Archived'),
    t('Entity Reference View Widget'),
    t('Select assets'),
  );
  $export['farm_asset_entityreference_view'] = $view;

  return $export;
}
