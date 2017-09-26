<?php
/**
 * @file
 * farm_soil_compost.features.field_instance.inc
 */

/**
 * Implements hook_field_default_field_instances().
 */
function farm_soil_compost_field_default_field_instances() {
  $field_instances = array();

  // Exported field_instance: 'farm_asset-compost-field_farm_description'.
  $field_instances['farm_asset-compost-field_farm_description'] = array(
    'bundle' => 'compost',
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
      'weight' => 2,
    ),
  );

  // Exported field_instance: 'farm_asset-compost-field_farm_files'.
  $field_instances['farm_asset-compost-field_farm_files'] = array(
    'bundle' => 'compost',
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
      'file_directory' => 'farm/asset/compost',
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
      'weight' => 3,
    ),
  );

  // Exported field_instance: 'farm_asset-compost-field_farm_images'.
  $field_instances['farm_asset-compost-field_farm_images'] = array(
    'bundle' => 'compost',
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
      'file_directory' => 'farm/asset/compost',
      'file_extensions' => '',
      'max_filesize' => '',
      'max_resolution' => '',
      'min_resolution' => '',
      'title_field' => 0,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'image',
      'settings' => array(
        'preview_image_style' => 'thumbnail',
        'progress_indicator' => 'bar',
      ),
      'type' => 'image_image',
      'weight' => 2,
    ),
  );

  // Exported field_instance: 'farm_asset-compost-field_farm_parent'.
  $field_instances['farm_asset-compost-field_farm_parent'] = array(
    'bundle' => 'compost',
    'deleted' => 0,
    'description' => 'If this compost asset was created from other compost assets, use this field to link it to them as a child.',
    'display' => array(
      'default' => array(
        'label' => 'inline',
        'module' => 'entityreference',
        'settings' => array(
          'bypass_access' => FALSE,
          'link' => FALSE,
        ),
        'type' => 'entityreference_label',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'farm_asset',
    'field_name' => 'field_farm_parent',
    'label' => 'Parent(s)',
    'required' => 0,
    'settings' => array(
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'entityreference_view_widget',
      'settings' => array(
        'allow_duplicates' => 0,
        'close_modal' => 1,
        'pass_argument' => 1,
        'pass_arguments' => '',
        'rendered_entity' => 0,
        'view' => 'farm_asset_entityreference_view|entityreference_view_widget',
        'view_mode' => 'full',
      ),
      'type' => 'entityreference_view_widget',
      'weight' => 6,
    ),
  );

  // Translatables
  // Included for use with string extractors like potx.
  t('Description');
  t('Files');
  t('If this compost asset was created from other compost assets, use this field to link it to them as a child.');
  t('Parent(s)');
  t('Photos');

  return $field_instances;
}
