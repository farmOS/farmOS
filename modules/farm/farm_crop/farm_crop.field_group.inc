<?php
/**
 * @file
 * farm_crop.field_group.inc
 */

/**
 * Implements hook_field_group_info().
 */
function farm_crop_field_group_info() {
  $field_groups = array();

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_assignment|log|farm_seeding|form';
  $field_group->group_name = 'group_farm_assignment';
  $field_group->entity_type = 'log';
  $field_group->bundle = 'farm_seeding';
  $field_group->mode = 'form';
  $field_group->parent_name = 'group_farm_general';
  $field_group->data = array(
    'label' => 'Assignment',
    'weight' => '6',
    'children' => array(
      0 => 'field_farm_log_owner',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'label' => 'Assignment',
      'instance_settings' => array(
        'required_fields' => 1,
        'id' => '',
        'classes' => 'group-farm-assignment field-group-fieldset',
        'description' => '',
      ),
      'formatter' => 'collapsed',
    ),
  );
  $field_groups['group_farm_assignment|log|farm_seeding|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_assignment|log|farm_transplanting|form';
  $field_group->group_name = 'group_farm_assignment';
  $field_group->entity_type = 'log';
  $field_group->bundle = 'farm_transplanting';
  $field_group->mode = 'form';
  $field_group->parent_name = 'group_farm_general';
  $field_group->data = array(
    'label' => 'Assignment',
    'weight' => '4',
    'children' => array(
      0 => 'field_farm_log_owner',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'label' => 'Assignment',
      'instance_settings' => array(
        'required_fields' => 1,
        'id' => '',
        'classes' => 'group-farm-assignment field-group-fieldset',
        'description' => '',
      ),
      'formatter' => 'collapsed',
    ),
  );
  $field_groups['group_farm_assignment|log|farm_transplanting|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_category|log|farm_seeding|form';
  $field_group->group_name = 'group_farm_category';
  $field_group->entity_type = 'log';
  $field_group->bundle = 'farm_seeding';
  $field_group->mode = 'form';
  $field_group->parent_name = 'group_farm_general';
  $field_group->data = array(
    'label' => 'Category',
    'weight' => '5',
    'children' => array(
      0 => 'field_farm_log_category',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'label' => 'Category',
      'instance_settings' => array(
        'required_fields' => 1,
        'id' => '',
        'classes' => 'group-farm-category field-group-fieldset',
        'description' => '',
      ),
      'formatter' => 'collapsed',
    ),
  );
  $field_groups['group_farm_category|log|farm_seeding|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_category|log|farm_transplanting|form';
  $field_group->group_name = 'group_farm_category';
  $field_group->entity_type = 'log';
  $field_group->bundle = 'farm_transplanting';
  $field_group->mode = 'form';
  $field_group->parent_name = 'group_farm_general';
  $field_group->data = array(
    'label' => 'Category',
    'weight' => '3',
    'children' => array(
      0 => 'field_farm_log_category',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'label' => 'Category',
      'instance_settings' => array(
        'required_fields' => 1,
        'id' => '',
        'classes' => 'group-farm-category field-group-fieldset',
        'description' => '',
      ),
      'formatter' => 'collapsed',
    ),
  );
  $field_groups['group_farm_category|log|farm_transplanting|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_files|farm_asset|planting|form';
  $field_group->group_name = 'group_farm_files';
  $field_group->entity_type = 'farm_asset';
  $field_group->bundle = 'planting';
  $field_group->mode = 'form';
  $field_group->parent_name = 'group_farm_tabs';
  $field_group->data = array(
    'label' => 'Files',
    'weight' => '3',
    'children' => array(
      0 => 'field_farm_files',
      1 => 'field_farm_images',
    ),
    'format_type' => 'field_group_easy_responsive_tabs_nav_item',
    'format_settings' => array(
      'label' => 'Files',
      'instance_settings' => array(
        'required_fields' => 1,
        'classes' => '',
      ),
      'formatter' => '',
    ),
  );
  $field_groups['group_farm_files|farm_asset|planting|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_files|log|farm_seeding|form';
  $field_group->group_name = 'group_farm_files';
  $field_group->entity_type = 'log';
  $field_group->bundle = 'farm_seeding';
  $field_group->mode = 'form';
  $field_group->parent_name = 'group_farm_tabs';
  $field_group->data = array(
    'label' => 'Files',
    'weight' => '6',
    'children' => array(
      0 => 'field_farm_files',
      1 => 'field_farm_images',
    ),
    'format_type' => 'field_group_easy_responsive_tabs_nav_item',
    'format_settings' => array(
      'label' => 'Files',
      'instance_settings' => array(
        'required_fields' => 1,
        'classes' => '',
      ),
      'formatter' => '',
    ),
  );
  $field_groups['group_farm_files|log|farm_seeding|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_files|log|farm_transplanting|form';
  $field_group->group_name = 'group_farm_files';
  $field_group->entity_type = 'log';
  $field_group->bundle = 'farm_transplanting';
  $field_group->mode = 'form';
  $field_group->parent_name = 'group_farm_tabs';
  $field_group->data = array(
    'label' => 'Files',
    'weight' => '6',
    'children' => array(
      0 => 'field_farm_files',
      1 => 'field_farm_images',
    ),
    'format_type' => 'field_group_easy_responsive_tabs_nav_item',
    'format_settings' => array(
      'label' => 'Files',
      'instance_settings' => array(
        'required_fields' => 1,
        'classes' => '',
      ),
      'formatter' => '',
    ),
  );
  $field_groups['group_farm_files|log|farm_transplanting|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_general|farm_asset|planting|form';
  $field_group->group_name = 'group_farm_general';
  $field_group->entity_type = 'farm_asset';
  $field_group->bundle = 'planting';
  $field_group->mode = 'form';
  $field_group->parent_name = 'group_farm_tabs';
  $field_group->data = array(
    'label' => 'General',
    'weight' => '1',
    'children' => array(
      0 => 'field_farm_crop',
      1 => 'field_farm_description',
      2 => 'field_farm_season',
      3 => 'field_farm_flags',
      4 => 'name',
    ),
    'format_type' => 'field_group_easy_responsive_tabs_nav_item',
    'format_settings' => array(
      'label' => 'General',
      'instance_settings' => array(
        'required_fields' => 1,
        'classes' => '',
      ),
      'formatter' => '',
    ),
  );
  $field_groups['group_farm_general|farm_asset|planting|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_general|log|farm_seeding|form';
  $field_group->group_name = 'group_farm_general';
  $field_group->entity_type = 'log';
  $field_group->bundle = 'farm_seeding';
  $field_group->mode = 'form';
  $field_group->parent_name = 'group_farm_tabs';
  $field_group->data = array(
    'label' => 'General',
    'weight' => '1',
    'children' => array(
      0 => 'field_farm_asset',
      1 => 'field_farm_notes',
      2 => 'field_farm_seed_source',
      3 => 'field_farm_flags',
      4 => 'timestamp',
      5 => 'group_farm_assignment',
      6 => 'group_farm_category',
    ),
    'format_type' => 'field_group_easy_responsive_tabs_nav_item',
    'format_settings' => array(
      'label' => 'General',
      'instance_settings' => array(
        'required_fields' => 1,
        'classes' => '',
      ),
      'formatter' => '',
    ),
  );
  $field_groups['group_farm_general|log|farm_seeding|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_general|log|farm_transplanting|form';
  $field_group->group_name = 'group_farm_general';
  $field_group->entity_type = 'log';
  $field_group->bundle = 'farm_transplanting';
  $field_group->mode = 'form';
  $field_group->parent_name = 'group_farm_tabs';
  $field_group->data = array(
    'label' => 'General',
    'weight' => '1',
    'children' => array(
      0 => 'field_farm_asset',
      1 => 'field_farm_notes',
      2 => 'field_farm_flags',
      3 => 'timestamp',
      4 => 'group_farm_assignment',
      5 => 'group_farm_category',
    ),
    'format_type' => 'field_group_easy_responsive_tabs_nav_item',
    'format_settings' => array(
      'label' => 'General',
      'instance_settings' => array(
        'required_fields' => 1,
        'classes' => '',
      ),
      'formatter' => '',
    ),
  );
  $field_groups['group_farm_general|log|farm_transplanting|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_inventory|log|farm_seeding|form';
  $field_group->group_name = 'group_farm_inventory';
  $field_group->entity_type = 'log';
  $field_group->bundle = 'farm_seeding';
  $field_group->mode = 'form';
  $field_group->parent_name = 'group_farm_tabs';
  $field_group->data = array(
    'label' => 'Inventory',
    'weight' => '5',
    'children' => array(
      0 => 'field_farm_inventory',
    ),
    'format_type' => 'field_group_easy_responsive_tabs_nav_item',
    'format_settings' => array(
      'formatter' => '',
      'instance_settings' => array(
        'classes' => 'group-farm-inventory field-group-field_group_easy_responsive_tabs_nav_item',
        'required_fields' => 1,
      ),
    ),
  );
  $field_groups['group_farm_inventory|log|farm_seeding|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_inventory|log|farm_transplanting|form';
  $field_group->group_name = 'group_farm_inventory';
  $field_group->entity_type = 'log';
  $field_group->bundle = 'farm_transplanting';
  $field_group->mode = 'form';
  $field_group->parent_name = 'group_farm_tabs';
  $field_group->data = array(
    'label' => 'Inventory',
    'weight' => '5',
    'children' => array(
      0 => 'field_farm_inventory',
    ),
    'format_type' => 'field_group_easy_responsive_tabs_nav_item',
    'format_settings' => array(
      'formatter' => '',
      'instance_settings' => array(
        'classes' => 'group-farm-inventory field-group-field_group_easy_responsive_tabs_nav_item',
        'required_fields' => 1,
      ),
    ),
  );
  $field_groups['group_farm_inventory|log|farm_transplanting|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_lineage|farm_asset|planting|form';
  $field_group->group_name = 'group_farm_lineage';
  $field_group->entity_type = 'farm_asset';
  $field_group->bundle = 'planting';
  $field_group->mode = 'form';
  $field_group->parent_name = 'group_farm_tabs';
  $field_group->data = array(
    'label' => 'Lineage',
    'weight' => '2',
    'children' => array(
      0 => 'field_farm_parent',
    ),
    'format_type' => 'field_group_easy_responsive_tabs_nav_item',
    'format_settings' => array(
      'formatter' => '',
      'instance_settings' => array(
        'classes' => '',
        'required_fields' => 1,
      ),
    ),
  );
  $field_groups['group_farm_lineage|farm_asset|planting|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_location|log|farm_seeding|form';
  $field_group->group_name = 'group_farm_location';
  $field_group->entity_type = 'log';
  $field_group->bundle = 'farm_seeding';
  $field_group->mode = 'form';
  $field_group->parent_name = 'group_farm_tabs';
  $field_group->data = array(
    'label' => 'Location',
    'weight' => '2',
    'children' => array(
      0 => 'field_farm_movement',
    ),
    'format_type' => 'field_group_easy_responsive_tabs_nav_item',
    'format_settings' => array(
      'formatter' => '',
      'instance_settings' => array(
        'classes' => 'group-farm-location field-group-field_group_easy_responsive_tabs_nav_item',
        'required_fields' => 1,
      ),
    ),
  );
  $field_groups['group_farm_location|log|farm_seeding|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_location|log|farm_transplanting|form';
  $field_group->group_name = 'group_farm_location';
  $field_group->entity_type = 'log';
  $field_group->bundle = 'farm_transplanting';
  $field_group->mode = 'form';
  $field_group->parent_name = 'group_farm_tabs';
  $field_group->data = array(
    'label' => 'Location',
    'weight' => '2',
    'children' => array(
      0 => 'field_farm_movement',
    ),
    'format_type' => 'field_group_easy_responsive_tabs_nav_item',
    'format_settings' => array(
      'formatter' => '',
      'instance_settings' => array(
        'classes' => 'group-farm-location field-group-field_group_easy_responsive_tabs_nav_item',
        'required_fields' => 1,
      ),
    ),
  );
  $field_groups['group_farm_location|log|farm_transplanting|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_quantity|log|farm_seeding|form';
  $field_group->group_name = 'group_farm_quantity';
  $field_group->entity_type = 'log';
  $field_group->bundle = 'farm_seeding';
  $field_group->mode = 'form';
  $field_group->parent_name = 'group_farm_tabs';
  $field_group->data = array(
    'label' => 'Quantity',
    'weight' => '3',
    'children' => array(
      0 => 'field_farm_quantity',
    ),
    'format_type' => 'field_group_easy_responsive_tabs_nav_item',
    'format_settings' => array(
      'label' => 'Quantity',
      'instance_settings' => array(
        'required_fields' => 1,
        'classes' => 'group-farm-quantity field-group-field_group_easy_responsive_tabs_nav_item',
      ),
    ),
  );
  $field_groups['group_farm_quantity|log|farm_seeding|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_quantity|log|farm_transplanting|form';
  $field_group->group_name = 'group_farm_quantity';
  $field_group->entity_type = 'log';
  $field_group->bundle = 'farm_transplanting';
  $field_group->mode = 'form';
  $field_group->parent_name = 'group_farm_tabs';
  $field_group->data = array(
    'label' => 'Quantity',
    'weight' => '3',
    'children' => array(
      0 => 'field_farm_quantity',
    ),
    'format_type' => 'field_group_easy_responsive_tabs_nav_item',
    'format_settings' => array(
      'formatter' => '',
      'instance_settings' => array(
        'classes' => 'group-farm-quantity field-group-field_group_easy_responsive_tabs_nav_item',
        'required_fields' => 1,
      ),
    ),
  );
  $field_groups['group_farm_quantity|log|farm_transplanting|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_tabs|farm_asset|planting|form';
  $field_group->group_name = 'group_farm_tabs';
  $field_group->entity_type = 'farm_asset';
  $field_group->bundle = 'planting';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Tabs',
    'weight' => '0',
    'children' => array(
      0 => 'group_farm_files',
      1 => 'group_farm_general',
      2 => 'group_farm_lineage',
    ),
    'format_type' => 'field_group_easy_responsive_tabs_nav',
    'format_settings' => array(
      'label' => 'Tabs',
      'instance_settings' => array(
        'type' => 'vertical',
        'width' => 'auto',
        'fit' => '1',
        'closed' => '0',
        'activetab_bg' => '',
        'inactive_bg' => '',
        'active_border_color' => '',
        'active_content_border_color' => '',
        'tabidentify' => '',
        'required_fields' => 1,
        'classes' => 'group-farm-tabs field-group-field_group_easy_responsive_tabs_nav',
      ),
    ),
  );
  $field_groups['group_farm_tabs|farm_asset|planting|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_tabs|log|farm_seeding|form';
  $field_group->group_name = 'group_farm_tabs';
  $field_group->entity_type = 'log';
  $field_group->bundle = 'farm_seeding';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Tabs',
    'weight' => '0',
    'children' => array(
      0 => 'group_farm_files',
      1 => 'group_farm_general',
      2 => 'group_farm_quantity',
      3 => 'group_farm_inventory',
      4 => 'group_farm_location',
    ),
    'format_type' => 'field_group_easy_responsive_tabs_nav',
    'format_settings' => array(
      'label' => 'Tabs',
      'instance_settings' => array(
        'type' => 'vertical',
        'width' => 'auto',
        'fit' => '1',
        'closed' => '0',
        'activetab_bg' => '',
        'inactive_bg' => '',
        'active_border_color' => '',
        'active_content_border_color' => '',
        'tabidentify' => '',
        'required_fields' => 1,
        'classes' => 'group-farm-tabs field-group-field_group_easy_responsive_tabs_nav',
      ),
    ),
  );
  $field_groups['group_farm_tabs|log|farm_seeding|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_tabs|log|farm_transplanting|form';
  $field_group->group_name = 'group_farm_tabs';
  $field_group->entity_type = 'log';
  $field_group->bundle = 'farm_transplanting';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Tabs',
    'weight' => '0',
    'children' => array(
      0 => 'group_farm_files',
      1 => 'group_farm_general',
      2 => 'group_farm_quantity',
      3 => 'group_farm_inventory',
      4 => 'group_farm_location',
    ),
    'format_type' => 'field_group_easy_responsive_tabs_nav',
    'format_settings' => array(
      'label' => 'Tabs',
      'instance_settings' => array(
        'type' => 'vertical',
        'width' => 'auto',
        'fit' => '1',
        'closed' => '0',
        'activetab_bg' => '',
        'inactive_bg' => '',
        'active_border_color' => '',
        'active_content_border_color' => '',
        'tabidentify' => '',
        'required_fields' => 1,
        'classes' => 'group-farm-tabs field-group-field_group_easy_responsive_tabs_nav',
      ),
    ),
  );
  $field_groups['group_farm_tabs|log|farm_transplanting|form'] = $field_group;

  // Translatables
  // Included for use with string extractors like potx.
  t('Assignment');
  t('Category');
  t('Files');
  t('General');
  t('Inventory');
  t('Lineage');
  t('Location');
  t('Quantity');
  t('Tabs');

  return $field_groups;
}
