<?php
/**
 * @file
 * farm_log_sales.field_group.inc
 */

/**
 * Implements hook_field_group_info().
 */
function farm_log_sales_field_group_info() {
  $export = array();

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_crops_fields|log|farm_loss|form';
  $field_group->group_name = 'group_farm_crops_fields';
  $field_group->entity_type = 'log';
  $field_group->bundle = 'farm_loss';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Crops + Fields',
    'weight' => '6',
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
  $export['group_farm_crops_fields|log|farm_loss|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_crops_fields|log|farm_purchase|form';
  $field_group->group_name = 'group_farm_crops_fields';
  $field_group->entity_type = 'log';
  $field_group->bundle = 'farm_purchase';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Crops + Fields',
    'weight' => '2',
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
  $export['group_farm_crops_fields|log|farm_purchase|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_crops_fields|log|farm_sale|form';
  $field_group->group_name = 'group_farm_crops_fields';
  $field_group->entity_type = 'log';
  $field_group->bundle = 'farm_sale';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Crops + Fields',
    'weight' => '2',
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
  $export['group_farm_crops_fields|log|farm_sale|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_details|log|farm_loss|form';
  $field_group->group_name = 'group_farm_details';
  $field_group->entity_type = 'log';
  $field_group->bundle = 'farm_loss';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Loss Details',
    'weight' => '1',
    'children' => array(
      0 => 'field_farm_notes',
      1 => 'field_farm_quantity_units',
      2 => 'field_farm_quantity_value',
      3 => 'field_farm_value',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'label' => 'Loss Details',
      'instance_settings' => array(
        'required_fields' => 0,
        'classes' => 'group-farm-details field-group-fieldset',
        'description' => '',
      ),
      'formatter' => 'open',
    ),
  );
  $export['group_farm_details|log|farm_loss|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_details|log|farm_purchase|form';
  $field_group->group_name = 'group_farm_details';
  $field_group->entity_type = 'log';
  $field_group->bundle = 'farm_purchase';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Purchase Details',
    'weight' => '1',
    'children' => array(
      0 => 'field_farm_notes',
      1 => 'field_farm_quantity_units',
      2 => 'field_farm_quantity_value',
      3 => 'field_farm_value',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'label' => 'Purchase Details',
      'instance_settings' => array(
        'required_fields' => 0,
        'classes' => 'group-farm-details field-group-fieldset',
        'description' => '',
      ),
      'formatter' => 'open',
    ),
  );
  $export['group_farm_details|log|farm_purchase|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_details|log|farm_sale|form';
  $field_group->group_name = 'group_farm_details';
  $field_group->entity_type = 'log';
  $field_group->bundle = 'farm_sale';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Sale Details',
    'weight' => '1',
    'children' => array(
      0 => 'field_farm_notes',
      1 => 'field_farm_quantity_units',
      2 => 'field_farm_quantity_value',
      3 => 'field_farm_value',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'label' => 'Sale Details',
      'instance_settings' => array(
        'required_fields' => 0,
        'classes' => 'group-farm-details field-group-fieldset',
        'description' => '',
      ),
      'formatter' => 'open',
    ),
  );
  $export['group_farm_details|log|farm_sale|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_files|log|farm_loss|form';
  $field_group->group_name = 'group_farm_files';
  $field_group->entity_type = 'log';
  $field_group->bundle = 'farm_loss';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Attach Files',
    'weight' => '7',
    'children' => array(
      0 => 'field_farm_images',
      1 => 'field_farm_files',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'label' => 'Attach Files',
      'instance_settings' => array(
        'required_fields' => 0,
        'classes' => 'group-farm-files field-group-fieldset',
        'description' => '',
      ),
      'formatter' => 'collapsed',
    ),
  );
  $export['group_farm_files|log|farm_loss|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_files|log|farm_purchase|form';
  $field_group->group_name = 'group_farm_files';
  $field_group->entity_type = 'log';
  $field_group->bundle = 'farm_purchase';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Attach Files',
    'weight' => '6',
    'children' => array(
      0 => 'field_farm_images',
      1 => 'field_farm_files',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'label' => 'Attach Files',
      'instance_settings' => array(
        'required_fields' => 0,
        'classes' => 'group-farm-files field-group-fieldset',
        'description' => '',
      ),
      'formatter' => 'collapsed',
    ),
  );
  $export['group_farm_files|log|farm_purchase|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_files|log|farm_sale|form';
  $field_group->group_name = 'group_farm_files';
  $field_group->entity_type = 'log';
  $field_group->bundle = 'farm_sale';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Attach Files',
    'weight' => '3',
    'children' => array(
      0 => 'field_farm_images',
      1 => 'field_farm_files',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'label' => 'Attach Files',
      'instance_settings' => array(
        'required_fields' => 0,
        'classes' => 'group-farm-files field-group-fieldset',
        'description' => '',
      ),
      'formatter' => 'collapsed',
    ),
  );
  $export['group_farm_files|log|farm_sale|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_related_logs|log|farm_loss|form';
  $field_group->group_name = 'group_farm_related_logs';
  $field_group->entity_type = 'log';
  $field_group->bundle = 'farm_loss';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Related Logs',
    'weight' => '8',
    'children' => array(
      0 => 'field_farm_issues',
      1 => 'field_farm_losses',
      2 => 'field_farm_plantings',
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
  $export['group_farm_related_logs|log|farm_loss|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_related_logs|log|farm_purchase|form';
  $field_group->group_name = 'group_farm_related_logs';
  $field_group->entity_type = 'log';
  $field_group->bundle = 'farm_purchase';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Related Logs',
    'weight' => '7',
    'children' => array(
      0 => 'field_farm_issues',
      1 => 'field_farm_plantings',
      2 => 'field_farm_purchases',
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
  $export['group_farm_related_logs|log|farm_purchase|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_related_logs|log|farm_sale|form';
  $field_group->group_name = 'group_farm_related_logs';
  $field_group->entity_type = 'log';
  $field_group->bundle = 'farm_sale';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Related Logs',
    'weight' => '4',
    'children' => array(
      0 => 'field_farm_issues',
      1 => 'field_farm_plantings',
      2 => 'field_farm_sales',
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
  $export['group_farm_related_logs|log|farm_sale|form'] = $field_group;

  return $export;
}
