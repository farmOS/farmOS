<?php
/**
 * @file
 * farm_livestock.views_default.inc
 */

/**
 * Implements hook_views_default_views().
 */
function farm_livestock_views_default_views() {
  $export = array();

  $view = new view();
  $view->name = 'farm_animals';
  $view->description = '';
  $view->tag = 'farm_livestock';
  $view->base_table = 'farm_asset';
  $view->human_name = 'Farm Animals';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Animals';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['access']['perm'] = 'view any animal farm assets';
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
  );
  /* No results behavior: Global: Unfiltered text */
  $handler->display->display_options['empty']['area_text_custom']['id'] = 'area_text_custom';
  $handler->display->display_options['empty']['area_text_custom']['table'] = 'views';
  $handler->display->display_options['empty']['area_text_custom']['field'] = 'area_text_custom';
  $handler->display->display_options['empty']['area_text_custom']['empty'] = TRUE;
  $handler->display->display_options['empty']['area_text_custom']['content'] = 'No animals found.';
  /* Relationship: Farm asset: Latest movement */
  $handler->display->display_options['relationships']['latest_movement']['id'] = 'latest_movement';
  $handler->display->display_options['relationships']['latest_movement']['table'] = 'farm_asset';
  $handler->display->display_options['relationships']['latest_movement']['field'] = 'latest_movement';
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
  /* Field: Farm asset: Sex */
  $handler->display->display_options['fields']['field_farm_animal_sex']['id'] = 'field_farm_animal_sex';
  $handler->display->display_options['fields']['field_farm_animal_sex']['table'] = 'field_data_field_farm_animal_sex';
  $handler->display->display_options['fields']['field_farm_animal_sex']['field'] = 'field_farm_animal_sex';
  /* Field: Farm asset: Type */
  $handler->display->display_options['fields']['field_farm_animal_type']['id'] = 'field_farm_animal_type';
  $handler->display->display_options['fields']['field_farm_animal_type']['table'] = 'field_data_field_farm_animal_type';
  $handler->display->display_options['fields']['field_farm_animal_type']['field'] = 'field_farm_animal_type';
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
  /* Sort criterion: Farm asset: Sex (field_farm_animal_sex) */
  $handler->display->display_options['sorts']['field_farm_animal_sex_value']['id'] = 'field_farm_animal_sex_value';
  $handler->display->display_options['sorts']['field_farm_animal_sex_value']['table'] = 'field_data_field_farm_animal_sex';
  $handler->display->display_options['sorts']['field_farm_animal_sex_value']['field'] = 'field_farm_animal_sex_value';
  $handler->display->display_options['sorts']['field_farm_animal_sex_value']['exposed'] = TRUE;
  $handler->display->display_options['sorts']['field_farm_animal_sex_value']['expose']['label'] = 'Sex';
  /* Sort criterion: Farm asset: Type (field_farm_animal_type) */
  $handler->display->display_options['sorts']['field_farm_animal_type_tid']['id'] = 'field_farm_animal_type_tid';
  $handler->display->display_options['sorts']['field_farm_animal_type_tid']['table'] = 'field_data_field_farm_animal_type';
  $handler->display->display_options['sorts']['field_farm_animal_type_tid']['field'] = 'field_farm_animal_type_tid';
  $handler->display->display_options['sorts']['field_farm_animal_type_tid']['exposed'] = TRUE;
  $handler->display->display_options['sorts']['field_farm_animal_type_tid']['expose']['label'] = 'Type';
  /* Filter criterion: Farm asset: Farm asset type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'farm_asset';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'animal' => 'animal',
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
  /* Filter criterion: Farm asset: Sex (field_farm_animal_sex) */
  $handler->display->display_options['filters']['field_farm_animal_sex_value']['id'] = 'field_farm_animal_sex_value';
  $handler->display->display_options['filters']['field_farm_animal_sex_value']['table'] = 'field_data_field_farm_animal_sex';
  $handler->display->display_options['filters']['field_farm_animal_sex_value']['field'] = 'field_farm_animal_sex_value';
  $handler->display->display_options['filters']['field_farm_animal_sex_value']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_animal_sex_value']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_animal_sex_value']['expose']['operator_id'] = 'field_farm_animal_sex_value_op';
  $handler->display->display_options['filters']['field_farm_animal_sex_value']['expose']['label'] = 'Sex';
  $handler->display->display_options['filters']['field_farm_animal_sex_value']['expose']['operator'] = 'field_farm_animal_sex_value_op';
  $handler->display->display_options['filters']['field_farm_animal_sex_value']['expose']['identifier'] = 'field_farm_animal_sex_value';
  $handler->display->display_options['filters']['field_farm_animal_sex_value']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    30037204 => 0,
    200153887 => 0,
    125843996 => 0,
    173712484 => 0,
    196317508 => 0,
    146437786 => 0,
  );
  /* Filter criterion: Farm asset: Type (field_farm_animal_type) */
  $handler->display->display_options['filters']['field_farm_animal_type_tid']['id'] = 'field_farm_animal_type_tid';
  $handler->display->display_options['filters']['field_farm_animal_type_tid']['table'] = 'field_data_field_farm_animal_type';
  $handler->display->display_options['filters']['field_farm_animal_type_tid']['field'] = 'field_farm_animal_type_tid';
  $handler->display->display_options['filters']['field_farm_animal_type_tid']['value'] = '';
  $handler->display->display_options['filters']['field_farm_animal_type_tid']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_animal_type_tid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_animal_type_tid']['expose']['operator_id'] = 'field_farm_animal_type_tid_op';
  $handler->display->display_options['filters']['field_farm_animal_type_tid']['expose']['label'] = 'Type';
  $handler->display->display_options['filters']['field_farm_animal_type_tid']['expose']['operator'] = 'field_farm_animal_type_tid_op';
  $handler->display->display_options['filters']['field_farm_animal_type_tid']['expose']['identifier'] = 'field_farm_animal_type_tid';
  $handler->display->display_options['filters']['field_farm_animal_type_tid']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    30037204 => 0,
    200153887 => 0,
    125843996 => 0,
    173712484 => 0,
    196317508 => 0,
    146437786 => 0,
  );
  $handler->display->display_options['filters']['field_farm_animal_type_tid']['type'] = 'select';
  $handler->display->display_options['filters']['field_farm_animal_type_tid']['vocabulary'] = 'farm_animal_types';
  $handler->display->display_options['filters']['field_farm_animal_type_tid']['hierarchy'] = 1;

  /* Display: All animals (Page) */
  $handler = $view->new_display('page', 'All animals (Page)', 'page');
  $handler->display->display_options['path'] = 'admin/farm/livestock/animals';
  $handler->display->display_options['menu']['type'] = 'default tab';
  $handler->display->display_options['menu']['title'] = 'Animals';
  $handler->display->display_options['menu']['weight'] = '0';
  $handler->display->display_options['menu']['name'] = 'management';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;
  $handler->display->display_options['tab_options']['type'] = 'tab';
  $handler->display->display_options['tab_options']['title'] = 'Livestock';
  $handler->display->display_options['tab_options']['weight'] = '10';

  /* Display: Animals by area (Page) */
  $handler = $view->new_display('page', 'Animals by area (Page)', 'page_1');
  $handler->display->display_options['defaults']['arguments'] = FALSE;
  /* Contextual filter: Log: To (field_farm_move_to) */
  $handler->display->display_options['arguments']['field_farm_move_to_tid']['id'] = 'field_farm_move_to_tid';
  $handler->display->display_options['arguments']['field_farm_move_to_tid']['table'] = 'field_data_field_farm_move_to';
  $handler->display->display_options['arguments']['field_farm_move_to_tid']['field'] = 'field_farm_move_to_tid';
  $handler->display->display_options['arguments']['field_farm_move_to_tid']['relationship'] = 'latest_movement';
  $handler->display->display_options['arguments']['field_farm_move_to_tid']['default_action'] = 'not found';
  $handler->display->display_options['arguments']['field_farm_move_to_tid']['title_enable'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_move_to_tid']['title'] = 'Animals in %1';
  $handler->display->display_options['arguments']['field_farm_move_to_tid']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['field_farm_move_to_tid']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['field_farm_move_to_tid']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['field_farm_move_to_tid']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['arguments']['field_farm_move_to_tid']['specify_validation'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_move_to_tid']['validate']['type'] = 'taxonomy_term';
  $handler->display->display_options['arguments']['field_farm_move_to_tid']['validate_options']['vocabularies'] = array(
    'farm_areas' => 'farm_areas',
  );
  $handler->display->display_options['path'] = 'farm/area/%/animals';
  $export['farm_animals'] = $view;

  return $export;
}
