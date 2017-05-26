<?php
/**
 * @file
 * farm_log_activity.field_group.inc
 */

/**
 * Implements hook_field_group_info().
 */
function farm_log_activity_field_group_info() {
  $field_groups = array();

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_areas|log|farm_activity|form';
  $field_group->group_name = 'group_farm_areas';
  $field_group->entity_type = 'log';
  $field_group->bundle = 'farm_activity';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Areas',
    'weight' => '2',
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
      'formatter' => 'collapsed',
    ),
  );
  $field_groups['group_farm_areas|log|farm_activity|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_assets|log|farm_activity|form';
  $field_group->group_name = 'group_farm_assets';
  $field_group->entity_type = 'log';
  $field_group->bundle = 'farm_activity';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Assets',
    'weight' => '1',
    'children' => array(
      0 => 'field_farm_asset',
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
      'formatter' => 'collapsed',
    ),
  );
  $field_groups['group_farm_assets|log|farm_activity|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_files|log|farm_activity|form';
  $field_group->group_name = 'group_farm_files';
  $field_group->entity_type = 'log';
  $field_group->bundle = 'farm_activity';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'File attachments',
    'weight' => '6',
    'children' => array(
      0 => 'field_farm_images',
      1 => 'field_farm_files',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'label' => 'File attachments',
      'instance_settings' => array(
        'required_fields' => 1,
        'id' => '',
        'classes' => 'group-farm-files field-group-fieldset',
        'description' => '',
      ),
      'formatter' => 'collapsed',
    ),
  );
  $field_groups['group_farm_files|log|farm_activity|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_general|log|farm_activity|form';
  $field_group->group_name = 'group_farm_general';
  $field_group->entity_type = 'log';
  $field_group->bundle = 'farm_activity';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'General information',
    'weight' => '0',
    'children' => array(
      0 => 'name',
      1 => 'timestamp',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'formatter' => 'collapsible',
      'instance_settings' => array(
        'description' => '',
        'classes' => 'group-farm-general field-group-fieldset',
        'required_fields' => 1,
        'id' => '',
      ),
    ),
  );
  $field_groups['group_farm_general|log|farm_activity|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_notes|log|farm_activity|form';
  $field_group->group_name = 'group_farm_notes';
  $field_group->entity_type = 'log';
  $field_group->bundle = 'farm_activity';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Notes',
    'weight' => '5',
    'children' => array(
      0 => 'field_farm_notes',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'label' => 'Notes',
      'instance_settings' => array(
        'required_fields' => 1,
        'id' => '',
        'classes' => 'group-farm-notes field-group-fieldset',
        'description' => '',
      ),
      'formatter' => 'collapsed',
    ),
  );
  $field_groups['group_farm_notes|log|farm_activity|form'] = $field_group;

  // Translatables
  // Included for use with string extractors like potx.
  t('Areas');
  t('Assets');
  t('File attachments');
  t('General information');
  t('Notes');

  return $field_groups;
}
