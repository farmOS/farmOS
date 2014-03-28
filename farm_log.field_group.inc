<?php
/**
 * @file
 * farm_log.field_group.inc
 */

/**
 * Implements hook_field_group_info().
 */
function farm_log_field_group_info() {
  $export = array();

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_crops_fields|log|farm_issue|form';
  $field_group->group_name = 'group_farm_crops_fields';
  $field_group->entity_type = 'log';
  $field_group->bundle = 'farm_issue';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Crops + Fields',
    'weight' => '3',
    'children' => array(
      0 => 'field_farm_areas',
      1 => 'field_farm_crops',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'label' => 'Crops + Fields',
      'instance_settings' => array(
        'required_fields' => 0,
        'classes' => 'group-farm-crops-fields field-group-fieldset',
        'description' => '',
      ),
      'formatter' => 'collapsed',
    ),
  );
  $export['group_farm_crops_fields|log|farm_issue|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_details|log|farm_issue|form';
  $field_group->group_name = 'group_farm_details';
  $field_group->entity_type = 'log';
  $field_group->bundle = 'farm_issue';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Issue Details',
    'weight' => '2',
    'children' => array(
      0 => 'field_farm_notes',
      1 => 'field_farm_priority',
      2 => 'field_farm_threats',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'label' => 'Issue Details',
      'instance_settings' => array(
        'required_fields' => 0,
        'classes' => 'group-farm-details field-group-fieldset',
        'description' => '',
      ),
      'formatter' => 'open',
    ),
  );
  $export['group_farm_details|log|farm_issue|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_files|log|farm_issue|form';
  $field_group->group_name = 'group_farm_files';
  $field_group->entity_type = 'log';
  $field_group->bundle = 'farm_issue';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Attach files',
    'weight' => '4',
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
  $export['group_farm_files|log|farm_issue|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_related_logs|log|farm_issue|form';
  $field_group->group_name = 'group_farm_related_logs';
  $field_group->entity_type = 'log';
  $field_group->bundle = 'farm_issue';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Related Logs',
    'weight' => '5',
    'children' => array(
      0 => 'field_farm_issues',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'label' => 'Related Logs',
      'instance_settings' => array(
        'required_fields' => 0,
        'classes' => 'group-farm-related-logs field-group-fieldset',
        'description' => '',
      ),
      'formatter' => 'collapsed',
    ),
  );
  $export['group_farm_related_logs|log|farm_issue|form'] = $field_group;

  return $export;
}
