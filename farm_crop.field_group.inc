<?php
/**
 * @file
 * farm_crop.field_group.inc
 */

/**
 * Implements hook_field_group_info().
 */
function farm_crop_field_group_info() {
  $export = array();

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_data|taxonomy_term|farm_crops|form';
  $field_group->group_name = 'group_farm_data';
  $field_group->entity_type = 'taxonomy_term';
  $field_group->bundle = 'farm_crops';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Crop Data',
    'weight' => '2',
    'children' => array(
      0 => 'field_farm_maturity_days',
      1 => 'field_farm_transplant_days',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'label' => 'Crop Data',
      'instance_settings' => array(
        'required_fields' => 1,
        'classes' => 'group-farm-data field-group-fieldset',
        'description' => '',
      ),
      'formatter' => 'collapsed',
    ),
  );
  $export['group_farm_data|taxonomy_term|farm_crops|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_details|farm_asset|planting|form';
  $field_group->group_name = 'group_farm_details';
  $field_group->entity_type = 'farm_asset';
  $field_group->bundle = 'planting';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Planting Details',
    'weight' => '1',
    'children' => array(
      0 => 'field_farm_season',
      1 => 'field_farm_crop',
      2 => 'field_farm_notes',
      3 => 'field_farm_planting_location',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'formatter' => 'collapsible',
      'instance_settings' => array(
        'description' => '',
        'classes' => 'group-farm-details field-group-fieldset',
        'required_fields' => 1,
      ),
    ),
  );
  $export['group_farm_details|farm_asset|planting|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_details|taxonomy_term|farm_crops|form';
  $field_group->group_name = 'group_farm_details';
  $field_group->entity_type = 'taxonomy_term';
  $field_group->bundle = 'farm_crops';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Crop Details',
    'weight' => '1',
    'children' => array(
      0 => 'field_farm_companions',
      1 => 'field_farm_crop_family',
      2 => 'description',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'label' => 'Crop Details',
      'instance_settings' => array(
        'required_fields' => 1,
        'classes' => 'group-farm-details field-group-fieldset',
        'description' => '',
      ),
      'formatter' => 'open',
    ),
  );
  $export['group_farm_details|taxonomy_term|farm_crops|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_files|farm_asset|planting|form';
  $field_group->group_name = 'group_farm_files';
  $field_group->entity_type = 'farm_asset';
  $field_group->bundle = 'planting';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Attach files',
    'weight' => '2',
    'children' => array(
      0 => 'field_farm_files',
      1 => 'field_farm_images',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'label' => 'Attach files',
      'instance_settings' => array(
        'required_fields' => 0,
        'classes' => 'group-farm-files field-group-fieldset',
        'description' => '',
      ),
      'formatter' => 'collapsed',
    ),
  );
  $export['group_farm_files|farm_asset|planting|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_files|taxonomy_term|farm_crops|form';
  $field_group->group_name = 'group_farm_files';
  $field_group->entity_type = 'taxonomy_term';
  $field_group->bundle = 'farm_crops';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Attach Files',
    'weight' => '4',
    'children' => array(
      0 => 'field_farm_files',
      1 => 'field_farm_images',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'label' => 'Attach Files',
      'instance_settings' => array(
        'required_fields' => 1,
        'classes' => 'group-farm-files field-group-fieldset',
        'description' => '',
      ),
      'formatter' => 'collapsed',
    ),
  );
  $export['group_farm_files|taxonomy_term|farm_crops|form'] = $field_group;

  return $export;
}
