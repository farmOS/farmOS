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
  $view->name = 'farm_animal_groups';
  $view->description = '';
  $view->tag = 'farm_crop';
  $view->base_table = 'taxonomy_term_data';
  $view->human_name = 'Farm Animal Groups';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Animal Groups';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['group_by'] = TRUE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['access']['perm'] = 'view any animal farm assets';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['exposed_form']['options']['reset_button'] = TRUE;
  $handler->display->display_options['pager']['type'] = 'none';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['style_plugin'] = 'tree';
  $handler->display->display_options['style_options']['main_field'] = 'tid';
  $handler->display->display_options['style_options']['parent_field'] = 'tid_1';
  $handler->display->display_options['row_plugin'] = 'fields';
  $handler->display->display_options['row_options']['inline'] = array(
    'name' => 'name',
    'id' => 'id',
  );
  /* Header: Global: Unfiltered text */
  $handler->display->display_options['header']['area_text_custom']['id'] = 'area_text_custom';
  $handler->display->display_options['header']['area_text_custom']['table'] = 'views';
  $handler->display->display_options['header']['area_text_custom']['field'] = 'area_text_custom';
  $handler->display->display_options['header']['area_text_custom']['content'] = '<h4>Hierarchy <small>' . l('(change)', 'admin/structure/taxonomy/farm_animal_groups', array('query' => array('destination' => 'farm/assets/animals/groups'))) . '</small></h4>';
  /* No results behavior: Global: Unfiltered text */
  $handler->display->display_options['empty']['area_text_custom']['id'] = 'area_text_custom';
  $handler->display->display_options['empty']['area_text_custom']['table'] = 'views';
  $handler->display->display_options['empty']['area_text_custom']['field'] = 'area_text_custom';
  $handler->display->display_options['empty']['area_text_custom']['empty'] = TRUE;
  $handler->display->display_options['empty']['area_text_custom']['content'] = 'No groups found.';
  /* Relationship: Taxonomy term: Parent term */
  $handler->display->display_options['relationships']['parent']['id'] = 'parent';
  $handler->display->display_options['relationships']['parent']['table'] = 'taxonomy_term_hierarchy';
  $handler->display->display_options['relationships']['parent']['field'] = 'parent';
  /* Relationship: Taxonomy term: Farm asset using Group */
  $handler->display->display_options['relationships']['reverse_field_farm_animal_group_farm_asset']['id'] = 'reverse_field_farm_animal_group_farm_asset';
  $handler->display->display_options['relationships']['reverse_field_farm_animal_group_farm_asset']['table'] = 'taxonomy_term_data';
  $handler->display->display_options['relationships']['reverse_field_farm_animal_group_farm_asset']['field'] = 'reverse_field_farm_animal_group_farm_asset';
  /* Field: Taxonomy term: Term ID */
  $handler->display->display_options['fields']['tid']['id'] = 'tid';
  $handler->display->display_options['fields']['tid']['table'] = 'taxonomy_term_data';
  $handler->display->display_options['fields']['tid']['field'] = 'tid';
  $handler->display->display_options['fields']['tid']['exclude'] = TRUE;
  $handler->display->display_options['fields']['tid']['separator'] = '';
  /* Field: Taxonomy term: Term ID */
  $handler->display->display_options['fields']['tid_1']['id'] = 'tid_1';
  $handler->display->display_options['fields']['tid_1']['table'] = 'taxonomy_term_data';
  $handler->display->display_options['fields']['tid_1']['field'] = 'tid';
  $handler->display->display_options['fields']['tid_1']['relationship'] = 'parent';
  $handler->display->display_options['fields']['tid_1']['label'] = 'Parent Term ID';
  $handler->display->display_options['fields']['tid_1']['exclude'] = TRUE;
  $handler->display->display_options['fields']['tid_1']['separator'] = '';
  /* Field: Taxonomy term: Name */
  $handler->display->display_options['fields']['name']['id'] = 'name';
  $handler->display->display_options['fields']['name']['table'] = 'taxonomy_term_data';
  $handler->display->display_options['fields']['name']['field'] = 'name';
  $handler->display->display_options['fields']['name']['label'] = '';
  $handler->display->display_options['fields']['name']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['name']['alter']['ellipsis'] = FALSE;
  $handler->display->display_options['fields']['name']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['name']['link_to_taxonomy'] = TRUE;
  /* Field: COUNT(Farm asset: Farm asset ID) */
  $handler->display->display_options['fields']['id']['id'] = 'id';
  $handler->display->display_options['fields']['id']['table'] = 'farm_asset';
  $handler->display->display_options['fields']['id']['field'] = 'id';
  $handler->display->display_options['fields']['id']['relationship'] = 'reverse_field_farm_animal_group_farm_asset';
  $handler->display->display_options['fields']['id']['group_type'] = 'count';
  $handler->display->display_options['fields']['id']['label'] = '';
  $handler->display->display_options['fields']['id']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['id']['prefix'] = '(';
  $handler->display->display_options['fields']['id']['suffix'] = ')';
  /* Filter criterion: Taxonomy vocabulary: Machine name */
  $handler->display->display_options['filters']['machine_name']['id'] = 'machine_name';
  $handler->display->display_options['filters']['machine_name']['table'] = 'taxonomy_vocabulary';
  $handler->display->display_options['filters']['machine_name']['field'] = 'machine_name';
  $handler->display->display_options['filters']['machine_name']['value'] = array(
    'farm_animal_groups' => 'farm_animal_groups',
  );
  $handler->display->display_options['filters']['machine_name']['group'] = 1;

  /* Display: Page */
  $handler = $view->new_display('page', 'Page', 'page');
  $handler->display->display_options['path'] = 'farm/assets/animals/groups';
  $handler->display->display_options['menu']['type'] = 'tab';
  $handler->display->display_options['menu']['title'] = 'Groups';
  $handler->display->display_options['menu']['weight'] = '';
  $handler->display->display_options['menu']['name'] = 'farm';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;
  $export['farm_animal_groups'] = $view;

  $view = new view();
  $view->name = 'farm_animal_types';
  $view->description = '';
  $view->tag = 'farm_crop';
  $view->base_table = 'taxonomy_term_data';
  $view->human_name = 'Farm Animal Types';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Animal Types';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['group_by'] = TRUE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['access']['perm'] = 'view any animal farm assets';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['exposed_form']['options']['reset_button'] = TRUE;
  $handler->display->display_options['pager']['type'] = 'none';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['style_plugin'] = 'tree';
  $handler->display->display_options['style_options']['main_field'] = 'tid';
  $handler->display->display_options['style_options']['parent_field'] = 'tid_1';
  $handler->display->display_options['row_plugin'] = 'fields';
  $handler->display->display_options['row_options']['inline'] = array(
    'name' => 'name',
    'id' => 'id',
  );
  /* Header: Global: Unfiltered text */
  $handler->display->display_options['header']['area_text_custom']['id'] = 'area_text_custom';
  $handler->display->display_options['header']['area_text_custom']['table'] = 'views';
  $handler->display->display_options['header']['area_text_custom']['field'] = 'area_text_custom';
  $handler->display->display_options['header']['area_text_custom']['content'] = '<h4>Hierarchy <small>' . l('(change)', 'admin/structure/taxonomy/farm_animal_types', array('query' => array('destination' => 'farm/assets/animals/types'))) . '</small></h4>';
  /* No results behavior: Global: Unfiltered text */
  $handler->display->display_options['empty']['area_text_custom']['id'] = 'area_text_custom';
  $handler->display->display_options['empty']['area_text_custom']['table'] = 'views';
  $handler->display->display_options['empty']['area_text_custom']['field'] = 'area_text_custom';
  $handler->display->display_options['empty']['area_text_custom']['empty'] = TRUE;
  $handler->display->display_options['empty']['area_text_custom']['content'] = 'No types found.';
  /* Relationship: Taxonomy term: Parent term */
  $handler->display->display_options['relationships']['parent']['id'] = 'parent';
  $handler->display->display_options['relationships']['parent']['table'] = 'taxonomy_term_hierarchy';
  $handler->display->display_options['relationships']['parent']['field'] = 'parent';
  /* Relationship: Taxonomy term: Farm asset using Type */
  $handler->display->display_options['relationships']['reverse_field_farm_animal_type_farm_asset']['id'] = 'reverse_field_farm_animal_type_farm_asset';
  $handler->display->display_options['relationships']['reverse_field_farm_animal_type_farm_asset']['table'] = 'taxonomy_term_data';
  $handler->display->display_options['relationships']['reverse_field_farm_animal_type_farm_asset']['field'] = 'reverse_field_farm_animal_type_farm_asset';
  /* Field: Taxonomy term: Term ID */
  $handler->display->display_options['fields']['tid']['id'] = 'tid';
  $handler->display->display_options['fields']['tid']['table'] = 'taxonomy_term_data';
  $handler->display->display_options['fields']['tid']['field'] = 'tid';
  $handler->display->display_options['fields']['tid']['exclude'] = TRUE;
  $handler->display->display_options['fields']['tid']['separator'] = '';
  /* Field: Taxonomy term: Term ID */
  $handler->display->display_options['fields']['tid_1']['id'] = 'tid_1';
  $handler->display->display_options['fields']['tid_1']['table'] = 'taxonomy_term_data';
  $handler->display->display_options['fields']['tid_1']['field'] = 'tid';
  $handler->display->display_options['fields']['tid_1']['relationship'] = 'parent';
  $handler->display->display_options['fields']['tid_1']['label'] = 'Parent Term ID';
  $handler->display->display_options['fields']['tid_1']['exclude'] = TRUE;
  $handler->display->display_options['fields']['tid_1']['separator'] = '';
  /* Field: Taxonomy term: Name */
  $handler->display->display_options['fields']['name']['id'] = 'name';
  $handler->display->display_options['fields']['name']['table'] = 'taxonomy_term_data';
  $handler->display->display_options['fields']['name']['field'] = 'name';
  $handler->display->display_options['fields']['name']['label'] = '';
  $handler->display->display_options['fields']['name']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['name']['alter']['ellipsis'] = FALSE;
  $handler->display->display_options['fields']['name']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['name']['link_to_taxonomy'] = TRUE;
  /* Field: COUNT(Farm asset: Farm asset ID) */
  $handler->display->display_options['fields']['id']['id'] = 'id';
  $handler->display->display_options['fields']['id']['table'] = 'farm_asset';
  $handler->display->display_options['fields']['id']['field'] = 'id';
  $handler->display->display_options['fields']['id']['relationship'] = 'reverse_field_farm_animal_type_farm_asset';
  $handler->display->display_options['fields']['id']['group_type'] = 'count';
  $handler->display->display_options['fields']['id']['label'] = '';
  $handler->display->display_options['fields']['id']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['id']['prefix'] = '(';
  $handler->display->display_options['fields']['id']['suffix'] = ')';
  /* Filter criterion: Taxonomy vocabulary: Machine name */
  $handler->display->display_options['filters']['machine_name']['id'] = 'machine_name';
  $handler->display->display_options['filters']['machine_name']['table'] = 'taxonomy_vocabulary';
  $handler->display->display_options['filters']['machine_name']['field'] = 'machine_name';
  $handler->display->display_options['filters']['machine_name']['value'] = array(
    'farm_animal_types' => 'farm_animal_types',
  );
  $handler->display->display_options['filters']['machine_name']['group'] = 1;

  /* Display: Page */
  $handler = $view->new_display('page', 'Page', 'page');
  $handler->display->display_options['path'] = 'farm/assets/animals/types';
  $handler->display->display_options['menu']['type'] = 'tab';
  $handler->display->display_options['menu']['title'] = 'Types';
  $handler->display->display_options['menu']['weight'] = '0';
  $handler->display->display_options['menu']['name'] = 'farm';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;
  $export['farm_animal_types'] = $view;

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
    'name' => 'name',
    'field_farm_animal_sex' => 'field_farm_animal_sex',
    'field_farm_date' => 'field_farm_date',
    'field_farm_animal_type' => 'field_farm_animal_type',
    'field_farm_animal_group' => 'field_farm_animal_group',
    'field_farm_move_to' => 'field_farm_move_to',
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
    'field_farm_animal_sex' => array(
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
    'field_farm_animal_type' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_animal_group' => array(
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
  $handler->display->display_options['style_options']['empty_table'] = TRUE;
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
  /* Relationship: Farm asset: ID Tag(s) (field_farm_animal_tag) */
  $handler->display->display_options['relationships']['field_farm_animal_tag_value']['id'] = 'field_farm_animal_tag_value';
  $handler->display->display_options['relationships']['field_farm_animal_tag_value']['table'] = 'field_data_field_farm_animal_tag';
  $handler->display->display_options['relationships']['field_farm_animal_tag_value']['field'] = 'field_farm_animal_tag_value';
  $handler->display->display_options['relationships']['field_farm_animal_tag_value']['delta'] = '-1';
  /* Field: Bulk operations: Farm asset */
  $handler->display->display_options['fields']['views_bulk_operations']['id'] = 'views_bulk_operations';
  $handler->display->display_options['fields']['views_bulk_operations']['table'] = 'farm_asset';
  $handler->display->display_options['fields']['views_bulk_operations']['field'] = 'views_bulk_operations';
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['display_type'] = '1';
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['enable_select_all_pages'] = 1;
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['force_single'] = 0;
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_settings']['entity_load_capacity'] = '10';
  $handler->display->display_options['fields']['views_bulk_operations']['vbo_operations'] = array(
    'action::farm_log_asset_move_action' => array(
      'selected' => 1,
      'postpone_processing' => 0,
      'skip_confirmation' => 1,
      'override_label' => 0,
      'label' => '',
    ),
  );
  /* Field: Farm asset: Farm asset ID */
  $handler->display->display_options['fields']['id']['id'] = 'id';
  $handler->display->display_options['fields']['id']['table'] = 'farm_asset';
  $handler->display->display_options['fields']['id']['field'] = 'id';
  $handler->display->display_options['fields']['id']['exclude'] = TRUE;
  $handler->display->display_options['fields']['id']['separator'] = '';
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
  /* Field: Farm asset: Date of birth */
  $handler->display->display_options['fields']['field_farm_date']['id'] = 'field_farm_date';
  $handler->display->display_options['fields']['field_farm_date']['table'] = 'field_data_field_farm_date';
  $handler->display->display_options['fields']['field_farm_date']['field'] = 'field_farm_date';
  $handler->display->display_options['fields']['field_farm_date']['label'] = 'Birthdate';
  $handler->display->display_options['fields']['field_farm_date']['settings'] = array(
    'format_type' => 'long',
    'fromto' => 'both',
    'multiple_number' => '',
    'multiple_from' => '',
    'multiple_to' => '',
  );
  /* Field: Farm asset: Type */
  $handler->display->display_options['fields']['field_farm_animal_type']['id'] = 'field_farm_animal_type';
  $handler->display->display_options['fields']['field_farm_animal_type']['table'] = 'field_data_field_farm_animal_type';
  $handler->display->display_options['fields']['field_farm_animal_type']['field'] = 'field_farm_animal_type';
  /* Field: Field: Group */
  $handler->display->display_options['fields']['field_farm_animal_group']['id'] = 'field_farm_animal_group';
  $handler->display->display_options['fields']['field_farm_animal_group']['table'] = 'field_data_field_farm_animal_group';
  $handler->display->display_options['fields']['field_farm_animal_group']['field'] = 'field_farm_animal_group';
  /* Field: Farm asset: ID Tag(s) */
  $handler->display->display_options['fields']['field_farm_animal_tag']['id'] = 'field_farm_animal_tag';
  $handler->display->display_options['fields']['field_farm_animal_tag']['table'] = 'field_data_field_farm_animal_tag';
  $handler->display->display_options['fields']['field_farm_animal_tag']['field'] = 'field_farm_animal_tag';
  $handler->display->display_options['fields']['field_farm_animal_tag']['type'] = 'field_collection_fields';
  $handler->display->display_options['fields']['field_farm_animal_tag']['settings'] = array(
    'view_mode' => 'full',
  );
  $handler->display->display_options['fields']['field_farm_animal_tag']['delta_offset'] = '0';
  /* Field: Log: To */
  $handler->display->display_options['fields']['field_farm_move_to']['id'] = 'field_farm_move_to';
  $handler->display->display_options['fields']['field_farm_move_to']['table'] = 'field_data_field_farm_move_to';
  $handler->display->display_options['fields']['field_farm_move_to']['field'] = 'field_farm_move_to';
  $handler->display->display_options['fields']['field_farm_move_to']['relationship'] = 'latest_movement';
  $handler->display->display_options['fields']['field_farm_move_to']['label'] = 'Location';
  /* Sort criterion: Field: Group (field_farm_animal_group) */
  $handler->display->display_options['sorts']['field_farm_animal_group_tid']['id'] = 'field_farm_animal_group_tid';
  $handler->display->display_options['sorts']['field_farm_animal_group_tid']['table'] = 'field_data_field_farm_animal_group';
  $handler->display->display_options['sorts']['field_farm_animal_group_tid']['field'] = 'field_farm_animal_group_tid';
  $handler->display->display_options['sorts']['field_farm_animal_group_tid']['exposed'] = TRUE;
  $handler->display->display_options['sorts']['field_farm_animal_group_tid']['expose']['label'] = 'Group';
  /* Sort criterion: Farm asset: Type (field_farm_animal_type) */
  $handler->display->display_options['sorts']['field_farm_animal_type_tid']['id'] = 'field_farm_animal_type_tid';
  $handler->display->display_options['sorts']['field_farm_animal_type_tid']['table'] = 'field_data_field_farm_animal_type';
  $handler->display->display_options['sorts']['field_farm_animal_type_tid']['field'] = 'field_farm_animal_type_tid';
  $handler->display->display_options['sorts']['field_farm_animal_type_tid']['exposed'] = TRUE;
  $handler->display->display_options['sorts']['field_farm_animal_type_tid']['expose']['label'] = 'Type';
  /* Sort criterion: Farm asset: Sex (field_farm_animal_sex) */
  $handler->display->display_options['sorts']['field_farm_animal_sex_value']['id'] = 'field_farm_animal_sex_value';
  $handler->display->display_options['sorts']['field_farm_animal_sex_value']['table'] = 'field_data_field_farm_animal_sex';
  $handler->display->display_options['sorts']['field_farm_animal_sex_value']['field'] = 'field_farm_animal_sex_value';
  $handler->display->display_options['sorts']['field_farm_animal_sex_value']['exposed'] = TRUE;
  $handler->display->display_options['sorts']['field_farm_animal_sex_value']['expose']['label'] = 'Sex';
  /* Sort criterion: Farm asset: Name */
  $handler->display->display_options['sorts']['name']['id'] = 'name';
  $handler->display->display_options['sorts']['name']['table'] = 'farm_asset';
  $handler->display->display_options['sorts']['name']['field'] = 'name';
  $handler->display->display_options['sorts']['name']['exposed'] = TRUE;
  $handler->display->display_options['sorts']['name']['expose']['label'] = 'Name';
  /* Sort criterion: Field collection item: ID (field_farm_animal_tag_id) */
  $handler->display->display_options['sorts']['field_farm_animal_tag_id_value']['id'] = 'field_farm_animal_tag_id_value';
  $handler->display->display_options['sorts']['field_farm_animal_tag_id_value']['table'] = 'field_data_field_farm_animal_tag_id';
  $handler->display->display_options['sorts']['field_farm_animal_tag_id_value']['field'] = 'field_farm_animal_tag_id_value';
  $handler->display->display_options['sorts']['field_farm_animal_tag_id_value']['relationship'] = 'field_farm_animal_tag_value';
  $handler->display->display_options['sorts']['field_farm_animal_tag_id_value']['exposed'] = TRUE;
  $handler->display->display_options['sorts']['field_farm_animal_tag_id_value']['expose']['label'] = 'ID Tag';
  /* Contextual filter: Log: To (field_farm_move_to) */
  $handler->display->display_options['arguments']['field_farm_move_to_tid']['id'] = 'field_farm_move_to_tid';
  $handler->display->display_options['arguments']['field_farm_move_to_tid']['table'] = 'field_data_field_farm_move_to';
  $handler->display->display_options['arguments']['field_farm_move_to_tid']['field'] = 'field_farm_move_to_tid';
  $handler->display->display_options['arguments']['field_farm_move_to_tid']['relationship'] = 'latest_movement';
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
  /* Contextual filter: Field: Group (field_farm_animal_group) */
  $handler->display->display_options['arguments']['field_farm_animal_group_tid']['id'] = 'field_farm_animal_group_tid';
  $handler->display->display_options['arguments']['field_farm_animal_group_tid']['table'] = 'field_data_field_farm_animal_group';
  $handler->display->display_options['arguments']['field_farm_animal_group_tid']['field'] = 'field_farm_animal_group_tid';
  $handler->display->display_options['arguments']['field_farm_animal_group_tid']['title_enable'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_animal_group_tid']['title'] = 'Animals in group %2';
  $handler->display->display_options['arguments']['field_farm_animal_group_tid']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['field_farm_animal_group_tid']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['field_farm_animal_group_tid']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['field_farm_animal_group_tid']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['arguments']['field_farm_animal_group_tid']['specify_validation'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_animal_group_tid']['validate']['type'] = 'taxonomy_term';
  $handler->display->display_options['arguments']['field_farm_animal_group_tid']['validate_options']['vocabularies'] = array(
    'farm_animal_groups' => 'farm_animal_groups',
  );
  /* Contextual filter: Farm asset: Type (field_farm_animal_type) */
  $handler->display->display_options['arguments']['field_farm_animal_type_tid']['id'] = 'field_farm_animal_type_tid';
  $handler->display->display_options['arguments']['field_farm_animal_type_tid']['table'] = 'field_data_field_farm_animal_type';
  $handler->display->display_options['arguments']['field_farm_animal_type_tid']['field'] = 'field_farm_animal_type_tid';
  $handler->display->display_options['arguments']['field_farm_animal_type_tid']['title_enable'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_animal_type_tid']['title'] = 'Animals of type %3';
  $handler->display->display_options['arguments']['field_farm_animal_type_tid']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['field_farm_animal_type_tid']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['field_farm_animal_type_tid']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['field_farm_animal_type_tid']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['arguments']['field_farm_animal_type_tid']['specify_validation'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_animal_type_tid']['validate']['type'] = 'taxonomy_term';
  $handler->display->display_options['arguments']['field_farm_animal_type_tid']['validate_options']['vocabularies'] = array(
    'farm_animal_types' => 'farm_animal_types',
  );
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
  $handler->display->display_options['filters']['field_farm_animal_type_tid']['type'] = 'select';
  $handler->display->display_options['filters']['field_farm_animal_type_tid']['vocabulary'] = 'farm_animal_types';
  $handler->display->display_options['filters']['field_farm_animal_type_tid']['hierarchy'] = 1;
  /* Filter criterion: Field: Group (field_farm_animal_group) */
  $handler->display->display_options['filters']['field_farm_animal_group_tid']['id'] = 'field_farm_animal_group_tid';
  $handler->display->display_options['filters']['field_farm_animal_group_tid']['table'] = 'field_data_field_farm_animal_group';
  $handler->display->display_options['filters']['field_farm_animal_group_tid']['field'] = 'field_farm_animal_group_tid';
  $handler->display->display_options['filters']['field_farm_animal_group_tid']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_animal_group_tid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_animal_group_tid']['expose']['operator_id'] = 'field_farm_animal_group_tid_op';
  $handler->display->display_options['filters']['field_farm_animal_group_tid']['expose']['label'] = 'Group';
  $handler->display->display_options['filters']['field_farm_animal_group_tid']['expose']['operator'] = 'field_farm_animal_group_tid_op';
  $handler->display->display_options['filters']['field_farm_animal_group_tid']['expose']['identifier'] = 'field_farm_animal_group_tid';
  $handler->display->display_options['filters']['field_farm_animal_group_tid']['type'] = 'select';
  $handler->display->display_options['filters']['field_farm_animal_group_tid']['vocabulary'] = 'farm_animal_groups';
  $handler->display->display_options['filters']['field_farm_animal_group_tid']['hierarchy'] = 1;
  /* Filter criterion: Field collection item: ID (field_farm_animal_tag_id) */
  $handler->display->display_options['filters']['field_farm_animal_tag_id_value']['id'] = 'field_farm_animal_tag_id_value';
  $handler->display->display_options['filters']['field_farm_animal_tag_id_value']['table'] = 'field_data_field_farm_animal_tag_id';
  $handler->display->display_options['filters']['field_farm_animal_tag_id_value']['field'] = 'field_farm_animal_tag_id_value';
  $handler->display->display_options['filters']['field_farm_animal_tag_id_value']['relationship'] = 'field_farm_animal_tag_value';
  $handler->display->display_options['filters']['field_farm_animal_tag_id_value']['operator'] = 'contains';
  $handler->display->display_options['filters']['field_farm_animal_tag_id_value']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_animal_tag_id_value']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_animal_tag_id_value']['expose']['operator_id'] = 'field_farm_animal_tag_id_value_op';
  $handler->display->display_options['filters']['field_farm_animal_tag_id_value']['expose']['label'] = 'ID Tag';
  $handler->display->display_options['filters']['field_farm_animal_tag_id_value']['expose']['operator'] = 'field_farm_animal_tag_id_value_op';
  $handler->display->display_options['filters']['field_farm_animal_tag_id_value']['expose']['identifier'] = 'field_farm_animal_tag_id_value';
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
  $handler->display->display_options['path'] = 'farm/assets/animals/list';
  $handler->display->display_options['menu']['type'] = 'default tab';
  $handler->display->display_options['menu']['title'] = 'Animals';
  $handler->display->display_options['menu']['weight'] = '-10';
  $handler->display->display_options['menu']['name'] = 'farm';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;
  $handler->display->display_options['tab_options']['type'] = 'normal';
  $handler->display->display_options['tab_options']['title'] = 'Animals';
  $handler->display->display_options['tab_options']['weight'] = '10';
  $handler->display->display_options['tab_options']['name'] = 'farm';
  $export['farm_animals'] = $view;

  return $export;
}
