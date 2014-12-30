<?php
/**
 * @file
 * farm_equipment.views_default.inc
 */

/**
 * Implements hook_views_default_views().
 */
function farm_equipment_views_default_views() {
  $export = array();

  $view = new view();
  $view->name = 'farm_equipment';
  $view->description = '';
  $view->tag = 'farm_livestock';
  $view->base_table = 'farm_asset';
  $view->human_name = 'Farm Equipment';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Equipment';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['access']['perm'] = 'view any equipment farm assets';
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
    'name' => 'name',
    'field_farm_move_to' => 'field_farm_move_to',
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
    'name' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_move_to' => array(
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
  $handler->display->display_options['empty']['area_text_custom']['content'] = 'No equipment found.';
  /* Relationship: Farm asset: Latest movement */
  $handler->display->display_options['relationships']['latest_movement']['id'] = 'latest_movement';
  $handler->display->display_options['relationships']['latest_movement']['table'] = 'farm_asset';
  $handler->display->display_options['relationships']['latest_movement']['field'] = 'latest_movement';
  /* Field: Bulk operations: Farm asset */
  $handler->display->display_options['fields']['views_bulk_operations']['id'] = 'views_bulk_operations';
  $handler->display->display_options['fields']['views_bulk_operations']['table'] = 'farm_asset';
  $handler->display->display_options['fields']['views_bulk_operations']['field'] = 'views_bulk_operations';
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['display_type'] = '1';
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['enable_select_all_pages'] = 1;
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['force_single'] = 0;
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['entity_load_capacity'] = '10';
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_operations'] = array(
    'action::views_bulk_operations_delete_item' => array(
      'selected' => 0,
      'postpone_processing' => 0,
      'skip_confirmation' => 0,
      'override_label' => 0,
      'label' => '',
    ),
    'action::views_bulk_operations_delete_revision' => array(
      'selected' => 0,
      'postpone_processing' => 0,
      'skip_confirmation' => 0,
      'override_label' => 0,
      'label' => '',
    ),
    'action::views_bulk_operations_script_action' => array(
      'selected' => 0,
      'postpone_processing' => 0,
      'skip_confirmation' => 0,
      'override_label' => 0,
      'label' => '',
    ),
    'action::views_bulk_operations_modify_action' => array(
      'selected' => 0,
      'postpone_processing' => 0,
      'skip_confirmation' => 0,
      'override_label' => 0,
      'label' => '',
      'settings' => array(
        'show_all_tokens' => 1,
        'display_values' => array(
          '_all_' => '_all_',
        ),
      ),
    ),
    'action::farm_log_asset_move_action' => array(
      'selected' => 1,
      'postpone_processing' => 0,
      'skip_confirmation' => 1,
      'override_label' => 0,
      'label' => '',
    ),
    'action::views_bulk_operations_argument_selector_action' => array(
      'selected' => 0,
      'skip_confirmation' => 0,
      'override_label' => 0,
      'label' => '',
      'settings' => array(
        'url' => '',
      ),
    ),
    'action::system_send_email_action' => array(
      'selected' => 0,
      'postpone_processing' => 0,
      'skip_confirmation' => 0,
      'override_label' => 0,
      'label' => '',
    ),
    'action::panelizer_set_status_action' => array(
      'selected' => 0,
      'postpone_processing' => 0,
      'skip_confirmation' => 0,
      'override_label' => 0,
      'label' => '',
    ),
  );
  /* Field: Farm asset: Farm asset ID */
  $handler->display->display_options['fields']['id']['id'] = 'id';
  $handler->display->display_options['fields']['id']['table'] = 'farm_asset';
  $handler->display->display_options['fields']['id']['field'] = 'id';
  $handler->display->display_options['fields']['id']['exclude'] = TRUE;
  /* Field: Farm asset: Name */
  $handler->display->display_options['fields']['name']['id'] = 'name';
  $handler->display->display_options['fields']['name']['table'] = 'farm_asset';
  $handler->display->display_options['fields']['name']['field'] = 'name';
  $handler->display->display_options['fields']['name']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['name']['alter']['path'] = 'farm/asset/[id]';
  /* Field: Log: To */
  $handler->display->display_options['fields']['field_farm_move_to']['id'] = 'field_farm_move_to';
  $handler->display->display_options['fields']['field_farm_move_to']['table'] = 'field_data_field_farm_move_to';
  $handler->display->display_options['fields']['field_farm_move_to']['field'] = 'field_farm_move_to';
  $handler->display->display_options['fields']['field_farm_move_to']['relationship'] = 'latest_movement';
  $handler->display->display_options['fields']['field_farm_move_to']['label'] = 'Location';
  /* Sort criterion: Farm asset: Name */
  $handler->display->display_options['sorts']['name']['id'] = 'name';
  $handler->display->display_options['sorts']['name']['table'] = 'farm_asset';
  $handler->display->display_options['sorts']['name']['field'] = 'name';
  $handler->display->display_options['sorts']['name']['exposed'] = TRUE;
  $handler->display->display_options['sorts']['name']['expose']['label'] = 'Name';
  /* Filter criterion: Farm asset: Farm asset type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'farm_asset';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'equipment' => 'equipment',
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

  /* Display: All equipment (Page) */
  $handler = $view->new_display('page', 'All equipment (Page)', 'page');
  $handler->display->display_options['path'] = 'admin/farm/equipment/list';
  $handler->display->display_options['menu']['type'] = 'default tab';
  $handler->display->display_options['menu']['title'] = 'List';
  $handler->display->display_options['menu']['weight'] = '0';
  $handler->display->display_options['menu']['name'] = 'management';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;
  $handler->display->display_options['tab_options']['type'] = 'tab';
  $handler->display->display_options['tab_options']['title'] = 'Equipment';
  $handler->display->display_options['tab_options']['weight'] = '10';

  /* Display: Equipment by area (Page) */
  $handler = $view->new_display('page', 'Equipment by area (Page)', 'page_1');
  $handler->display->display_options['defaults']['arguments'] = FALSE;
  /* Contextual filter: Log: To (field_farm_move_to) */
  $handler->display->display_options['arguments']['field_farm_move_to_tid']['id'] = 'field_farm_move_to_tid';
  $handler->display->display_options['arguments']['field_farm_move_to_tid']['table'] = 'field_data_field_farm_move_to';
  $handler->display->display_options['arguments']['field_farm_move_to_tid']['field'] = 'field_farm_move_to_tid';
  $handler->display->display_options['arguments']['field_farm_move_to_tid']['relationship'] = 'latest_movement';
  $handler->display->display_options['arguments']['field_farm_move_to_tid']['default_action'] = 'not found';
  $handler->display->display_options['arguments']['field_farm_move_to_tid']['title_enable'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_move_to_tid']['title'] = 'Equipment in %1';
  $handler->display->display_options['arguments']['field_farm_move_to_tid']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['field_farm_move_to_tid']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['field_farm_move_to_tid']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['field_farm_move_to_tid']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['arguments']['field_farm_move_to_tid']['specify_validation'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_move_to_tid']['validate']['type'] = 'taxonomy_term';
  $handler->display->display_options['arguments']['field_farm_move_to_tid']['validate_options']['vocabularies'] = array(
    'farm_areas' => 'farm_areas',
  );
  $handler->display->display_options['path'] = 'farm/area/%/equipment';
  $export['farm_equipment'] = $view;

  $view = new view();
  $view->name = 'farm_log_maintenance';
  $view->description = '';
  $view->tag = 'farm_log';
  $view->base_table = 'log';
  $view->human_name = 'Farm Log: Maintenance';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Maintenance';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['access']['perm'] = 'view any farm_maintenance log entities';
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
    'field_farm_move_from' => 'field_farm_move_from',
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
    'field_farm_move_from' => array(
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
  $handler->display->display_options['empty']['area_text_custom']['content'] = 'No maintenance logs found.';
  /* Field: Log: Log ID */
  $handler->display->display_options['fields']['id']['id'] = 'id';
  $handler->display->display_options['fields']['id']['table'] = 'log';
  $handler->display->display_options['fields']['id']['field'] = 'id';
  $handler->display->display_options['fields']['id']['exclude'] = TRUE;
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
  $handler->display->display_options['fields']['name']['label'] = 'Maintenance';
  $handler->display->display_options['fields']['name']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['name']['alter']['path'] = 'log/[id]';
  /* Field: Log: Equipment */
  $handler->display->display_options['fields']['field_farm_equipment']['id'] = 'field_farm_equipment';
  $handler->display->display_options['fields']['field_farm_equipment']['table'] = 'field_data_field_farm_equipment';
  $handler->display->display_options['fields']['field_farm_equipment']['field'] = 'field_farm_equipment';
  $handler->display->display_options['fields']['field_farm_equipment']['settings'] = array(
    'link' => 1,
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
    'farm_maintenance' => 'farm_maintenance',
  );
  $handler->display->display_options['filters']['type']['group'] = 1;
  /* Filter criterion: Field: Date (field_farm_date) */
  $handler->display->display_options['filters']['field_farm_date_value']['id'] = 'field_farm_date_value';
  $handler->display->display_options['filters']['field_farm_date_value']['table'] = 'field_data_field_farm_date';
  $handler->display->display_options['filters']['field_farm_date_value']['field'] = 'field_farm_date_value';
  $handler->display->display_options['filters']['field_farm_date_value']['operator'] = '>=';
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
  $handler->display->display_options['filters']['field_farm_date_value']['year_range'] = '-10:+3';
  /* Filter criterion: Field: Date (field_farm_date) */
  $handler->display->display_options['filters']['field_farm_date_value_1']['id'] = 'field_farm_date_value_1';
  $handler->display->display_options['filters']['field_farm_date_value_1']['table'] = 'field_data_field_farm_date';
  $handler->display->display_options['filters']['field_farm_date_value_1']['field'] = 'field_farm_date_value';
  $handler->display->display_options['filters']['field_farm_date_value_1']['operator'] = '<=';
  $handler->display->display_options['filters']['field_farm_date_value_1']['group'] = 1;
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
  $handler->display->display_options['filters']['field_farm_date_value_1']['year_range'] = '-10:+3';

  /* Display: All maintenance (Page) */
  $handler = $view->new_display('page', 'All maintenance (Page)', 'page_1');
  $handler->display->display_options['path'] = 'admin/farm/plan/maintenance';
  $handler->display->display_options['menu']['type'] = 'tab';
  $handler->display->display_options['menu']['title'] = 'Maintenance';
  $handler->display->display_options['menu']['weight'] = '0';
  $handler->display->display_options['menu']['name'] = 'management';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;

  /* Display: Maintenance by asset (Page) */
  $handler = $view->new_display('page', 'Maintenance by asset (Page)', 'page_2');
  $handler->display->display_options['defaults']['arguments'] = FALSE;
  /* Contextual filter: Log: Equipment (field_farm_equipment) */
  $handler->display->display_options['arguments']['field_farm_equipment_target_id']['id'] = 'field_farm_equipment_target_id';
  $handler->display->display_options['arguments']['field_farm_equipment_target_id']['table'] = 'field_data_field_farm_equipment';
  $handler->display->display_options['arguments']['field_farm_equipment_target_id']['field'] = 'field_farm_equipment_target_id';
  $handler->display->display_options['arguments']['field_farm_equipment_target_id']['default_action'] = 'not found';
  $handler->display->display_options['arguments']['field_farm_equipment_target_id']['title_enable'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_equipment_target_id']['title'] = '%1\'s maintenance';
  $handler->display->display_options['arguments']['field_farm_equipment_target_id']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['field_farm_equipment_target_id']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['field_farm_equipment_target_id']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['field_farm_equipment_target_id']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['arguments']['field_farm_equipment_target_id']['specify_validation'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_equipment_target_id']['validate']['type'] = 'php';
  $handler->display->display_options['arguments']['field_farm_equipment_target_id']['validate_options']['code'] = '// Attempt to load the asset.
$farm_asset = farm_asset_load($argument);
if (!empty($farm_asset)) {
  // Set the title.
  $handler->validated_title = entity_label(\'farm_asset\', $farm_asset);
  return TRUE;
}';
  $handler->display->display_options['path'] = 'farm/asset/%/maintenance';

  /* Display: Maintenance by asset (Content pane) */
  $handler = $view->new_display('panel_pane', 'Maintenance by asset (Content pane)', 'panel_pane_1');
  $handler->display->display_options['defaults']['use_more'] = FALSE;
  $handler->display->display_options['use_more'] = TRUE;
  $handler->display->display_options['defaults']['use_more_always'] = FALSE;
  $handler->display->display_options['defaults']['use_more_always'] = FALSE;
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['defaults']['use_more_text'] = FALSE;
  $handler->display->display_options['defaults']['link_display'] = FALSE;
  $handler->display->display_options['link_display'] = 'page_2';
  $handler->display->display_options['defaults']['pager'] = FALSE;
  $handler->display->display_options['pager']['type'] = 'some';
  $handler->display->display_options['pager']['options']['items_per_page'] = '5';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['defaults']['sorts'] = FALSE;
  /* Sort criterion: Field: Date (field_farm_date) */
  $handler->display->display_options['sorts']['field_farm_date_value']['id'] = 'field_farm_date_value';
  $handler->display->display_options['sorts']['field_farm_date_value']['table'] = 'field_data_field_farm_date';
  $handler->display->display_options['sorts']['field_farm_date_value']['field'] = 'field_farm_date_value';
  $handler->display->display_options['sorts']['field_farm_date_value']['order'] = 'DESC';
  $handler->display->display_options['sorts']['field_farm_date_value']['expose']['label'] = 'Date';
  /* Sort criterion: Log: Log ID */
  $handler->display->display_options['sorts']['id']['id'] = 'id';
  $handler->display->display_options['sorts']['id']['table'] = 'log';
  $handler->display->display_options['sorts']['id']['field'] = 'id';
  $handler->display->display_options['sorts']['id']['order'] = 'DESC';
  $handler->display->display_options['defaults']['arguments'] = FALSE;
  /* Contextual filter: Log: Equipment (field_farm_equipment) */
  $handler->display->display_options['arguments']['field_farm_equipment_target_id']['id'] = 'field_farm_equipment_target_id';
  $handler->display->display_options['arguments']['field_farm_equipment_target_id']['table'] = 'field_data_field_farm_equipment';
  $handler->display->display_options['arguments']['field_farm_equipment_target_id']['field'] = 'field_farm_equipment_target_id';
  $handler->display->display_options['arguments']['field_farm_equipment_target_id']['default_action'] = 'empty';
  $handler->display->display_options['arguments']['field_farm_equipment_target_id']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['field_farm_equipment_target_id']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['field_farm_equipment_target_id']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['field_farm_equipment_target_id']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['arguments']['field_farm_equipment_target_id']['specify_validation'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_equipment_target_id']['validate']['type'] = 'php';
  $handler->display->display_options['arguments']['field_farm_equipment_target_id']['validate_options']['code'] = '// Attempt to load the asset.
$farm_asset = farm_asset_load($argument);
if (!empty($farm_asset)) {
  // Set the title.
  $handler->validated_title = entity_label(\'farm_asset\', $farm_asset);
  return TRUE;
}';
  $handler->display->display_options['defaults']['filter_groups'] = FALSE;
  $handler->display->display_options['defaults']['filters'] = FALSE;
  /* Filter criterion: Log: Log type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'log';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'farm_maintenance' => 'farm_maintenance',
  );
  $handler->display->display_options['filters']['type']['group'] = 1;
  $handler->display->display_options['argument_input'] = array(
    'field_farm_equipment_target_id' => array(
      'type' => 'context',
      'context' => 'entity:farm_asset.id',
      'context_optional' => 0,
      'panel' => '0',
      'fixed' => '',
      'label' => 'Log: Equipment (field_farm_equipment)',
    ),
  );
  $handler->display->display_options['link_to_view'] = '0';
  $export['farm_log_maintenance'] = $view;

  return $export;
}
