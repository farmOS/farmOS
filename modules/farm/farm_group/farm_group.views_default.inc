<?php
/**
 * @file
 * farm_group.views_default.inc
 */

/**
 * Implements hook_views_default_views().
 */
function farm_group_views_default_views() {
  $export = array();

  $view = new view();
  $view->name = 'farm_groups';
  $view->description = '';
  $view->tag = 'farm_livestock';
  $view->base_table = 'farm_asset';
  $view->human_name = 'Farm Groups';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Groups';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['access']['perm'] = 'view any group farm assets';
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
    'field_farm_images' => 'field_farm_images',
    'name' => 'name',
    'field_farm_description' => 'field_farm_description',
    'field_farm_move_to' => 'field_farm_move_to',
    'active' => 'active',
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
    'field_farm_images' => array(
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
    'field_farm_description' => array(
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
    'active' => array(
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
  $handler->display->display_options['empty']['area_text_custom']['content'] = 'No groups found.';
  /* Relationship: Farm asset: Latest movement */
  $handler->display->display_options['relationships']['latest_movement']['id'] = 'latest_movement';
  $handler->display->display_options['relationships']['latest_movement']['table'] = 'farm_asset';
  $handler->display->display_options['relationships']['latest_movement']['field'] = 'latest_movement';
  /* Field: Bulk operations: Farm asset */
  $handler->display->display_options['fields']['views_bulk_operations']['id'] = 'views_bulk_operations';
  $handler->display->display_options['fields']['views_bulk_operations']['table'] = 'views_entity_farm_asset';
  $handler->display->display_options['fields']['views_bulk_operations']['field'] = 'views_bulk_operations';
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['display_type'] = '1';
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['enable_select_all_pages'] = 1;
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['row_clickable'] = 1;
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['force_single'] = 0;
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['entity_load_capacity'] = '10';
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_operations'] = array(
    'action::farm_movement_asset_move_action' => array(
      'selected' => 1,
      'skip_confirmation' => 1,
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
  /* Field: Field: Photos */
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
  /* Field: Farm asset: Name */
  $handler->display->display_options['fields']['name']['id'] = 'name';
  $handler->display->display_options['fields']['name']['table'] = 'farm_asset';
  $handler->display->display_options['fields']['name']['field'] = 'name';
  $handler->display->display_options['fields']['name']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['name']['alter']['path'] = 'farm/asset/[id]';
  /* Field: Farm asset: Description */
  $handler->display->display_options['fields']['field_farm_description']['id'] = 'field_farm_description';
  $handler->display->display_options['fields']['field_farm_description']['table'] = 'field_data_field_farm_description';
  $handler->display->display_options['fields']['field_farm_description']['field'] = 'field_farm_description';
  $handler->display->display_options['fields']['field_farm_description']['alter']['trim_whitespace'] = TRUE;
  $handler->display->display_options['fields']['field_farm_description']['alter']['max_length'] = '140';
  $handler->display->display_options['fields']['field_farm_description']['alter']['strip_tags'] = TRUE;
  $handler->display->display_options['fields']['field_farm_description']['alter']['trim'] = TRUE;
  /* Field: Field collection item: Movement to */
  $handler->display->display_options['fields']['field_farm_move_to']['id'] = 'field_farm_move_to';
  $handler->display->display_options['fields']['field_farm_move_to']['table'] = 'field_data_field_farm_move_to';
  $handler->display->display_options['fields']['field_farm_move_to']['field'] = 'field_farm_move_to';
  $handler->display->display_options['fields']['field_farm_move_to']['relationship'] = 'latest_movement';
  $handler->display->display_options['fields']['field_farm_move_to']['label'] = 'Location';
  /* Field: Farm asset: Active */
  $handler->display->display_options['fields']['active']['id'] = 'active';
  $handler->display->display_options['fields']['active']['table'] = 'farm_asset';
  $handler->display->display_options['fields']['active']['field'] = 'active';
  $handler->display->display_options['fields']['active']['not'] = 0;
  /* Sort criterion: Farm asset: Name */
  $handler->display->display_options['sorts']['name']['id'] = 'name';
  $handler->display->display_options['sorts']['name']['table'] = 'farm_asset';
  $handler->display->display_options['sorts']['name']['field'] = 'name';
  $handler->display->display_options['sorts']['name']['exposed'] = TRUE;
  $handler->display->display_options['sorts']['name']['expose']['label'] = 'Name';
  /* Contextual filter: Field collection item: Movement to (field_farm_move_to) */
  $handler->display->display_options['arguments']['field_farm_move_to_tid']['id'] = 'field_farm_move_to_tid';
  $handler->display->display_options['arguments']['field_farm_move_to_tid']['table'] = 'field_data_field_farm_move_to';
  $handler->display->display_options['arguments']['field_farm_move_to_tid']['field'] = 'field_farm_move_to_tid';
  $handler->display->display_options['arguments']['field_farm_move_to_tid']['relationship'] = 'latest_movement';
  $handler->display->display_options['arguments']['field_farm_move_to_tid']['title_enable'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_move_to_tid']['title'] = 'Groups in %1';
  $handler->display->display_options['arguments']['field_farm_move_to_tid']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['field_farm_move_to_tid']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['field_farm_move_to_tid']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['field_farm_move_to_tid']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['arguments']['field_farm_move_to_tid']['specify_validation'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_move_to_tid']['validate']['type'] = 'taxonomy_term';
  $handler->display->display_options['arguments']['field_farm_move_to_tid']['validate_options']['vocabularies'] = array(
    'farm_areas' => 'farm_areas',
  );
  /* Filter criterion: Farm asset: Farm asset type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'farm_asset';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'group' => 'group',
  );
  $handler->display->display_options['filters']['type']['group'] = 1;
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
  /* Filter criterion: Field collection item: Movement to (field_farm_move_to) */
  $handler->display->display_options['filters']['field_farm_move_to_tid']['id'] = 'field_farm_move_to_tid';
  $handler->display->display_options['filters']['field_farm_move_to_tid']['table'] = 'field_data_field_farm_move_to';
  $handler->display->display_options['filters']['field_farm_move_to_tid']['field'] = 'field_farm_move_to_tid';
  $handler->display->display_options['filters']['field_farm_move_to_tid']['relationship'] = 'latest_movement';
  $handler->display->display_options['filters']['field_farm_move_to_tid']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_move_to_tid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_move_to_tid']['expose']['operator_id'] = 'field_farm_move_to_tid_op';
  $handler->display->display_options['filters']['field_farm_move_to_tid']['expose']['label'] = 'Location';
  $handler->display->display_options['filters']['field_farm_move_to_tid']['expose']['operator'] = 'field_farm_move_to_tid_op';
  $handler->display->display_options['filters']['field_farm_move_to_tid']['expose']['identifier'] = 'field_farm_move_to_tid';
  $handler->display->display_options['filters']['field_farm_move_to_tid']['expose']['multiple'] = TRUE;
  $handler->display->display_options['filters']['field_farm_move_to_tid']['type'] = 'select';
  $handler->display->display_options['filters']['field_farm_move_to_tid']['vocabulary'] = 'farm_areas';
  $handler->display->display_options['filters']['field_farm_move_to_tid']['hierarchy'] = 1;
  /* Filter criterion: Farm asset: Active */
  $handler->display->display_options['filters']['active']['id'] = 'active';
  $handler->display->display_options['filters']['active']['table'] = 'farm_asset';
  $handler->display->display_options['filters']['active']['field'] = 'active';
  $handler->display->display_options['filters']['active']['value'] = '1';
  $handler->display->display_options['filters']['active']['group'] = 1;
  $handler->display->display_options['filters']['active']['exposed'] = TRUE;
  $handler->display->display_options['filters']['active']['expose']['operator_id'] = '';
  $handler->display->display_options['filters']['active']['expose']['label'] = 'Active';
  $handler->display->display_options['filters']['active']['expose']['operator'] = 'active_op';
  $handler->display->display_options['filters']['active']['expose']['identifier'] = 'active';

  /* Display: Page */
  $handler = $view->new_display('page', 'Page', 'page');
  $handler->display->display_options['path'] = 'farm/assets/groups/list';
  $handler->display->display_options['menu']['type'] = 'default tab';
  $handler->display->display_options['menu']['title'] = 'Groups';
  $handler->display->display_options['menu']['weight'] = '-10';
  $handler->display->display_options['menu']['name'] = 'farm';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;
  $handler->display->display_options['tab_options']['type'] = 'normal';
  $handler->display->display_options['tab_options']['title'] = 'Groups';
  $handler->display->display_options['tab_options']['weight'] = '10';
  $handler->display->display_options['tab_options']['name'] = 'farm';

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
  $handler->display->display_options['path'] = 'farm/assets/groups/csv';
  $handler->display->display_options['displays'] = array(
    'page' => 'page',
    'default' => 0,
  );
  $translatables['farm_groups'] = array(
    t('Master'),
    t('Groups'),
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
    t('No groups found.'),
    t('Latest movement'),
    t('Farm asset'),
    t('- Choose an operation -'),
    t('Asset ID'),
    t('.'),
    t('Name'),
    t('Description'),
    t('Location'),
    t('Active'),
    t('All'),
    t('Groups in %1'),
    t('Page'),
    t('Data export'),
  );
  $export['farm_groups'] = $view;

  return $export;
}
