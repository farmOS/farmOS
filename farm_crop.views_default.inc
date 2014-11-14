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
  $view->tag = 'default';
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
  $handler->display->display_options['pager']['options']['items_per_page'] = '100';
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['columns'] = array(
    'tid' => 'tid',
    'name' => 'name',
    'field_farm_crop_family' => 'field_farm_crop_family',
    'field_farm_transplant_days' => 'field_farm_transplant_days',
    'field_farm_maturity_days' => 'field_farm_maturity_days',
    'field_farm_companions' => 'field_farm_companions',
    'nothing' => 'nothing',
  );
  $handler->display->display_options['style_options']['default'] = 'name';
  $handler->display->display_options['style_options']['info'] = array(
    'tid' => array(
      'sortable' => 0,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'name' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_crop_family' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_transplant_days' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_maturity_days' => array(
      'sortable' => 1,
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
    'nothing' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
  );
  $handler->display->display_options['style_options']['empty_table'] = TRUE;
  /* Footer: Global: Unfiltered text */
  $handler->display->display_options['footer']['area_text_custom']['id'] = 'area_text_custom';
  $handler->display->display_options['footer']['area_text_custom']['table'] = 'views';
  $handler->display->display_options['footer']['area_text_custom']['field'] = 'area_text_custom';
  $handler->display->display_options['footer']['area_text_custom']['empty'] = TRUE;
  $handler->display->display_options['footer']['area_text_custom']['content'] = '<a href="/admin/structure/taxonomy/farm_crops/add">+ Add a crop</a>';
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
  /* Field: Global: Custom text */
  $handler->display->display_options['fields']['nothing']['id'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['table'] = 'views';
  $handler->display->display_options['fields']['nothing']['field'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['label'] = 'Links';
  $handler->display->display_options['fields']['nothing']['alter']['text'] = '<a href="/admin/farm/crops/[tid]/plantings">plantings</a> | <a href="/admin/farm/crops/[tid]/inputs">inputs</a> | <a href="/admin/farm/crops/[tid]/cultivations">cultivations</a> | <a href="/admin/farm/crops/[tid]/harvests">harvests</a> | <a href="/admin/farm/crops/[tid]/issues">issues</a> (<a href="/taxonomy/term/[tid]/edit">edit crop</a>)';
  /* Filter criterion: Taxonomy term: Vocabulary */
  $handler->display->display_options['filters']['vid']['id'] = 'vid';
  $handler->display->display_options['filters']['vid']['table'] = 'taxonomy_term_data';
  $handler->display->display_options['filters']['vid']['field'] = 'vid';
  $handler->display->display_options['filters']['vid']['value'] = array(
    3 => '3',
  );

  /* Display: Crops list (Page) */
  $handler = $view->new_display('page', 'Crops list (Page)', 'page');
  $handler->display->display_options['defaults']['filter_groups'] = FALSE;
  $handler->display->display_options['defaults']['filters'] = FALSE;
  /* Filter criterion: Taxonomy term: Vocabulary */
  $handler->display->display_options['filters']['vid']['id'] = 'vid';
  $handler->display->display_options['filters']['vid']['table'] = 'taxonomy_term_data';
  $handler->display->display_options['filters']['vid']['field'] = 'vid';
  $handler->display->display_options['filters']['vid']['value'] = array(
    3 => '3',
  );
  $handler->display->display_options['filters']['vid']['group'] = 1;
  /* Filter criterion: Taxonomy term: Name */
  $handler->display->display_options['filters']['name']['id'] = 'name';
  $handler->display->display_options['filters']['name']['table'] = 'taxonomy_term_data';
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
  /* Filter criterion: Taxonomy term: Crop family (field_farm_crop_family) */
  $handler->display->display_options['filters']['field_farm_crop_family_tid']['id'] = 'field_farm_crop_family_tid';
  $handler->display->display_options['filters']['field_farm_crop_family_tid']['table'] = 'field_data_field_farm_crop_family';
  $handler->display->display_options['filters']['field_farm_crop_family_tid']['field'] = 'field_farm_crop_family_tid';
  $handler->display->display_options['filters']['field_farm_crop_family_tid']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_crop_family_tid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_crop_family_tid']['expose']['operator_id'] = 'field_farm_crop_family_tid_op';
  $handler->display->display_options['filters']['field_farm_crop_family_tid']['expose']['label'] = 'Crop family';
  $handler->display->display_options['filters']['field_farm_crop_family_tid']['expose']['operator'] = 'field_farm_crop_family_tid_op';
  $handler->display->display_options['filters']['field_farm_crop_family_tid']['expose']['identifier'] = 'field_farm_crop_family_tid';
  $handler->display->display_options['filters']['field_farm_crop_family_tid']['expose']['multiple'] = TRUE;
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
  /* Filter criterion: Taxonomy term: Companions (field_farm_companions) */
  $handler->display->display_options['filters']['field_farm_companions_tid']['id'] = 'field_farm_companions_tid';
  $handler->display->display_options['filters']['field_farm_companions_tid']['table'] = 'field_data_field_farm_companions';
  $handler->display->display_options['filters']['field_farm_companions_tid']['field'] = 'field_farm_companions_tid';
  $handler->display->display_options['filters']['field_farm_companions_tid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_companions_tid']['expose']['operator_id'] = 'field_farm_companions_tid_op';
  $handler->display->display_options['filters']['field_farm_companions_tid']['expose']['label'] = 'Companions';
  $handler->display->display_options['filters']['field_farm_companions_tid']['expose']['operator'] = 'field_farm_companions_tid_op';
  $handler->display->display_options['filters']['field_farm_companions_tid']['expose']['identifier'] = 'field_farm_companions_tid';
  $handler->display->display_options['filters']['field_farm_companions_tid']['expose']['multiple'] = TRUE;
  $handler->display->display_options['filters']['field_farm_companions_tid']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    30037204 => 0,
    200153887 => 0,
    125843996 => 0,
    173712484 => 0,
    196317508 => 0,
    146437786 => 0,
  );
  $handler->display->display_options['filters']['field_farm_companions_tid']['type'] = 'select';
  $handler->display->display_options['filters']['field_farm_companions_tid']['vocabulary'] = 'farm_crops';
  $handler->display->display_options['filters']['field_farm_companions_tid']['hierarchy'] = 1;
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

  /* Display: Crops dashboard (Content pane) */
  $handler = $view->new_display('panel_pane', 'Crops dashboard (Content pane)', 'panel_pane_1');
  $handler->display->display_options['defaults']['fields'] = FALSE;
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
  /* Field: Global: Custom text */
  $handler->display->display_options['fields']['nothing']['id'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['table'] = 'views';
  $handler->display->display_options['fields']['nothing']['field'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['label'] = 'Links';
  $handler->display->display_options['fields']['nothing']['alter']['text'] = '<a href="/admin/farm/crops/[tid]/plantings">plantings</a> | <a href="/admin/farm/crops/[tid]/inputs">inputs</a> | <a href="/admin/farm/crops/[tid]/cultivations">cultivations</a> | <a href="/admin/farm/crops/[tid]/harvests">harvests</a> | <a href="/admin/farm/crops/[tid]/issues">issues</a> (<a href="/taxonomy/term/[tid]/edit">edit crop</a>)';
  $handler->display->display_options['link_to_view'] = '0';
  $export['farm_crops'] = $view;

  $view = new view();
  $view->name = 'farm_plantings';
  $view->description = '';
  $view->tag = 'default';
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
    'field_farm_crop' => 'field_farm_crop',
    'field_farm_area' => 'field_farm_area',
    'field_farm_quantity' => 'field_farm_quantity',
    'field_farm_season' => 'field_farm_season',
    'nothing' => 'nothing',
  );
  $handler->display->display_options['style_options']['default'] = '-1';
  $handler->display->display_options['style_options']['info'] = array(
    'id' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_crop' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_area' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_quantity' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_season' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'nothing' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
  );
  /* Header: Global: Unfiltered text */
  $handler->display->display_options['header']['area_text_custom']['id'] = 'area_text_custom';
  $handler->display->display_options['header']['area_text_custom']['table'] = 'views';
  $handler->display->display_options['header']['area_text_custom']['field'] = 'area_text_custom';
  $handler->display->display_options['header']['area_text_custom']['content'] = '<a href="/farm/asset/add/planting?destination=admin/farm/crops/plantings">+ Create a planting</a>';
  /* No results behavior: Global: Unfiltered text */
  $handler->display->display_options['empty']['area_text_custom']['id'] = 'area_text_custom';
  $handler->display->display_options['empty']['area_text_custom']['table'] = 'views';
  $handler->display->display_options['empty']['area_text_custom']['field'] = 'area_text_custom';
  $handler->display->display_options['empty']['area_text_custom']['empty'] = TRUE;
  $handler->display->display_options['empty']['area_text_custom']['content'] = 'No plantings found.';
  /* Relationship: Field: Location (field_farm_planting_location) */
  $handler->display->display_options['relationships']['field_farm_planting_location_value']['id'] = 'field_farm_planting_location_value';
  $handler->display->display_options['relationships']['field_farm_planting_location_value']['table'] = 'field_data_field_farm_planting_location';
  $handler->display->display_options['relationships']['field_farm_planting_location_value']['field'] = 'field_farm_planting_location_value';
  $handler->display->display_options['relationships']['field_farm_planting_location_value']['label'] = 'location';
  $handler->display->display_options['relationships']['field_farm_planting_location_value']['required'] = TRUE;
  $handler->display->display_options['relationships']['field_farm_planting_location_value']['delta'] = '-1';
  /* Field: Farm asset: Farm asset ID */
  $handler->display->display_options['fields']['id']['id'] = 'id';
  $handler->display->display_options['fields']['id']['table'] = 'farm_asset';
  $handler->display->display_options['fields']['id']['field'] = 'id';
  $handler->display->display_options['fields']['id']['exclude'] = TRUE;
  /* Field: Field: Crop */
  $handler->display->display_options['fields']['field_farm_crop']['id'] = 'field_farm_crop';
  $handler->display->display_options['fields']['field_farm_crop']['table'] = 'field_data_field_farm_crop';
  $handler->display->display_options['fields']['field_farm_crop']['field'] = 'field_farm_crop';
  /* Field: Field: Area */
  $handler->display->display_options['fields']['field_farm_area']['id'] = 'field_farm_area';
  $handler->display->display_options['fields']['field_farm_area']['table'] = 'field_data_field_farm_area';
  $handler->display->display_options['fields']['field_farm_area']['field'] = 'field_farm_area';
  $handler->display->display_options['fields']['field_farm_area']['relationship'] = 'field_farm_planting_location_value';
  /* Field: Field: Quantity */
  $handler->display->display_options['fields']['field_farm_quantity']['id'] = 'field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['table'] = 'field_data_field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['field'] = 'field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['relationship'] = 'field_farm_planting_location_value';
  $handler->display->display_options['fields']['field_farm_quantity']['type'] = 'field_collection_fields';
  $handler->display->display_options['fields']['field_farm_quantity']['settings'] = array(
    'view_mode' => 'full',
  );
  /* Field: Field: Season */
  $handler->display->display_options['fields']['field_farm_season']['id'] = 'field_farm_season';
  $handler->display->display_options['fields']['field_farm_season']['table'] = 'field_data_field_farm_season';
  $handler->display->display_options['fields']['field_farm_season']['field'] = 'field_farm_season';
  /* Field: Global: Custom text */
  $handler->display->display_options['fields']['nothing']['id'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['table'] = 'views';
  $handler->display->display_options['fields']['nothing']['field'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['label'] = 'Operations';
  $handler->display->display_options['fields']['nothing']['alter']['text'] = '<a href="/farm/asset/[id]/edit?destination=admin/farm/crops/plantings">edit</a> | <a href="/farm/asset/[id]/delete?destination=admin/farm/crops/plantings">delete</a>';
  /* Filter criterion: Farm asset: Farm asset type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'farm_asset';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'planting' => 'planting',
  );
  $handler->display->display_options['filters']['type']['group'] = 1;
  /* Filter criterion: Field: Date range -  start date (field_farm_planting_date_range) */
  $handler->display->display_options['filters']['field_farm_planting_date_range_value']['id'] = 'field_farm_planting_date_range_value';
  $handler->display->display_options['filters']['field_farm_planting_date_range_value']['table'] = 'field_data_field_farm_planting_date_range';
  $handler->display->display_options['filters']['field_farm_planting_date_range_value']['field'] = 'field_farm_planting_date_range_value';
  $handler->display->display_options['filters']['field_farm_planting_date_range_value']['relationship'] = 'field_farm_planting_location_value';
  $handler->display->display_options['filters']['field_farm_planting_date_range_value']['operator'] = '<=';
  $handler->display->display_options['filters']['field_farm_planting_date_range_value']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_planting_date_range_value']['default_date'] = 'now';
  /* Filter criterion: Field: Date range - end date (field_farm_planting_date_range:value2) */
  $handler->display->display_options['filters']['field_farm_planting_date_range_value2']['id'] = 'field_farm_planting_date_range_value2';
  $handler->display->display_options['filters']['field_farm_planting_date_range_value2']['table'] = 'field_data_field_farm_planting_date_range';
  $handler->display->display_options['filters']['field_farm_planting_date_range_value2']['field'] = 'field_farm_planting_date_range_value2';
  $handler->display->display_options['filters']['field_farm_planting_date_range_value2']['relationship'] = 'field_farm_planting_location_value';
  $handler->display->display_options['filters']['field_farm_planting_date_range_value2']['operator'] = '>=';
  $handler->display->display_options['filters']['field_farm_planting_date_range_value2']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_planting_date_range_value2']['default_date'] = 'now';
  /* Filter criterion: Field: Crop (field_farm_crop) */
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
  /* Filter criterion: Field: Area (field_farm_area) */
  $handler->display->display_options['filters']['field_farm_area_tid']['id'] = 'field_farm_area_tid';
  $handler->display->display_options['filters']['field_farm_area_tid']['table'] = 'field_data_field_farm_area';
  $handler->display->display_options['filters']['field_farm_area_tid']['field'] = 'field_farm_area_tid';
  $handler->display->display_options['filters']['field_farm_area_tid']['relationship'] = 'field_farm_planting_location_value';
  $handler->display->display_options['filters']['field_farm_area_tid']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_area_tid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_area_tid']['expose']['operator_id'] = 'field_farm_area_tid_op';
  $handler->display->display_options['filters']['field_farm_area_tid']['expose']['label'] = 'Location';
  $handler->display->display_options['filters']['field_farm_area_tid']['expose']['operator'] = 'field_farm_area_tid_op';
  $handler->display->display_options['filters']['field_farm_area_tid']['expose']['identifier'] = 'field_farm_area_tid';
  $handler->display->display_options['filters']['field_farm_area_tid']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    30037204 => 0,
    200153887 => 0,
    125843996 => 0,
    173712484 => 0,
    196317508 => 0,
    146437786 => 0,
  );
  $handler->display->display_options['filters']['field_farm_area_tid']['type'] = 'select';
  $handler->display->display_options['filters']['field_farm_area_tid']['vocabulary'] = 'farm_areas';
  $handler->display->display_options['filters']['field_farm_area_tid']['hierarchy'] = 1;

  /* Display: All plantings (Page) */
  $handler = $view->new_display('page', 'All plantings (Page)', 'page');
  $handler->display->display_options['path'] = 'admin/farm/crops/plantings';
  $handler->display->display_options['menu']['type'] = 'tab';
  $handler->display->display_options['menu']['title'] = 'Plantings';
  $handler->display->display_options['menu']['weight'] = '0';
  $handler->display->display_options['menu']['name'] = 'management';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;

  /* Display: Plantings by area (Page) */
  $handler = $view->new_display('page', 'Plantings by area (Page)', 'page_1');
  $handler->display->display_options['defaults']['style_plugin'] = FALSE;
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['columns'] = array(
    'id' => 'id',
    'field_farm_crop' => 'field_farm_crop',
    'field_farm_quantity' => 'field_farm_quantity',
    'field_farm_season' => 'field_farm_season',
    'nothing' => 'nothing',
  );
  $handler->display->display_options['style_options']['default'] = '-1';
  $handler->display->display_options['style_options']['info'] = array(
    'id' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_crop' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_quantity' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_season' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'nothing' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
  );
  $handler->display->display_options['defaults']['style_options'] = FALSE;
  $handler->display->display_options['defaults']['row_plugin'] = FALSE;
  $handler->display->display_options['defaults']['row_options'] = FALSE;
  $handler->display->display_options['defaults']['fields'] = FALSE;
  /* Field: Farm asset: Farm asset ID */
  $handler->display->display_options['fields']['id']['id'] = 'id';
  $handler->display->display_options['fields']['id']['table'] = 'farm_asset';
  $handler->display->display_options['fields']['id']['field'] = 'id';
  $handler->display->display_options['fields']['id']['exclude'] = TRUE;
  /* Field: Field: Crop */
  $handler->display->display_options['fields']['field_farm_crop']['id'] = 'field_farm_crop';
  $handler->display->display_options['fields']['field_farm_crop']['table'] = 'field_data_field_farm_crop';
  $handler->display->display_options['fields']['field_farm_crop']['field'] = 'field_farm_crop';
  /* Field: Field: Quantity */
  $handler->display->display_options['fields']['field_farm_quantity']['id'] = 'field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['table'] = 'field_data_field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['field'] = 'field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['relationship'] = 'field_farm_planting_location_value';
  $handler->display->display_options['fields']['field_farm_quantity']['type'] = 'field_collection_fields';
  $handler->display->display_options['fields']['field_farm_quantity']['settings'] = array(
    'view_mode' => 'full',
  );
  /* Field: Field: Season */
  $handler->display->display_options['fields']['field_farm_season']['id'] = 'field_farm_season';
  $handler->display->display_options['fields']['field_farm_season']['table'] = 'field_data_field_farm_season';
  $handler->display->display_options['fields']['field_farm_season']['field'] = 'field_farm_season';
  /* Field: Global: Custom text */
  $handler->display->display_options['fields']['nothing']['id'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['table'] = 'views';
  $handler->display->display_options['fields']['nothing']['field'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['label'] = 'Operations';
  $handler->display->display_options['fields']['nothing']['alter']['text'] = '<a href="/farm/asset/[id]/edit?destination=admin/farm/crops/plantings">edit</a> | <a href="/farm/asset/[id]/delete?destination=admin/farm/crops/plantings">delete</a>';
  $handler->display->display_options['defaults']['arguments'] = FALSE;
  /* Contextual filter: Field: Area (field_farm_area) */
  $handler->display->display_options['arguments']['field_farm_area_tid']['id'] = 'field_farm_area_tid';
  $handler->display->display_options['arguments']['field_farm_area_tid']['table'] = 'field_data_field_farm_area';
  $handler->display->display_options['arguments']['field_farm_area_tid']['field'] = 'field_farm_area_tid';
  $handler->display->display_options['arguments']['field_farm_area_tid']['relationship'] = 'field_farm_planting_location_value';
  $handler->display->display_options['arguments']['field_farm_area_tid']['default_action'] = 'not found';
  $handler->display->display_options['arguments']['field_farm_area_tid']['title_enable'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_area_tid']['title'] = 'Plantings in %1';
  $handler->display->display_options['arguments']['field_farm_area_tid']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['field_farm_area_tid']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['field_farm_area_tid']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['field_farm_area_tid']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['arguments']['field_farm_area_tid']['specify_validation'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_area_tid']['validate']['type'] = 'taxonomy_term';
  $handler->display->display_options['arguments']['field_farm_area_tid']['validate_options']['vocabularies'] = array(
    'farm_areas' => 'farm_areas',
  );
  $handler->display->display_options['defaults']['filter_groups'] = FALSE;
  $handler->display->display_options['defaults']['filters'] = FALSE;
  /* Filter criterion: Farm asset: Farm asset type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'farm_asset';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'planting' => 'planting',
  );
  $handler->display->display_options['filters']['type']['group'] = 1;
  /* Filter criterion: Field: Date range -  start date (field_farm_planting_date_range) */
  $handler->display->display_options['filters']['field_farm_planting_date_range_value']['id'] = 'field_farm_planting_date_range_value';
  $handler->display->display_options['filters']['field_farm_planting_date_range_value']['table'] = 'field_data_field_farm_planting_date_range';
  $handler->display->display_options['filters']['field_farm_planting_date_range_value']['field'] = 'field_farm_planting_date_range_value';
  $handler->display->display_options['filters']['field_farm_planting_date_range_value']['relationship'] = 'field_farm_planting_location_value';
  $handler->display->display_options['filters']['field_farm_planting_date_range_value']['operator'] = '<=';
  $handler->display->display_options['filters']['field_farm_planting_date_range_value']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_planting_date_range_value']['default_date'] = 'now';
  /* Filter criterion: Field: Date range - end date (field_farm_planting_date_range:value2) */
  $handler->display->display_options['filters']['field_farm_planting_date_range_value2']['id'] = 'field_farm_planting_date_range_value2';
  $handler->display->display_options['filters']['field_farm_planting_date_range_value2']['table'] = 'field_data_field_farm_planting_date_range';
  $handler->display->display_options['filters']['field_farm_planting_date_range_value2']['field'] = 'field_farm_planting_date_range_value2';
  $handler->display->display_options['filters']['field_farm_planting_date_range_value2']['relationship'] = 'field_farm_planting_location_value';
  $handler->display->display_options['filters']['field_farm_planting_date_range_value2']['operator'] = '>=';
  $handler->display->display_options['filters']['field_farm_planting_date_range_value2']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_planting_date_range_value2']['default_date'] = 'now';
  /* Filter criterion: Field: Crop (field_farm_crop) */
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
  $handler->display->display_options['path'] = 'taxonomy/term/%/plantings';
  $handler->display->display_options['menu']['title'] = 'Plantings';
  $handler->display->display_options['menu']['weight'] = '0';
  $handler->display->display_options['menu']['name'] = 'management';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;

  /* Display: Plantings by crop (Page) */
  $handler = $view->new_display('page', 'Plantings by crop (Page)', 'page_2');
  $handler->display->display_options['defaults']['style_plugin'] = FALSE;
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['columns'] = array(
    'id' => 'id',
    'field_farm_area' => 'field_farm_area',
    'field_farm_quantity' => 'field_farm_quantity',
    'field_farm_season' => 'field_farm_season',
    'nothing' => 'nothing',
  );
  $handler->display->display_options['style_options']['default'] = '-1';
  $handler->display->display_options['style_options']['info'] = array(
    'id' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_area' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_quantity' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'field_farm_season' => array(
      'sortable' => 1,
      'default_sort_order' => 'asc',
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
    'nothing' => array(
      'align' => '',
      'separator' => '',
      'empty_column' => 0,
    ),
  );
  $handler->display->display_options['defaults']['style_options'] = FALSE;
  $handler->display->display_options['defaults']['row_plugin'] = FALSE;
  $handler->display->display_options['defaults']['row_options'] = FALSE;
  $handler->display->display_options['defaults']['fields'] = FALSE;
  /* Field: Farm asset: Farm asset ID */
  $handler->display->display_options['fields']['id']['id'] = 'id';
  $handler->display->display_options['fields']['id']['table'] = 'farm_asset';
  $handler->display->display_options['fields']['id']['field'] = 'id';
  $handler->display->display_options['fields']['id']['exclude'] = TRUE;
  /* Field: Field: Area */
  $handler->display->display_options['fields']['field_farm_area']['id'] = 'field_farm_area';
  $handler->display->display_options['fields']['field_farm_area']['table'] = 'field_data_field_farm_area';
  $handler->display->display_options['fields']['field_farm_area']['field'] = 'field_farm_area';
  $handler->display->display_options['fields']['field_farm_area']['relationship'] = 'field_farm_planting_location_value';
  /* Field: Field: Quantity */
  $handler->display->display_options['fields']['field_farm_quantity']['id'] = 'field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['table'] = 'field_data_field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['field'] = 'field_farm_quantity';
  $handler->display->display_options['fields']['field_farm_quantity']['relationship'] = 'field_farm_planting_location_value';
  $handler->display->display_options['fields']['field_farm_quantity']['type'] = 'field_collection_fields';
  $handler->display->display_options['fields']['field_farm_quantity']['settings'] = array(
    'view_mode' => 'full',
  );
  /* Field: Field: Season */
  $handler->display->display_options['fields']['field_farm_season']['id'] = 'field_farm_season';
  $handler->display->display_options['fields']['field_farm_season']['table'] = 'field_data_field_farm_season';
  $handler->display->display_options['fields']['field_farm_season']['field'] = 'field_farm_season';
  /* Field: Global: Custom text */
  $handler->display->display_options['fields']['nothing']['id'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['table'] = 'views';
  $handler->display->display_options['fields']['nothing']['field'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['label'] = 'Operations';
  $handler->display->display_options['fields']['nothing']['alter']['text'] = '<a href="/farm/asset/[id]/edit?destination=admin/farm/crops/plantings">edit</a> | <a href="/farm/asset/[id]/delete?destination=admin/farm/crops/plantings">delete</a>';
  $handler->display->display_options['defaults']['arguments'] = FALSE;
  /* Contextual filter: Field: Crop (field_farm_crop) */
  $handler->display->display_options['arguments']['field_farm_crop_tid']['id'] = 'field_farm_crop_tid';
  $handler->display->display_options['arguments']['field_farm_crop_tid']['table'] = 'field_data_field_farm_crop';
  $handler->display->display_options['arguments']['field_farm_crop_tid']['field'] = 'field_farm_crop_tid';
  $handler->display->display_options['arguments']['field_farm_crop_tid']['default_action'] = 'not found';
  $handler->display->display_options['arguments']['field_farm_crop_tid']['title_enable'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_crop_tid']['title'] = 'Plantings in %1';
  $handler->display->display_options['arguments']['field_farm_crop_tid']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['field_farm_crop_tid']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['field_farm_crop_tid']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['field_farm_crop_tid']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['arguments']['field_farm_crop_tid']['specify_validation'] = TRUE;
  $handler->display->display_options['arguments']['field_farm_crop_tid']['validate']['type'] = 'taxonomy_term';
  $handler->display->display_options['arguments']['field_farm_crop_tid']['validate_options']['vocabularies'] = array(
    'farm_crops' => 'farm_crops',
  );
  $handler->display->display_options['defaults']['filter_groups'] = FALSE;
  $handler->display->display_options['defaults']['filters'] = FALSE;
  /* Filter criterion: Farm asset: Farm asset type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'farm_asset';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'planting' => 'planting',
  );
  $handler->display->display_options['filters']['type']['group'] = 1;
  /* Filter criterion: Field: Date range -  start date (field_farm_planting_date_range) */
  $handler->display->display_options['filters']['field_farm_planting_date_range_value']['id'] = 'field_farm_planting_date_range_value';
  $handler->display->display_options['filters']['field_farm_planting_date_range_value']['table'] = 'field_data_field_farm_planting_date_range';
  $handler->display->display_options['filters']['field_farm_planting_date_range_value']['field'] = 'field_farm_planting_date_range_value';
  $handler->display->display_options['filters']['field_farm_planting_date_range_value']['relationship'] = 'field_farm_planting_location_value';
  $handler->display->display_options['filters']['field_farm_planting_date_range_value']['operator'] = '<=';
  $handler->display->display_options['filters']['field_farm_planting_date_range_value']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_planting_date_range_value']['default_date'] = 'now';
  /* Filter criterion: Field: Date range - end date (field_farm_planting_date_range:value2) */
  $handler->display->display_options['filters']['field_farm_planting_date_range_value2']['id'] = 'field_farm_planting_date_range_value2';
  $handler->display->display_options['filters']['field_farm_planting_date_range_value2']['table'] = 'field_data_field_farm_planting_date_range';
  $handler->display->display_options['filters']['field_farm_planting_date_range_value2']['field'] = 'field_farm_planting_date_range_value2';
  $handler->display->display_options['filters']['field_farm_planting_date_range_value2']['relationship'] = 'field_farm_planting_location_value';
  $handler->display->display_options['filters']['field_farm_planting_date_range_value2']['operator'] = '>=';
  $handler->display->display_options['filters']['field_farm_planting_date_range_value2']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_planting_date_range_value2']['default_date'] = 'now';
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
  /* Filter criterion: Field: Area (field_farm_area) */
  $handler->display->display_options['filters']['field_farm_area_tid']['id'] = 'field_farm_area_tid';
  $handler->display->display_options['filters']['field_farm_area_tid']['table'] = 'field_data_field_farm_area';
  $handler->display->display_options['filters']['field_farm_area_tid']['field'] = 'field_farm_area_tid';
  $handler->display->display_options['filters']['field_farm_area_tid']['relationship'] = 'field_farm_planting_location_value';
  $handler->display->display_options['filters']['field_farm_area_tid']['group'] = 1;
  $handler->display->display_options['filters']['field_farm_area_tid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_farm_area_tid']['expose']['operator_id'] = 'field_farm_area_tid_op';
  $handler->display->display_options['filters']['field_farm_area_tid']['expose']['label'] = 'Location';
  $handler->display->display_options['filters']['field_farm_area_tid']['expose']['operator'] = 'field_farm_area_tid_op';
  $handler->display->display_options['filters']['field_farm_area_tid']['expose']['identifier'] = 'field_farm_area_tid';
  $handler->display->display_options['filters']['field_farm_area_tid']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    30037204 => 0,
    200153887 => 0,
    125843996 => 0,
    173712484 => 0,
    196317508 => 0,
    146437786 => 0,
  );
  $handler->display->display_options['filters']['field_farm_area_tid']['type'] = 'select';
  $handler->display->display_options['filters']['field_farm_area_tid']['vocabulary'] = 'farm_areas';
  $handler->display->display_options['filters']['field_farm_area_tid']['hierarchy'] = 1;
  $handler->display->display_options['path'] = 'taxonomy/term/%/plantings';
  $handler->display->display_options['menu']['title'] = 'Plantings';
  $handler->display->display_options['menu']['weight'] = '0';
  $handler->display->display_options['menu']['name'] = 'management';
  $handler->display->display_options['menu']['context'] = 0;
  $handler->display->display_options['menu']['context_only_inline'] = 0;
  $export['farm_plantings'] = $view;

  return $export;
}
