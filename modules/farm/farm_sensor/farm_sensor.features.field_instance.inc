<?php
/**
 * @file
 * farm_sensor.features.field_instance.inc
 */

/**
 * Implements hook_field_default_field_instances().
 */
function farm_sensor_field_default_field_instances() {
  $field_instances = array();

  // Exported field_instance: 'farm_asset-sensor-field_farm_description'.
  $field_instances['farm_asset-sensor-field_farm_description'] = array(
    'bundle' => 'sensor',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'text',
        'settings' => array(),
        'type' => 'text_default',
        'weight' => 1,
      ),
    ),
    'entity_type' => 'farm_asset',
    'field_name' => 'field_farm_description',
    'label' => 'Description',
    'required' => 0,
    'settings' => array(
      'text_processing' => 1,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'text',
      'settings' => array(
        'rows' => 5,
      ),
      'type' => 'text_textarea',
      'weight' => -9,
    ),
  );

  // Exported field_instance: 'farm_asset-sensor-field_farm_files'.
  $field_instances['farm_asset-sensor-field_farm_files'] = array(
    'bundle' => 'sensor',
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'file',
        'settings' => array(),
        'type' => 'file_table',
        'weight' => 3,
      ),
    ),
    'entity_type' => 'farm_asset',
    'field_name' => 'field_farm_files',
    'label' => 'Files',
    'required' => 0,
    'settings' => array(
      'description_field' => 1,
      'file_directory' => 'farm/asset/sensor',
      'file_extensions' => '',
      'max_filesize' => '',
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'multiupload_filefield_widget',
      'settings' => array(
        'progress_indicator' => 'bar',
      ),
      'type' => 'file_mfw',
      'weight' => 4,
    ),
  );

  // Exported field_instance: 'farm_asset-sensor-field_farm_flags'.
  $field_instances['farm_asset-sensor-field_farm_flags'] = array(
    'bundle' => 'sensor',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => 'Add flags to this asset to enable better sorting and filtering of records.',
    'display' => array(
      'default' => array(
        'label' => 'inline',
        'module' => 'list',
        'settings' => array(),
        'type' => 'list_default',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'farm_asset',
    'field_name' => 'field_farm_flags',
    'label' => 'Flags',
    'required' => 0,
    'settings' => array(
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'options',
      'settings' => array(),
      'type' => 'options_buttons',
      'weight' => -8,
    ),
  );

  // Exported field_instance: 'farm_asset-sensor-field_farm_images'.
  $field_instances['farm_asset-sensor-field_farm_images'] = array(
    'bundle' => 'sensor',
    'default_value' => array(),
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'colorbox',
        'settings' => array(
          'colorbox_caption' => 'auto',
          'colorbox_caption_custom' => '',
          'colorbox_gallery' => 'post',
          'colorbox_gallery_custom' => '',
          'colorbox_image_style' => '',
          'colorbox_multivalue_index' => NULL,
          'colorbox_node_style' => 'thumbnail',
          'colorbox_node_style_first' => 'large',
        ),
        'type' => 'colorbox',
        'weight' => 2,
      ),
    ),
    'entity_type' => 'farm_asset',
    'field_name' => 'field_farm_images',
    'label' => 'Photos',
    'required' => 0,
    'settings' => array(
      'alt_field' => 0,
      'default_image' => 0,
      'file_directory' => 'farm/asset/sensor',
      'file_extensions' => '',
      'max_filesize' => '',
      'max_resolution' => '',
      'min_resolution' => '',
      'title_field' => 0,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'multiupload_imagefield_widget',
      'settings' => array(
        'preview_image_style' => 'thumbnail',
        'progress_indicator' => 'bar',
      ),
      'type' => 'image_miw',
      'weight' => 3,
    ),
  );

  // Translatables
  // Included for use with string extractors like potx.
  t('Add flags to this asset to enable better sorting and filtering of records.');
  t('Description');
  t('Files');
  t('Flags');
  t('Photos');

  return $field_instances;
}
