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
  $view->name = 'farm_animal_types';
  $view->description = '';
  $view->tag = 'farm_crop';
  $view->base_table = 'taxonomy_term_data';
  $view->human_name = 'Farm Animal Species/Breeds';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Animal Species/Breeds';
  $handler->display->display_options['css_class'] = 'views-tree';
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
  $handler->display->display_options['header']['area_text_custom']['content'] = '<h4>Hierarchy <small><a href="/admin/structure/taxonomy/farm_animal_types?destination=farm/assets/animals/types">(change)</a></small></h4>';
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
  /* Relationship: Taxonomy term: Farm asset using Species/breed */
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
  $handler->display->display_options['menu']['title'] = 'Species/Breeds';
  $handler->display->display_options['menu']['weight'] = '0';
  $handler->display->display_options['menu']['name'] = 'farm';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;
  $translatables['farm_animal_types'] = array(
    t('Master'),
    t('Animal Species/Breeds'),
    t('more'),
    t('Apply'),
    t('Reset'),
    t('Sort by'),
    t('Asc'),
    t('Desc'),
    t('<h4>Hierarchy <small><a href="/admin/structure/taxonomy/farm_animal_types?destination=farm/assets/animals/types">(change)</a></small></h4>'),
    t('No types found.'),
    t('Parent'),
    t('field_farm_animal_type'),
    t('Term ID'),
    t('.'),
    t('Parent Term ID'),
    t(','),
    t('('),
    t(')'),
    t('Page'),
  );
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
  $handler->display->display_options['pager']['options']['expose']['items_per_page'] = TRUE;
  $handler->display->display_options['pager']['options']['expose']['items_per_page_options'] = '25, 50, 100, 250, 500';
  $handler->display->display_options['pager']['options']['expose']['items_per_page_options_all'] = TRUE;
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['columns'] = array(
    'views_bulk_operations' => 'views_bulk_operations',
    'id' => 'id',
    'field_farm_images' => 'field_farm_images',
    'name' => 'name',
    'field_farm_animal_type' => 'field_farm_animal_type',
    'inventory' => 'inventory',
    'field_farm_date' => 'field_farm_date',
    'field_farm_animal_sex' => 'field_farm_animal_sex',
    'field_farm_animal_castrated' => 'field_farm_animal_castrated',
    'field_farm_animal_tag_id' => 'field_farm_animal_tag_id',
    'field_farm_animal_tag_type' => 'field_farm_animal_tag_type',
    'field_farm_animal_tag_location' => 'field_farm_animal_tag_location',
    'field_farm_description' => 'field_farm_description',
    'field_farm_flags' => 'field_farm_flags',
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
    'field_farm_animal_type' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'inventory' => array(
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
    'field_farm_animal_sex' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_animal_castrated' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_animal_tag_id' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_animal_tag_type' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_animal_tag_location' => array(
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
    'field_farm_flags' => array(
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
  $handler->display->display_options['empty']['area_text_custom']['content'] = 'No animals found.';
  /* Relationship: Farm asset: Latest movement */
  $handler->display->display_options['relationships']['latest_movement']['id'] = 'latest_movement';
  $handler->display->display_options['relationships']['latest_movement']['table'] = 'farm_asset';
  $handler->display->display_options['relationships']['latest_movement']['field'] = 'latest_movement';
  /* Relationship: Farm asset: Group membership */
  $handler->display->display_options['relationships']['membership']['id'] = 'membership';
  $handler->display->display_options['relationships']['membership']['table'] = 'farm_asset';
  $handler->display->display_options['relationships']['membership']['field'] = 'membership';
  /* Relationship: Farm asset: ID Tag(s) (field_farm_animal_tag) */
  $handler->display->display_options['relationships']['field_farm_animal_tag_value']['id'] = 'field_farm_animal_tag_value';
  $handler->display->display_options['relationships']['field_farm_animal_tag_value']['table'] = 'field_data_field_farm_animal_tag';
  $handler->display->display_options['relationships']['field_farm_animal_tag_value']['field'] = 'field_farm_animal_tag_value';
  $handler->display->display_options['relationships']['field_farm_animal_tag_value']['delta'] = '0';
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
  /* Field: Farm asset: Species/breed */
  $handler->display->display_options['fields']['field_farm_animal_type']['id'] = 'field_farm_animal_type';
  $handler->display->display_options['fields']['field_farm_animal_type']['table'] = 'field_data_field_farm_animal_type';
  $handler->display->display_options['fields']['field_farm_animal_type']['field'] = 'field_farm_animal_type';
  /* Field: Farm asset: Asset inventory value */
  $handler->display->display_options['fields']['inventory']['id'] = 'inventory';
  $handler->display->display_options['fields']['inventory']['table'] = 'farm_asset';
  $handler->display->display_options['fields']['inventory']['field'] = 'inventory';
  $handler->display->display_options['fields']['inventory']['label'] = 'Inventory';
  /* Field: Farm asset: Date of birth */
  $handler->display->display_options['fields']['field_farm_date']['id'] = 'field_farm_date';
  $handler->display->display_options['fields']['field_farm_date']['table'] = 'field_data_field_farm_date';
  $handler->display->display_options['fields']['field_farm_date']['field'] = 'field_farm_date';
  $handler->display->display_options['fields']['field_farm_date']['settings'] = array(
    'format_type' => 'short',
    'custom_date_format' => '',
    'fromto' => 'both',
    'multiple_number' => '',
    'multiple_from' => '',
    'multiple_to' => '',
    'show_remaining_days' => 0,
  );
  /* Field: Farm asset: Sex */
  $handler->display->display_options['fields']['field_farm_animal_sex']['id'] = 'field_farm_animal_sex';
  $handler->display->display_options['fields']['field_farm_animal_sex']['table'] = 'field_data_field_farm_animal_sex';
  $handler->display->display_options['fields']['field_farm_animal_sex']['field'] = 'field_farm_animal_sex';
  /* Field: Farm asset: Castrated */
  $handler->display->display_options['fields']['field_farm_animal_castrated']['id'] = 'field_farm_animal_castrated';
  $handler->display->display_options['fields']['field_farm_animal_castrated']['table'] = 'field_data_field_farm_animal_castrated';
  $handler->display->display_options['fields']['field_farm_animal_castrated']['field'] = 'field_farm_animal_castrated';
  /* Field: Field collection item: ID */
  $handler->display->display_options['fields']['field_farm_animal_tag_id']['id'] = 'field_farm_animal_tag_id';
  $handler->display->display_options['fields']['field_farm_animal_tag_id']['table'] = 'field_data_field_farm_animal_tag_id';
  $handler->display->display_options['fields']['field_farm_animal_tag_id']['field'] = 'field_farm_animal_tag_id';
  $handler->display->display_options['fields']['field_farm_animal_tag_id']['relationship'] = 'field_farm_animal_tag_value';
  $handler->display->display_options['fields']['field_farm_animal_tag_id']['label'] = 'Tag ID';
  /* Field: Field collection item: Tag Type */
  $handler->display->display_options['fields']['field_farm_animal_tag_type']['id'] = 'field_farm_animal_tag_type';
  $handler->display->display_options['fields']['field_farm_animal_tag_type']['table'] = 'field_data_field_farm_animal_tag_type';
  $handler->display->display_options['fields']['field_farm_animal_tag_type']['field'] = 'field_farm_animal_tag_type';
  $handler->display->display_options['fields']['field_farm_animal_tag_type']['relationship'] = 'field_farm_animal_tag_value';
  $handler->display->display_options['fields']['field_farm_animal_tag_type']['label'] = 'Tag type';
  /* Field: Field collection item: Body Location */
  $handler->display->display_options['fields']['field_farm_animal_tag_location']['id'] = 'field_farm_animal_tag_location';
  $handler->display->display_options['fields']['field_farm_animal_tag_location']['table'] = 'field_data_field_farm_animal_tag_location';
  $handler->display->display_options['fields']['field_farm_animal_tag_location']['field'] = 'field_farm_animal_tag_location';
  $handler->display->display_options['fields']['field_farm_animal_tag_location']['relationship'] = 'field_farm_animal_tag_value';
  $handler->display->display_options['fields']['field_farm_animal_tag_location']['label'] = 'Tag location';
  /* Field: Farm asset: Description */
  $handler->display->display_options['fields']['field_farm_description']['id'] = 'field_farm_description';
  $handler->display->display_options['fields']['field_farm_description']['table'] = 'field_data_field_farm_description';
  $handler->display->display_options['fields']['field_farm_description']['field'] = 'field_farm_description';
  $handler->display->display_options['fields']['field_farm_description']['alter']['trim_whitespace'] = TRUE;
  $handler->display->display_options['fields']['field_farm_description']['alter']['max_length'] = '140';
  $handler->display->display_options['fields']['field_farm_description']['alter']['strip_tags'] = TRUE;
  $handler->display->display_options['fields']['field_farm_description']['alter']['trim'] = TRUE;
  /* Field: Field: Flags */
  $handler->display->display_options['fields']['field_farm_flags']['id'] = 'field_farm_flags';
  $handler->display->display_options['fields']['field_farm_flags']['table'] = 'field_data_field_farm_flags';
  $handler->display->display_options['fields']['field_farm_flags']['field'] = 'field_farm_flags';
  $handler->display->display_options['fields']['field_farm_flags']['delta_offset'] = '0';
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
  /* Field: Farm asset: Archived */
  $handler->display->display_options['fields']['archived']['id'] = 'archived';
  $handler->display->display_options['fields']['archived']['table'] = 'farm_asset';
  $handler->display->display_options['fields']['archived']['field'] = 'archived';
  $handler->display->display_options['fields']['archived']['date_format'] = 'custom';
  $handler->display->display_options['fields']['archived']['custom_date_format'] = 'M j Y';
  $handler->display->display_options['fields']['archived']['second_date_format'] = 'long';
  /* Sort criterion: Farm asset: Species/breed (field_farm_animal_type) */
  $handler->display->display_options['sorts']['field_farm_animal_type_tid']['id'] = 'field_farm_animal_type_tid';
  $handler->display->display_options['sorts']['field_farm_animal_type_tid']['table'] = 'field_data_field_farm_animal_type';
  $handler->display->display_options['sorts']['field_farm_animal_type_tid']['field'] = 'field_farm_animal_type_tid';
  $handler->display->display_options['sorts']['field_farm_animal_type_tid']['exposed'] = TRUE;
  $handler->display->display_options['sorts']['field_farm_animal_type_tid']['expose']['label'] = 'Species/Breed';
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
  /* Sort criterion: Farm asset: Farm asset ID */
  $handler->display->display_options['sorts']['id']['id'] = 'id';
  $handler->display->display_options['sorts']['id']['table'] = 'farm_asset';
  $handler->display->display_options['sorts']['id']['field'] = 'id';
  $handler->display->display_options['sorts']['id']['exposed'] = TRUE;
  $handler->display->display_options['sorts']['id']['expose']['label'] = 'Asset ID';
  /* Contextual filter: Field collection item: Movement to (field_farm_move_to) */
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
  /* Contextual filter: Farm asset: Species/breed (field_farm_animal_type) */
  $handler->display->display_options['arguments']['field_farm_animal_type_tid']['id'] = 'field_farm_animal_type_tid';
  $handler->display->display_options['arguments']['field_farm_animal_type_tid']['table'] = 'field_data_field_farm_animal_type';
  $handler->display->display_options['arguments']['field_farm_animal_type_tid']['field'] = 'field_farm_animal_type_tid';
  $handler->display->display_options['arguments']['field_farm_animal_type_tid']['title_enable'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_animal_type_tid']['title'] = 'Animals of type %2';
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
  $handler->display->display_options['filters']['field_farm_animal_tag_id_value']['expose']['description'] = 'If there are multiple ID tags on an animal, this filter will only look at the first one.';
  $handler->display->display_options['filters']['field_farm_animal_tag_id_value']['expose']['operator'] = 'field_farm_animal_tag_id_value_op';
  $handler->display->display_options['filters']['field_farm_animal_tag_id_value']['expose']['identifier'] = 'field_farm_animal_tag_id_value';
  /* Filter criterion: Farm asset: Species/breed (field_farm_animal_type) */
  $handler->display->display_options['filters']['field_farm_animal_type_tid']['id'] = 'field_farm_animal_type_tid';
  $handler->display->display_options['filters']['field_farm_animal_type_tid']['table'] = 'field_data_field_farm_animal_type';
  $handler->display->display_options['filters']['field_farm_animal_type_tid']['field'] = 'field_farm_animal_type_tid';
  $handler->display->display_options['filters']['field_farm_animal_type_tid']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_animal_type_tid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_animal_type_tid']['expose']['operator_id'] = 'field_farm_animal_type_tid_op';
  $handler->display->display_options['filters']['field_farm_animal_type_tid']['expose']['label'] = 'Type';
  $handler->display->display_options['filters']['field_farm_animal_type_tid']['expose']['operator'] = 'field_farm_animal_type_tid_op';
  $handler->display->display_options['filters']['field_farm_animal_type_tid']['expose']['identifier'] = 'field_farm_animal_type_tid';
  $handler->display->display_options['filters']['field_farm_animal_type_tid']['expose']['multiple'] = TRUE;
  $handler->display->display_options['filters']['field_farm_animal_type_tid']['type'] = 'select';
  $handler->display->display_options['filters']['field_farm_animal_type_tid']['vocabulary'] = 'farm_animal_types';
  $handler->display->display_options['filters']['field_farm_animal_type_tid']['hierarchy'] = 1;
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
  /* Filter criterion: Farm asset: Castrated (field_farm_animal_castrated) */
  $handler->display->display_options['filters']['field_farm_animal_castrated_value']['id'] = 'field_farm_animal_castrated_value';
  $handler->display->display_options['filters']['field_farm_animal_castrated_value']['table'] = 'field_data_field_farm_animal_castrated';
  $handler->display->display_options['filters']['field_farm_animal_castrated_value']['field'] = 'field_farm_animal_castrated_value';
  $handler->display->display_options['filters']['field_farm_animal_castrated_value']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_animal_castrated_value']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_animal_castrated_value']['expose']['operator_id'] = 'field_farm_animal_castrated_value_op';
  $handler->display->display_options['filters']['field_farm_animal_castrated_value']['expose']['label'] = 'Castrated';
  $handler->display->display_options['filters']['field_farm_animal_castrated_value']['expose']['operator'] = 'field_farm_animal_castrated_value_op';
  $handler->display->display_options['filters']['field_farm_animal_castrated_value']['expose']['identifier'] = 'field_farm_animal_castrated_value';
  /* Filter criterion: Farm asset: Date of birth (field_farm_date) */
  $handler->display->display_options['filters']['field_farm_date_value']['id'] = 'field_farm_date_value';
  $handler->display->display_options['filters']['field_farm_date_value']['table'] = 'field_data_field_farm_date';
  $handler->display->display_options['filters']['field_farm_date_value']['field'] = 'field_farm_date_value';
  $handler->display->display_options['filters']['field_farm_date_value']['operator'] = 'between';
  $handler->display->display_options['filters']['field_farm_date_value']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_date_value']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_date_value']['expose']['operator_id'] = 'field_farm_date_value_op';
  $handler->display->display_options['filters']['field_farm_date_value']['expose']['label'] = 'Date of birth (range)';
  $handler->display->display_options['filters']['field_farm_date_value']['expose']['operator'] = 'field_farm_date_value_op';
  $handler->display->display_options['filters']['field_farm_date_value']['expose']['identifier'] = 'field_farm_date_value';
  $handler->display->display_options['filters']['field_farm_date_value']['year_range'] = '-10:+3';
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
  /* Filter criterion: Field collection item: Group (field_farm_group) */
  $handler->display->display_options['filters']['field_farm_group_target_id']['id'] = 'field_farm_group_target_id';
  $handler->display->display_options['filters']['field_farm_group_target_id']['table'] = 'field_data_field_farm_group';
  $handler->display->display_options['filters']['field_farm_group_target_id']['field'] = 'field_farm_group_target_id';
  $handler->display->display_options['filters']['field_farm_group_target_id']['relationship'] = 'membership';
  $handler->display->display_options['filters']['field_farm_group_target_id']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_group_target_id']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_group_target_id']['expose']['operator_id'] = 'field_farm_group_target_id_op';
  $handler->display->display_options['filters']['field_farm_group_target_id']['expose']['label'] = 'Group';
  $handler->display->display_options['filters']['field_farm_group_target_id']['expose']['operator'] = 'field_farm_group_target_id_op';
  $handler->display->display_options['filters']['field_farm_group_target_id']['expose']['identifier'] = 'field_farm_group_target_id';
  $handler->display->display_options['filters']['field_farm_group_target_id']['expose']['multiple'] = TRUE;
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
  /* Filter criterion: Farm asset: Archived (boolean) */
  $handler->display->display_options['filters']['archived_boolean']['id'] = 'archived_boolean';
  $handler->display->display_options['filters']['archived_boolean']['table'] = 'farm_asset';
  $handler->display->display_options['filters']['archived_boolean']['field'] = 'archived_boolean';
  $handler->display->display_options['filters']['archived_boolean']['value'] = '0';
  $handler->display->display_options['filters']['archived_boolean']['group'] = 1;
  $handler->display->display_options['filters']['archived_boolean']['exposed'] = TRUE;
  $handler->display->display_options['filters']['archived_boolean']['expose']['operator_id'] = '';
  $handler->display->display_options['filters']['archived_boolean']['expose']['label'] = 'Archived';
  $handler->display->display_options['filters']['archived_boolean']['expose']['operator'] = 'archived_boolean_op';
  $handler->display->display_options['filters']['archived_boolean']['expose']['identifier'] = 'archived_boolean';

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
  $handler->display->display_options['path'] = 'farm/assets/animals/csv';
  $handler->display->display_options['displays'] = array(
    'page' => 'page',
    'default' => 0,
  );
  $translatables['farm_animals'] = array(
    t('Master'),
    t('Animals'),
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
    t('No animals found.'),
    t('Latest movement'),
    t('Group membership'),
    t('field collection item from field_farm_animal_tag'),
    t('Farm asset'),
    t('- Choose an operation -'),
    t('Delete'),
    t('Asset ID'),
    t('.'),
    t('Name'),
    t('Species/breed'),
    t('Inventory'),
    t('Date of birth'),
    t('Sex'),
    t('Castrated'),
    t('Tag ID'),
    t('Tag type'),
    t('Tag location'),
    t('Description'),
    t('Flags'),
    t('Group'),
    t('Location'),
    t('Archived'),
    t('Species/Breed'),
    t('ID Tag'),
    t('All'),
    t('Animals in %1'),
    t('Animals of type %2'),
    t('If there are multiple ID tags on an animal, this filter will only look at the first one.'),
    t('Type'),
    t('Date of birth (range)'),
    t('Page'),
    t('Data export'),
  );
  $export['farm_animals'] = $view;

  $view = new view();
  $view->name = 'farm_log_birth';
  $view->description = '';
  $view->tag = 'farm_log';
  $view->base_table = 'log';
  $view->human_name = 'Farm Log: Birth';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Births';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['access']['perm'] = 'view any farm_birth log entities';
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
    'field_farm_mother' => 'field_farm_mother',
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
    'field_farm_mother' => array(
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
  $handler->display->display_options['empty']['area_text_custom']['content'] = 'No birth logs found.';
  /* Relationship: Entity Reference: Referenced Entity */
  $handler->display->display_options['relationships']['field_farm_mother_target_id']['id'] = 'field_farm_mother_target_id';
  $handler->display->display_options['relationships']['field_farm_mother_target_id']['table'] = 'field_data_field_farm_mother';
  $handler->display->display_options['relationships']['field_farm_mother_target_id']['field'] = 'field_farm_mother_target_id';
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
  /* Field: Log: Mother */
  $handler->display->display_options['fields']['field_farm_mother']['id'] = 'field_farm_mother';
  $handler->display->display_options['fields']['field_farm_mother']['table'] = 'field_data_field_farm_mother';
  $handler->display->display_options['fields']['field_farm_mother']['field'] = 'field_farm_mother';
  $handler->display->display_options['fields']['field_farm_mother']['settings'] = array(
    'bypass_access' => 0,
    'link' => 1,
  );
  /* Field: Log: Assets */
  $handler->display->display_options['fields']['field_farm_asset']['id'] = 'field_farm_asset';
  $handler->display->display_options['fields']['field_farm_asset']['table'] = 'field_data_field_farm_asset';
  $handler->display->display_options['fields']['field_farm_asset']['field'] = 'field_farm_asset';
  $handler->display->display_options['fields']['field_farm_asset']['label'] = 'Children';
  $handler->display->display_options['fields']['field_farm_asset']['settings'] = array(
    'bypass_access' => 0,
    'link' => 1,
  );
  $handler->display->display_options['fields']['field_farm_asset']['delta_offset'] = '0';
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
  $handler->display->display_options['arguments']['field_farm_asset_target_id']['title'] = '%1\'s Birth';
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
  $handler->display->display_options['arguments']['field_farm_area_tid']['title'] = 'Births in %2';
  $handler->display->display_options['arguments']['field_farm_area_tid']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['field_farm_area_tid']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['field_farm_area_tid']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['field_farm_area_tid']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['arguments']['field_farm_area_tid']['specify_validation'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_area_tid']['validate']['type'] = 'taxonomy_term';
  $handler->display->display_options['arguments']['field_farm_area_tid']['validate_options']['vocabularies'] = array(
    'farm_areas' => 'farm_areas',
  );
  /* Contextual filter: Log: Mother (field_farm_mother) */
  $handler->display->display_options['arguments']['field_farm_mother_target_id']['id'] = 'field_farm_mother_target_id';
  $handler->display->display_options['arguments']['field_farm_mother_target_id']['table'] = 'field_data_field_farm_mother';
  $handler->display->display_options['arguments']['field_farm_mother_target_id']['field'] = 'field_farm_mother_target_id';
  $handler->display->display_options['arguments']['field_farm_mother_target_id']['title_enable'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_mother_target_id']['title'] = 'Births by %3';
  $handler->display->display_options['arguments']['field_farm_mother_target_id']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['field_farm_mother_target_id']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['field_farm_mother_target_id']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['field_farm_mother_target_id']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['arguments']['field_farm_mother_target_id']['specify_validation'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_mother_target_id']['validate']['type'] = 'farm_asset';
  $handler->display->display_options['arguments']['field_farm_mother_target_id']['validate_options']['types'] = array(
    'animal' => 'animal',
  );
  /* Filter criterion: Log: Log type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'log';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'farm_birth' => 'farm_birth',
  );
  $handler->display->display_options['filters']['type']['group'] = 1;
  /* Filter criterion: Farm asset: Name */
  $handler->display->display_options['filters']['name_1']['id'] = 'name_1';
  $handler->display->display_options['filters']['name_1']['table'] = 'farm_asset';
  $handler->display->display_options['filters']['name_1']['field'] = 'name';
  $handler->display->display_options['filters']['name_1']['relationship'] = 'field_farm_mother_target_id';
  $handler->display->display_options['filters']['name_1']['operator'] = 'contains';
  $handler->display->display_options['filters']['name_1']['group'] = 1;
  $handler->display->display_options['filters']['name_1']['exposed'] = TRUE;
  $handler->display->display_options['filters']['name_1']['expose']['operator_id'] = 'name_1_op';
  $handler->display->display_options['filters']['name_1']['expose']['label'] = 'Mother';
  $handler->display->display_options['filters']['name_1']['expose']['operator'] = 'name_1_op';
  $handler->display->display_options['filters']['name_1']['expose']['identifier'] = 'name_1';
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
  $handler->display->display_options['path'] = 'farm/logs/births';
  $handler->display->display_options['menu']['type'] = 'normal';
  $handler->display->display_options['menu']['title'] = 'Births';
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
  $handler->display->display_options['path'] = 'farm/logs/births/csv';
  $handler->display->display_options['displays'] = array(
    'page' => 'page',
    'default' => 0,
  );
  $translatables['farm_log_birth'] = array(
    t('Master'),
    t('Births'),
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
    t('No birth logs found.'),
    t('Farm asset entity referenced from field_farm_mother'),
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
    t('Mother'),
    t('Children'),
    t('Areas'),
    t('Notes'),
    t('Flags'),
    t('Categories'),
    t('Assigned to'),
    t('All'),
    t('%1\'s Birth'),
    t('Births in %2'),
    t('Births by %3'),
    t('Start date'),
    t('End date'),
    t('Page'),
    t('Data export'),
  );
  $export['farm_log_birth'] = $view;

  $view = new view();
  $view->name = 'farm_log_medical';
  $view->description = '';
  $view->tag = 'farm_log';
  $view->base_table = 'log';
  $view->human_name = 'Farm Log: Medical';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Medical Records';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['access']['perm'] = 'view any farm_medical log entities';
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
  $handler->display->display_options['empty']['area_text_custom']['content'] = 'No medical logs found.';
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
  $handler->display->display_options['fields']['field_farm_asset']['delta_offset'] = '0';
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
  $handler->display->display_options['arguments']['field_farm_asset_target_id']['title'] = 'Medical records for %1';
  $handler->display->display_options['arguments']['field_farm_asset_target_id']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['field_farm_asset_target_id']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['field_farm_asset_target_id']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['field_farm_asset_target_id']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['arguments']['field_farm_asset_target_id']['specify_validation'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_asset_target_id']['validate']['type'] = 'farm_asset';
  /* Filter criterion: Log: Log type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'log';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'farm_medical' => 'farm_medical',
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
  $handler->display->display_options['path'] = 'farm/logs/medical';
  $handler->display->display_options['menu']['type'] = 'normal';
  $handler->display->display_options['menu']['title'] = 'Medical';
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
  $handler->display->display_options['path'] = 'farm/logs/medical/csv';
  $handler->display->display_options['displays'] = array(
    'page' => 'page',
    'default' => 0,
  );
  $translatables['farm_log_medical'] = array(
    t('Master'),
    t('Medical Records'),
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
    t('No medical logs found.'),
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
    t('Notes'),
    t('Flags'),
    t('Categories'),
    t('Assigned to'),
    t('All'),
    t('Medical records for %1'),
    t('Start date'),
    t('End date'),
    t('Page'),
    t('Data export'),
  );
  $export['farm_log_medical'] = $view;

  return $export;
}
