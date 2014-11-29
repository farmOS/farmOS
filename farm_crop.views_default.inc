<?php
/**
 * @file
 * farm_crop.views_default.inc
 */

/**
 * Implements hook_views_default_views().
 */
function farm_crop_views_default_views() {
  $export = array();

  $view = new view();
  $view->name = 'farm_crops';
  $view->description = '';
  $view->tag = 'farm_crop';
  $view->base_table = 'taxonomy_term_data';
  $view->human_name = 'Farm Crops';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Crops';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['access']['perm'] = 'edit terms in farm_crops';
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
    'tid' => 'tid',
    'name' => 'name',
    'field_farm_crop_family' => 'field_farm_crop_family',
    'field_farm_transplant_days' => 'field_farm_transplant_days',
    'field_farm_maturity_days' => 'field_farm_maturity_days',
    'field_farm_companions' => 'field_farm_companions',
    'id' => 'id',
  );
  $handler->display->display_options['style_options']['default'] = '-1';
  $handler->display->display_options['style_options']['info'] = array(
    'tid' => array(
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
    'field_farm_crop_family' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_transplant_days' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_maturity_days' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_companions' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'id' => array(
      'sortable' => 0,
      'default_sort_order' => 'desc',
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
  $handler->display->display_options['empty']['area_text_custom']['content'] = 'No crops found.';
  /* Field: Taxonomy term: Term ID */
  $handler->display->display_options['fields']['tid']['id'] = 'tid';
  $handler->display->display_options['fields']['tid']['table'] = 'taxonomy_term_data';
  $handler->display->display_options['fields']['tid']['field'] = 'tid';
  $handler->display->display_options['fields']['tid']['exclude'] = TRUE;
  /* Field: Taxonomy term: Name */
  $handler->display->display_options['fields']['name']['id'] = 'name';
  $handler->display->display_options['fields']['name']['table'] = 'taxonomy_term_data';
  $handler->display->display_options['fields']['name']['field'] = 'name';
  $handler->display->display_options['fields']['name']['label'] = 'Crop Name';
  $handler->display->display_options['fields']['name']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['name']['alter']['ellipsis'] = FALSE;
  $handler->display->display_options['fields']['name']['link_to_taxonomy'] = TRUE;
  /* Field: Taxonomy term: Crop family */
  $handler->display->display_options['fields']['field_farm_crop_family']['id'] = 'field_farm_crop_family';
  $handler->display->display_options['fields']['field_farm_crop_family']['table'] = 'field_data_field_farm_crop_family';
  $handler->display->display_options['fields']['field_farm_crop_family']['field'] = 'field_farm_crop_family';
  $handler->display->display_options['fields']['field_farm_crop_family']['type'] = 'taxonomy_term_reference_plain';
  /* Field: Taxonomy term: Days to transplant */
  $handler->display->display_options['fields']['field_farm_transplant_days']['id'] = 'field_farm_transplant_days';
  $handler->display->display_options['fields']['field_farm_transplant_days']['table'] = 'field_data_field_farm_transplant_days';
  $handler->display->display_options['fields']['field_farm_transplant_days']['field'] = 'field_farm_transplant_days';
  $handler->display->display_options['fields']['field_farm_transplant_days']['settings'] = array(
    'thousand_separator' => ' ',
    'prefix_suffix' => 1,
  );
  /* Field: Taxonomy term: Days to maturity */
  $handler->display->display_options['fields']['field_farm_maturity_days']['id'] = 'field_farm_maturity_days';
  $handler->display->display_options['fields']['field_farm_maturity_days']['table'] = 'field_data_field_farm_maturity_days';
  $handler->display->display_options['fields']['field_farm_maturity_days']['field'] = 'field_farm_maturity_days';
  $handler->display->display_options['fields']['field_farm_maturity_days']['settings'] = array(
    'thousand_separator' => ' ',
    'prefix_suffix' => 1,
  );
  /* Field: Taxonomy term: Companions */
  $handler->display->display_options['fields']['field_farm_companions']['id'] = 'field_farm_companions';
  $handler->display->display_options['fields']['field_farm_companions']['table'] = 'field_data_field_farm_companions';
  $handler->display->display_options['fields']['field_farm_companions']['field'] = 'field_farm_companions';
  $handler->display->display_options['fields']['field_farm_companions']['type'] = 'taxonomy_term_reference_plain';
  $handler->display->display_options['fields']['field_farm_companions']['delta_offset'] = '0';
  /* Sort criterion: Taxonomy term: Name */
  $handler->display->display_options['sorts']['name']['id'] = 'name';
  $handler->display->display_options['sorts']['name']['table'] = 'taxonomy_term_data';
  $handler->display->display_options['sorts']['name']['field'] = 'name';
  $handler->display->display_options['sorts']['name']['exposed'] = TRUE;
  $handler->display->display_options['sorts']['name']['expose']['label'] = 'Name';
  /* Sort criterion: Taxonomy term: Crop family (field_farm_crop_family) */
  $handler->display->display_options['sorts']['field_farm_crop_family_tid']['id'] = 'field_farm_crop_family_tid';
  $handler->display->display_options['sorts']['field_farm_crop_family_tid']['table'] = 'field_data_field_farm_crop_family';
  $handler->display->display_options['sorts']['field_farm_crop_family_tid']['field'] = 'field_farm_crop_family_tid';
  $handler->display->display_options['sorts']['field_farm_crop_family_tid']['exposed'] = TRUE;
  $handler->display->display_options['sorts']['field_farm_crop_family_tid']['expose']['label'] = 'Family';
  /* Filter criterion: Taxonomy term: Vocabulary */
  $handler->display->display_options['filters']['vid']['id'] = 'vid';
  $handler->display->display_options['filters']['vid']['table'] = 'taxonomy_term_data';
  $handler->display->display_options['filters']['vid']['field'] = 'vid';
  $handler->display->display_options['filters']['vid']['value'] = array(
    3 => '3',
  );
  $handler->display->display_options['filters']['vid']['group'] = 1;
  /* Filter criterion: Taxonomy term: Crop family (field_farm_crop_family) */
  $handler->display->display_options['filters']['field_farm_crop_family_tid']['id'] = 'field_farm_crop_family_tid';
  $handler->display->display_options['filters']['field_farm_crop_family_tid']['table'] = 'field_data_field_farm_crop_family';
  $handler->display->display_options['filters']['field_farm_crop_family_tid']['field'] = 'field_farm_crop_family_tid';
  $handler->display->display_options['filters']['field_farm_crop_family_tid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_crop_family_tid']['expose']['operator_id'] = 'field_farm_crop_family_tid_op';
  $handler->display->display_options['filters']['field_farm_crop_family_tid']['expose']['label'] = 'Crop family';
  $handler->display->display_options['filters']['field_farm_crop_family_tid']['expose']['operator'] = 'field_farm_crop_family_tid_op';
  $handler->display->display_options['filters']['field_farm_crop_family_tid']['expose']['identifier'] = 'field_farm_crop_family_tid';
  $handler->display->display_options['filters']['field_farm_crop_family_tid']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    30037204 => 0,
    200153887 => 0,
    125843996 => 0,
    173712484 => 0,
    196317508 => 0,
    146437786 => 0,
  );
  $handler->display->display_options['filters']['field_farm_crop_family_tid']['type'] = 'select';
  $handler->display->display_options['filters']['field_farm_crop_family_tid']['vocabulary'] = 'farm_crop_families';
  $handler->display->display_options['filters']['field_farm_crop_family_tid']['hierarchy'] = 1;
  /* Filter criterion: Taxonomy term: Name */
  $handler->display->display_options['filters']['name']['id'] = 'name';
  $handler->display->display_options['filters']['name']['table'] = 'taxonomy_term_data';
  $handler->display->display_options['filters']['name']['field'] = 'name';
  $handler->display->display_options['filters']['name']['operator'] = 'contains';
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

  /* Display: All crops (Page) */
  $handler = $view->new_display('page', 'All crops (Page)', 'page');
  $handler->display->display_options['path'] = 'admin/farm/crops/list';
  $handler->display->display_options['menu']['type'] = 'default tab';
  $handler->display->display_options['menu']['title'] = 'Crops';
  $handler->display->display_options['menu']['weight'] = '-10';
  $handler->display->display_options['menu']['name'] = 'management';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;
  $handler->display->display_options['tab_options']['type'] = 'tab';
  $handler->display->display_options['tab_options']['title'] = 'Crops';
  $handler->display->display_options['tab_options']['weight'] = '10';
  $export['farm_crops'] = $view;

  $view = new view();
  $view->name = 'farm_log_harvest';
  $view->description = '';
  $view->tag = 'farm_crop';
  $view->base_table = 'log';
  $view->human_name = 'Farm Log: Harvest';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Harvests';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['access']['perm'] = 'edit any farm_harvest log entities';
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
    'field_farm_quantity' => array(
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
  $handler->display->display_options['empty']['area_text_custom']['content'] = 'No harvests found.';
  /* Field: Log: Log ID */
  $handler->display->display_options['fields']['id']['id'] = 'id';
  $handler->display->display_options['fields']['id']['table'] = 'log';
  $handler->display->display_options['fields']['id']['field'] = 'id';
  $handler->display->display_options['fields']['id']['label'] = 'Harvest ID';
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
  $handler->display->display_options['fields']['name']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['name']['alter']['path'] = 'log/[id]';
  /* Field: Log: Quantity */
  $handler->display->display_options['fields']['field_farm_quantity']['id'] = 'field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['table'] = 'field_data_field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['field'] = 'field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['relationship'] = 'field_farm_harvest_list_value';
  $handler->display->display_options['fields']['field_farm_quantity']['type'] = 'field_collection_fields';
  $handler->display->display_options['fields']['field_farm_quantity']['settings'] = array(
    'view_mode' => 'full',
  );
  /* Field: Log: Planting */
  $handler->display->display_options['fields']['field_farm_planting']['id'] = 'field_farm_planting';
  $handler->display->display_options['fields']['field_farm_planting']['table'] = 'field_data_field_farm_planting';
  $handler->display->display_options['fields']['field_farm_planting']['field'] = 'field_farm_planting';
  $handler->display->display_options['fields']['field_farm_planting']['settings'] = array(
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
    'farm_harvest' => 'farm_harvest',
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
  $handler->display->display_options['filters']['field_farm_date_value_1']['default_date'] = 'now';
  $handler->display->display_options['filters']['field_farm_date_value_1']['year_range'] = '-10:+3';

  /* Display: All harvests (Page) */
  $handler = $view->new_display('page', 'All harvests (Page)', 'page');
  $handler->display->display_options['path'] = 'admin/farm/plan/harvests';
  $handler->display->display_options['menu']['type'] = 'tab';
  $handler->display->display_options['menu']['title'] = 'Harvests';
  $handler->display->display_options['menu']['weight'] = '0';
  $handler->display->display_options['menu']['name'] = 'management';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;
  $handler->display->display_options['tab_options']['type'] = 'normal';
  $handler->display->display_options['tab_options']['title'] = 'Logs';
  $handler->display->display_options['tab_options']['weight'] = '0';
  $handler->display->display_options['tab_options']['name'] = 'management';

  /* Display: Harvests by planting (Page) */
  $handler = $view->new_display('page', 'Harvests by planting (Page)', 'page_1');
  $handler->display->display_options['defaults']['arguments'] = FALSE;
  /* Contextual filter: Log: Planting (field_farm_planting) */
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['id'] = 'field_farm_planting_target_id';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['table'] = 'field_data_field_farm_planting';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['field'] = 'field_farm_planting_target_id';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['default_action'] = 'not found';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['title_enable'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['title'] = 'Harvests of %1';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['specify_validation'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['validate']['type'] = 'php';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['validate_options']['code'] = '// Attempt to load the asset.
$farm_asset = farm_asset_load($argument);
if (!empty($farm_asset)) {
  // Set the title.
  $handler->validated_title = entity_label(\'farm_asset\', $farm_asset);
  return TRUE;
}';
  $handler->display->display_options['path'] = 'farm/asset/%/harvests';

  /* Display: Harvests by planting (Content pane) */
  $handler = $view->new_display('panel_pane', 'Harvests by planting (Content pane)', 'panel_pane_1');
  $handler->display->display_options['defaults']['use_more'] = FALSE;
  $handler->display->display_options['use_more'] = TRUE;
  $handler->display->display_options['defaults']['use_more_always'] = FALSE;
  $handler->display->display_options['defaults']['use_more_always'] = FALSE;
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['defaults']['use_more_text'] = FALSE;
  $handler->display->display_options['defaults']['pager'] = FALSE;
  $handler->display->display_options['pager']['type'] = 'some';
  $handler->display->display_options['pager']['options']['items_per_page'] = '5';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['defaults']['arguments'] = FALSE;
  /* Contextual filter: Log: Planting (field_farm_planting) */
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['id'] = 'field_farm_planting_target_id';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['table'] = 'field_data_field_farm_planting';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['field'] = 'field_farm_planting_target_id';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['default_action'] = 'empty';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['specify_validation'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['validate']['type'] = 'php';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['validate_options']['code'] = '// Attempt to load the asset.
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
    'farm_harvest' => 'farm_harvest',
  );
  $handler->display->display_options['filters']['type']['group'] = 1;
  $handler->display->display_options['argument_input'] = array(
    'field_farm_planting_target_id' => array(
      'type' => 'context',
      'context' => 'entity:farm_asset.id',
      'context_optional' => 0,
      'panel' => '0',
      'fixed' => '',
      'label' => 'Log: Planting (field_farm_planting)',
    ),
  );
  $export['farm_log_harvest'] = $view;

  $view = new view();
  $view->name = 'farm_log_seeding';
  $view->description = '';
  $view->tag = 'farm_crop';
  $view->base_table = 'log';
  $view->human_name = 'Farm Log: Seeding';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Seedings';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['access']['perm'] = 'view any planting farm assets';
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
    'name_1' => 'name_1',
    'field_farm_quantity' => 'field_farm_quantity',
    'name' => 'name',
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
    'name_1' => array(
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
    'name' => array(
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
  $handler->display->display_options['empty']['area_text_custom']['content'] = 'No seedings found.';
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
  $handler->display->display_options['fields']['name_1']['id'] = 'name_1';
  $handler->display->display_options['fields']['name_1']['table'] = 'log';
  $handler->display->display_options['fields']['name_1']['field'] = 'name';
  $handler->display->display_options['fields']['name_1']['label'] = 'Seeding';
  $handler->display->display_options['fields']['name_1']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['name_1']['alter']['path'] = 'log/[id]';
  /* Field: Log: Quantity */
  $handler->display->display_options['fields']['field_farm_quantity']['id'] = 'field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['table'] = 'field_data_field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['field'] = 'field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['type'] = 'field_collection_fields';
  $handler->display->display_options['fields']['field_farm_quantity']['settings'] = array(
    'view_mode' => 'full',
  );
  /* Field: Log: Planting */
  $handler->display->display_options['fields']['field_farm_planting']['id'] = 'field_farm_planting';
  $handler->display->display_options['fields']['field_farm_planting']['table'] = 'field_data_field_farm_planting';
  $handler->display->display_options['fields']['field_farm_planting']['field'] = 'field_farm_planting';
  $handler->display->display_options['fields']['field_farm_planting']['settings'] = array(
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
    'farm_seeding' => 'farm_seeding',
  );
  $handler->display->display_options['filters']['type']['group'] = 1;
  /* Filter criterion: Field: Date (field_farm_date) */
  $handler->display->display_options['filters']['field_farm_date_value']['id'] = 'field_farm_date_value';
  $handler->display->display_options['filters']['field_farm_date_value']['table'] = 'field_data_field_farm_date';
  $handler->display->display_options['filters']['field_farm_date_value']['field'] = 'field_farm_date_value';
  $handler->display->display_options['filters']['field_farm_date_value']['operator'] = '>=';
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

  /* Display: All seedings (Page) */
  $handler = $view->new_display('page', 'All seedings (Page)', 'page_1');
  $handler->display->display_options['path'] = 'admin/farm/plan/seedings';
  $handler->display->display_options['menu']['type'] = 'tab';
  $handler->display->display_options['menu']['title'] = 'Seedings';
  $handler->display->display_options['menu']['weight'] = '0';
  $handler->display->display_options['menu']['name'] = 'management';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;

  /* Display: Seedings by planting (Page) */
  $handler = $view->new_display('page', 'Seedings by planting (Page)', 'page_2');
  $handler->display->display_options['defaults']['arguments'] = FALSE;
  /* Contextual filter: Log: Planting (field_farm_planting) */
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['id'] = 'field_farm_planting_target_id';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['table'] = 'field_data_field_farm_planting';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['field'] = 'field_farm_planting_target_id';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['default_action'] = 'not found';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['title_enable'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['title'] = 'Seedings of %1';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['specify_validation'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['validate']['type'] = 'php';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['validate_options']['code'] = '// Attempt to load the asset.
$farm_asset = farm_asset_load($argument);
if (!empty($farm_asset)) {
  // Set the title.
  $handler->validated_title = entity_label(\'farm_asset\', $farm_asset);
  return TRUE;
}';
  $handler->display->display_options['path'] = 'farm/asset/%/seedings';

  /* Display: Seedings by planting (Content pane) */
  $handler = $view->new_display('panel_pane', 'Seedings by planting (Content pane)', 'panel_pane_1');
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
  $handler->display->display_options['defaults']['arguments'] = FALSE;
  /* Contextual filter: Log: Planting (field_farm_planting) */
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['id'] = 'field_farm_planting_target_id';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['table'] = 'field_data_field_farm_planting';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['field'] = 'field_farm_planting_target_id';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['default_action'] = 'not found';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['specify_validation'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['validate']['type'] = 'php';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['validate_options']['code'] = '// Attempt to load the asset.
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
    'farm_seeding' => 'farm_seeding',
  );
  $handler->display->display_options['filters']['type']['group'] = 1;
  $handler->display->display_options['argument_input'] = array(
    'field_farm_planting_target_id' => array(
      'type' => 'context',
      'context' => 'entity:farm_asset.id',
      'context_optional' => 0,
      'panel' => '0',
      'fixed' => '',
      'label' => 'Log: Planting (field_farm_planting)',
    ),
  );
  $handler->display->display_options['link_to_view'] = '0';
  $export['farm_log_seeding'] = $view;

  $view = new view();
  $view->name = 'farm_log_transplanting';
  $view->description = '';
  $view->tag = 'farm_crop';
  $view->base_table = 'log';
  $view->human_name = 'Farm Log: Transplanting';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Transplantings';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['access']['perm'] = 'view any planting farm assets';
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
    'name_1' => 'name_1',
    'field_farm_quantity' => 'field_farm_quantity',
    'name' => 'name',
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
    'name_1' => array(
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
    'name' => array(
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
  $handler->display->display_options['empty']['area_text_custom']['content'] = 'No transplantings found.';
  /* Relationship: Entity Reference: Referenced Entity */
  $handler->display->display_options['relationships']['field_farm_planting_target_id']['id'] = 'field_farm_planting_target_id';
  $handler->display->display_options['relationships']['field_farm_planting_target_id']['table'] = 'field_data_field_farm_planting';
  $handler->display->display_options['relationships']['field_farm_planting_target_id']['field'] = 'field_farm_planting_target_id';
  $handler->display->display_options['relationships']['field_farm_planting_target_id']['label'] = 'Planting';
  $handler->display->display_options['relationships']['field_farm_planting_target_id']['required'] = TRUE;
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
  $handler->display->display_options['fields']['name_1']['id'] = 'name_1';
  $handler->display->display_options['fields']['name_1']['table'] = 'log';
  $handler->display->display_options['fields']['name_1']['field'] = 'name';
  $handler->display->display_options['fields']['name_1']['label'] = 'Transplanting';
  $handler->display->display_options['fields']['name_1']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['name_1']['alter']['path'] = 'log/[id]';
  /* Field: Log: Quantity */
  $handler->display->display_options['fields']['field_farm_quantity']['id'] = 'field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['table'] = 'field_data_field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['field'] = 'field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['type'] = 'field_collection_fields';
  $handler->display->display_options['fields']['field_farm_quantity']['settings'] = array(
    'view_mode' => 'full',
  );
  /* Field: Farm asset: Name */
  $handler->display->display_options['fields']['name']['id'] = 'name';
  $handler->display->display_options['fields']['name']['table'] = 'farm_asset';
  $handler->display->display_options['fields']['name']['field'] = 'name';
  $handler->display->display_options['fields']['name']['relationship'] = 'field_farm_planting_target_id';
  $handler->display->display_options['fields']['name']['label'] = 'Planting';
  $handler->display->display_options['fields']['name']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['name']['alter']['path'] = 'farm/asset/!1';
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
    'farm_transplanting' => 'farm_transplanting',
  );
  $handler->display->display_options['filters']['type']['group'] = 1;
  /* Filter criterion: Field: Date (field_farm_date) */
  $handler->display->display_options['filters']['field_farm_date_value']['id'] = 'field_farm_date_value';
  $handler->display->display_options['filters']['field_farm_date_value']['table'] = 'field_data_field_farm_date';
  $handler->display->display_options['filters']['field_farm_date_value']['field'] = 'field_farm_date_value';
  $handler->display->display_options['filters']['field_farm_date_value']['operator'] = '>=';
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

  /* Display: All transplantings (Page) */
  $handler = $view->new_display('page', 'All transplantings (Page)', 'page_1');
  $handler->display->display_options['path'] = 'admin/farm/plan/transplantings';
  $handler->display->display_options['menu']['type'] = 'tab';
  $handler->display->display_options['menu']['title'] = 'Transplantings';
  $handler->display->display_options['menu']['weight'] = '0';
  $handler->display->display_options['menu']['name'] = 'management';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;

  /* Display: Transplantings by planting (Page) */
  $handler = $view->new_display('page', 'Transplantings by planting (Page)', 'page_2');
  $handler->display->display_options['defaults']['arguments'] = FALSE;
  /* Contextual filter: Log: Planting (field_farm_planting) */
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['id'] = 'field_farm_planting_target_id';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['table'] = 'field_data_field_farm_planting';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['field'] = 'field_farm_planting_target_id';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['default_action'] = 'not found';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['title_enable'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['title'] = 'Transplantings of %1';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['specify_validation'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['validate']['type'] = 'php';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['validate_options']['code'] = '// Attempt to load the asset.
$farm_asset = farm_asset_load($argument);
if (!empty($farm_asset)) {
  // Set the title.
  $handler->validated_title = entity_label(\'farm_asset\', $farm_asset);
  return TRUE;
}';
  $handler->display->display_options['path'] = 'farm/asset/%/transplantings';

  /* Display: Transplantings by planting (Content pane) */
  $handler = $view->new_display('panel_pane', 'Transplantings by planting (Content pane)', 'panel_pane_1');
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
  $handler->display->display_options['defaults']['arguments'] = FALSE;
  /* Contextual filter: Log: Planting (field_farm_planting) */
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['id'] = 'field_farm_planting_target_id';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['table'] = 'field_data_field_farm_planting';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['field'] = 'field_farm_planting_target_id';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['default_action'] = 'not found';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['specify_validation'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['validate']['type'] = 'php';
  $handler->display->display_options['arguments']['field_farm_planting_target_id']['validate_options']['code'] = '// Attempt to load the asset.
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
    'farm_transplanting' => 'farm_transplanting',
  );
  $handler->display->display_options['filters']['type']['group'] = 1;
  $handler->display->display_options['argument_input'] = array(
    'field_farm_planting_target_id' => array(
      'type' => 'context',
      'context' => 'entity:farm_asset.id',
      'context_optional' => 0,
      'panel' => '0',
      'fixed' => '',
      'label' => 'Log: Planting (field_farm_planting)',
    ),
  );
  $handler->display->display_options['link_to_view'] = '0';
  $export['farm_log_transplanting'] = $view;

  $view = new view();
  $view->name = 'farm_plantings';
  $view->description = '';
  $view->tag = 'farm_crop';
  $view->base_table = 'farm_asset';
  $view->human_name = 'Farm Plantings';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Plantings';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['access']['perm'] = 'view any planting farm assets';
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
    'field_farm_crop' => 'field_farm_crop',
    'field_farm_season' => 'field_farm_season',
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
    'field_farm_crop' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_season' => array(
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
  $handler->display->display_options['empty']['area_text_custom']['content'] = 'No plantings found.';
  /* Field: Farm asset: Farm asset ID */
  $handler->display->display_options['fields']['id']['id'] = 'id';
  $handler->display->display_options['fields']['id']['table'] = 'farm_asset';
  $handler->display->display_options['fields']['id']['field'] = 'id';
  $handler->display->display_options['fields']['id']['exclude'] = TRUE;
  /* Field: Farm asset: Name */
  $handler->display->display_options['fields']['name']['id'] = 'name';
  $handler->display->display_options['fields']['name']['table'] = 'farm_asset';
  $handler->display->display_options['fields']['name']['field'] = 'name';
  $handler->display->display_options['fields']['name']['label'] = 'Planting';
  $handler->display->display_options['fields']['name']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['name']['alter']['path'] = 'farm/asset/[id]';
  /* Field: Farm asset: Crop */
  $handler->display->display_options['fields']['field_farm_crop']['id'] = 'field_farm_crop';
  $handler->display->display_options['fields']['field_farm_crop']['table'] = 'field_data_field_farm_crop';
  $handler->display->display_options['fields']['field_farm_crop']['field'] = 'field_farm_crop';
  /* Field: Field: Season */
  $handler->display->display_options['fields']['field_farm_season']['id'] = 'field_farm_season';
  $handler->display->display_options['fields']['field_farm_season']['table'] = 'field_data_field_farm_season';
  $handler->display->display_options['fields']['field_farm_season']['field'] = 'field_farm_season';
  /* Sort criterion: Farm asset: Name */
  $handler->display->display_options['sorts']['name']['id'] = 'name';
  $handler->display->display_options['sorts']['name']['table'] = 'farm_asset';
  $handler->display->display_options['sorts']['name']['field'] = 'name';
  $handler->display->display_options['sorts']['name']['exposed'] = TRUE;
  $handler->display->display_options['sorts']['name']['expose']['label'] = 'Name';
  /* Sort criterion: Farm asset: Crop (field_farm_crop) */
  $handler->display->display_options['sorts']['field_farm_crop_tid']['id'] = 'field_farm_crop_tid';
  $handler->display->display_options['sorts']['field_farm_crop_tid']['table'] = 'field_data_field_farm_crop';
  $handler->display->display_options['sorts']['field_farm_crop_tid']['field'] = 'field_farm_crop_tid';
  $handler->display->display_options['sorts']['field_farm_crop_tid']['exposed'] = TRUE;
  $handler->display->display_options['sorts']['field_farm_crop_tid']['expose']['label'] = 'Crop';
  /* Sort criterion: Field: Season (field_farm_season) */
  $handler->display->display_options['sorts']['field_farm_season_tid']['id'] = 'field_farm_season_tid';
  $handler->display->display_options['sorts']['field_farm_season_tid']['table'] = 'field_data_field_farm_season';
  $handler->display->display_options['sorts']['field_farm_season_tid']['field'] = 'field_farm_season_tid';
  $handler->display->display_options['sorts']['field_farm_season_tid']['exposed'] = TRUE;
  $handler->display->display_options['sorts']['field_farm_season_tid']['expose']['label'] = 'Season';
  /* Filter criterion: Farm asset: Farm asset type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'farm_asset';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'planting' => 'planting',
  );
  $handler->display->display_options['filters']['type']['group'] = 1;
  /* Filter criterion: Farm asset: Crop (field_farm_crop) */
  $handler->display->display_options['filters']['field_farm_crop_tid']['id'] = 'field_farm_crop_tid';
  $handler->display->display_options['filters']['field_farm_crop_tid']['table'] = 'field_data_field_farm_crop';
  $handler->display->display_options['filters']['field_farm_crop_tid']['field'] = 'field_farm_crop_tid';
  $handler->display->display_options['filters']['field_farm_crop_tid']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_crop_tid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_crop_tid']['expose']['operator_id'] = 'field_farm_crop_tid_op';
  $handler->display->display_options['filters']['field_farm_crop_tid']['expose']['label'] = 'Crop';
  $handler->display->display_options['filters']['field_farm_crop_tid']['expose']['operator'] = 'field_farm_crop_tid_op';
  $handler->display->display_options['filters']['field_farm_crop_tid']['expose']['identifier'] = 'field_farm_crop_tid';
  $handler->display->display_options['filters']['field_farm_crop_tid']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    30037204 => 0,
    200153887 => 0,
    125843996 => 0,
    173712484 => 0,
    196317508 => 0,
    146437786 => 0,
  );
  $handler->display->display_options['filters']['field_farm_crop_tid']['type'] = 'select';
  $handler->display->display_options['filters']['field_farm_crop_tid']['vocabulary'] = 'farm_crops';
  $handler->display->display_options['filters']['field_farm_crop_tid']['hierarchy'] = 1;
  /* Filter criterion: Field: Season (field_farm_season) */
  $handler->display->display_options['filters']['field_farm_season_tid']['id'] = 'field_farm_season_tid';
  $handler->display->display_options['filters']['field_farm_season_tid']['table'] = 'field_data_field_farm_season';
  $handler->display->display_options['filters']['field_farm_season_tid']['field'] = 'field_farm_season_tid';
  $handler->display->display_options['filters']['field_farm_season_tid']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_season_tid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_season_tid']['expose']['operator_id'] = 'field_farm_season_tid_op';
  $handler->display->display_options['filters']['field_farm_season_tid']['expose']['label'] = 'Season';
  $handler->display->display_options['filters']['field_farm_season_tid']['expose']['operator'] = 'field_farm_season_tid_op';
  $handler->display->display_options['filters']['field_farm_season_tid']['expose']['identifier'] = 'field_farm_season_tid';
  $handler->display->display_options['filters']['field_farm_season_tid']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    30037204 => 0,
    200153887 => 0,
    125843996 => 0,
    173712484 => 0,
    196317508 => 0,
    146437786 => 0,
  );
  $handler->display->display_options['filters']['field_farm_season_tid']['type'] = 'select';
  $handler->display->display_options['filters']['field_farm_season_tid']['vocabulary'] = 'farm_season';
  $handler->display->display_options['filters']['field_farm_season_tid']['hierarchy'] = 1;

  /* Display: All plantings (Page) */
  $handler = $view->new_display('page', 'All plantings (Page)', 'page');
  $handler->display->display_options['path'] = 'admin/farm/crops/plantings';
  $handler->display->display_options['menu']['type'] = 'tab';
  $handler->display->display_options['menu']['title'] = 'Plantings';
  $handler->display->display_options['menu']['weight'] = '0';
  $handler->display->display_options['menu']['name'] = 'management';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;
  $export['farm_plantings'] = $view;

  return $export;
}
