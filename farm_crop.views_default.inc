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
    'name' => 'name',
    'field_farm_crop_family' => 'field_farm_crop_family',
    'edit_term' => 'edit_term',
  );
  $handler->display->display_options['style_options']['default'] = 'name';
  $handler->display->display_options['style_options']['info'] = array(
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
    'edit_term' => array(
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
  /* Field: Taxonomy term: Crop family */
  $handler->display->display_options['fields']['field_farm_crop_family']['id'] = 'field_farm_crop_family';
  $handler->display->display_options['fields']['field_farm_crop_family']['table'] = 'field_data_field_farm_crop_family';
  $handler->display->display_options['fields']['field_farm_crop_family']['field'] = 'field_farm_crop_family';
  $handler->display->display_options['fields']['field_farm_crop_family']['type'] = 'taxonomy_term_reference_plain';
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
  /* Field: Global: Custom text */
  $handler->display->display_options['fields']['nothing']['id'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['table'] = 'views';
  $handler->display->display_options['fields']['nothing']['field'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['label'] = 'Links';
  $handler->display->display_options['fields']['nothing']['alter']['text'] = '<a href="/admin/farm/crops/[tid]/plantings">plantings</a> | <a href="/admin/farm/crops/[tid]/inputs">inputs</a> | <a href="/admin/farm/crops/[tid]/cultivations">cultivations</a> | <a href="/admin/farm/crops/[tid]/harvests">harvests</a> | <a href="/admin/farm/crops/[tid]/issues">issues</a> (<a href="/taxonomy/term/[tid]/edit">edit crop</a>)';
  $handler->display->display_options['link_to_view'] = '0';
  $export['farm_crops'] = $view;

  return $export;
}
