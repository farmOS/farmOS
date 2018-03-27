<?php
/**
 * @file
 * farm_log_observation.field_group.inc
 */

/**
 * Implements hook_field_group_info().
 */
function farm_log_observation_field_group_info() {
  $field_groups = array();

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_areas|log|farm_observation|form';
  $field_group->group_name = 'group_farm_areas';
  $field_group->entity_type = 'log';
  $field_group->bundle = 'farm_observation';
  $field_group->mode = 'form';
  $field_group->parent_name = 'group_farm_location';
  $field_group->data = array(
    'label' => 'Areas',
    'weight' => '7',
    'children' => array(
      0 => 'field_farm_area',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'label' => 'Areas',
      'instance_settings' => array(
        'required_fields' => 1,
        'id' => '',
        'classes' => 'group-farm-areas field-group-fieldset',
        'description' => '',
      ),
      'formatter' => 'open',
    ),
  );
  $field_groups['group_farm_areas|log|farm_observation|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_assets|log|farm_observation|form';
  $field_group->group_name = 'group_farm_assets';
  $field_group->entity_type = 'log';
  $field_group->bundle = 'farm_observation';
  $field_group->mode = 'form';
  $field_group->parent_name = 'group_farm_tabs';
  $field_group->data = array(
    'label' => 'Assets',
    'weight' => '7',
    'children' => array(
      0 => 'field_farm_asset',
      1 => 'field_farm_movement',
      2 => 'field_farm_membership',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'label' => 'Assets',
      'instance_settings' => array(
        'required_fields' => 1,
        'id' => '',
        'classes' => 'group-farm-assets field-group-fieldset',
        'description' => '',
      ),
      'formatter' => 'open',
    ),
  );
  $field_groups['group_farm_assets|log|farm_observation|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_assignment|log|farm_observation|form';
  $field_group->group_name = 'group_farm_assignment';
  $field_group->entity_type = 'log';
  $field_group->bundle = 'farm_observation';
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
        'classes' => '',
        'description' => '',
      ),
      'formatter' => 'collapsed',
    ),
  );
  $field_groups['group_farm_assignment|log|farm_observation|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_category|log|farm_observation|form';
  $field_group->group_name = 'group_farm_category';
  $field_group->entity_type = 'log';
  $field_group->bundle = 'farm_observation';
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
  $field_groups['group_farm_category|log|farm_observation|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_files|log|farm_observation|form';
  $field_group->group_name = 'group_farm_files';
  $field_group->entity_type = 'log';
  $field_group->bundle = 'farm_observation';
  $field_group->mode = 'form';
  $field_group->parent_name = 'group_farm_tabs';
  $field_group->data = array(
    'label' => 'Files',
    'weight' => '15',
    'children' => array(
      0 => 'field_farm_files',
      1 => 'field_farm_images',
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
  $field_groups['group_farm_files|log|farm_observation|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_general|log|farm_observation|form';
  $field_group->group_name = 'group_farm_general';
  $field_group->entity_type = 'log';
  $field_group->bundle = 'farm_observation';
  $field_group->mode = 'form';
  $field_group->parent_name = 'group_farm_tabs';
  $field_group->data = array(
    'label' => 'General',
    'weight' => '6',
    'children' => array(
      0 => 'field_farm_notes',
      1 => 'field_farm_flags',
      2 => 'name',
      3 => 'timestamp',
      4 => 'group_farm_assignment',
      5 => 'group_farm_category',
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
  $field_groups['group_farm_general|log|farm_observation|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_geometry|log|farm_observation|form';
  $field_group->group_name = 'group_farm_geometry';
  $field_group->entity_type = 'log';
  $field_group->bundle = 'farm_observation';
  $field_group->mode = 'form';
  $field_group->parent_name = 'group_farm_location';
  $field_group->data = array(
    'label' => 'Geometry',
    'weight' => '9',
    'children' => array(
      0 => 'field_farm_geofield',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'label' => 'Geometry',
      'instance_settings' => array(
        'required_fields' => 1,
        'id' => '',
        'classes' => '',
        'description' => '',
      ),
      'formatter' => 'collapsed',
    ),
  );
  $field_groups['group_farm_geometry|log|farm_observation|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_inventory|log|farm_observation|form';
  $field_group->group_name = 'group_farm_inventory';
  $field_group->entity_type = 'log';
  $field_group->bundle = 'farm_observation';
  $field_group->mode = 'form';
  $field_group->parent_name = 'group_farm_tabs';
  $field_group->data = array(
    'label' => 'Inventory',
    'weight' => '14',
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
  $field_groups['group_farm_inventory|log|farm_observation|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_location|log|farm_observation|form';
  $field_group->group_name = 'group_farm_location';
  $field_group->entity_type = 'log';
  $field_group->bundle = 'farm_observation';
  $field_group->mode = 'form';
  $field_group->parent_name = 'group_farm_tabs';
  $field_group->data = array(
    'label' => 'Location',
    'weight' => '9',
    'children' => array(
      0 => 'group_farm_areas',
      1 => 'group_farm_geometry',
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
  $field_groups['group_farm_location|log|farm_observation|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_quantity|log|farm_observation|form';
  $field_group->group_name = 'group_farm_quantity';
  $field_group->entity_type = 'log';
  $field_group->bundle = 'farm_observation';
  $field_group->mode = 'form';
  $field_group->parent_name = 'group_farm_tabs';
  $field_group->data = array(
    'label' => 'Quantity',
    'weight' => '10',
    'children' => array(
      0 => 'field_farm_quantity',
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
  $field_groups['group_farm_quantity|log|farm_observation|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_tabs|log|farm_observation|form';
  $field_group->group_name = 'group_farm_tabs';
  $field_group->entity_type = 'log';
  $field_group->bundle = 'farm_observation';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Tabs',
    'weight' => '0',
    'children' => array(
      0 => 'group_farm_files',
      1 => 'group_farm_general',
      2 => 'group_farm_assets',
      3 => 'group_farm_quantity',
      4 => 'group_farm_inventory',
      5 => 'group_farm_location',
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
        'classes' => '',
      ),
    ),
  );
  $field_groups['group_farm_tabs|log|farm_observation|form'] = $field_group;

  // Translatables
  // Included for use with string extractors like potx.
  t('Areas');
  t('Assets');
  t('Assignment');
  t('Category');
  t('Files');
  t('General');
  t('Geometry');
  t('Inventory');
  t('Location');
  t('Quantity');
  t('Tabs');

  return $field_groups;
}
