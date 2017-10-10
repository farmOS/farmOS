<?php
/**
 * @file
 * farm_movement.field_group.inc
 */

/**
 * Implements hook_field_group_info().
 */
function farm_movement_field_group_info() {
  $field_groups = array();

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_farm_geometry|field_collection_item|field_farm_movement|form';
  $field_group->group_name = 'group_farm_geometry';
  $field_group->entity_type = 'field_collection_item';
  $field_group->bundle = 'field_farm_movement';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Geometry',
    'weight' => '2',
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
  $field_groups['group_farm_geometry|field_collection_item|field_farm_movement|form'] = $field_group;

  // Translatables
  // Included for use with string extractors like potx.
  t('Geometry');

  return $field_groups;
}
