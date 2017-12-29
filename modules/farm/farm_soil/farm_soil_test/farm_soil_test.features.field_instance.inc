<?php
/**
 * @file
 * farm_soil_test.features.field_instance.inc
 */

/**
 * Implements hook_field_default_field_instances().
 */
function farm_soil_test_field_default_field_instances() {
  $field_instances = array();

  // Exported field_instance: 'log-farm_soil_test-field_farm_area'.
  $field_instances['log-farm_soil_test-field_farm_area'] = array(
    'bundle' => 'farm_soil_test',
    'default_value' => array(),
    'deleted' => 0,
    'description' => 'What areas is this sample being taken from?',
    'display' => array(
      'default' => array(
        'label' => 'inline',
        'module' => 'taxonomy',
        'settings' => array(),
        'type' => 'taxonomy_term_reference_link',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'log',
    'field_name' => 'field_farm_area',
    'label' => 'Areas',
    'required' => 0,
    'settings' => array(
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'options',
      'settings' => array(),
      'type' => 'options_select',
      'weight' => 6,
    ),
  );

  // Exported field_instance: 'log-farm_soil_test-field_farm_files'.
  $field_instances['log-farm_soil_test-field_farm_files'] = array(
    'bundle' => 'farm_soil_test',
    'default_value' => array(),
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'file',
        'settings' => array(),
        'type' => 'file_table',
        'weight' => 8,
      ),
    ),
    'entity_type' => 'log',
    'field_name' => 'field_farm_files',
    'label' => 'Files',
    'required' => 0,
    'settings' => array(
      'description_field' => 1,
      'file_directory' => 'farm/log/soil_test',
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
      'weight' => 7,
    ),
  );

  // Exported field_instance: 'log-farm_soil_test-field_farm_geofield'.
  $field_instances['log-farm_soil_test-field_farm_geofield'] = array(
    'bundle' => 'farm_soil_test',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => 'Use the map to pinpoint the location(s) from which this sample is taken. If you are preparing a soil test, you may be sampling multiple points within a given area. All the points that comprise this sample should be included.',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'openlayers_geofield',
        'settings' => array(
          'data' => 'full',
          'map_layer_preset' => 'farm_map_geofield_formatter:openlayers_geofield_layer_formatter',
        ),
        'type' => 'openlayers_geofield',
        'weight' => 1,
      ),
    ),
    'entity_type' => 'log',
    'field_name' => 'field_farm_geofield',
    'label' => 'Sample points',
    'required' => 0,
    'settings' => array(
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'openlayers_geofield',
      'settings' => array(
        'allow_edit' => 1,
        'data_storage' => 'collection',
        'delta_handling' => 'default',
        'feature_types' => array(
          'path' => 0,
          'point' => 'point',
          'polygon' => 0,
        ),
        'geocoder_field' => 'field_farm_files',
        'geocoder_handler' => 'kml',
        'handler_settings' => array(
          'google' => array(
            'all_results' => 0,
            'biasing' => array(
              'bounds' => '',
              'components' => '',
              'region' => '',
            ),
            'geometry_type' => 'point',
            'reject_results' => array(
              'APPROXIMATE' => 0,
              'GEOMETRIC_CENTER' => 0,
              'RANGE_INTERPOLATED' => 0,
              'ROOFTOP' => 0,
            ),
          ),
        ),
        'openlayers_map' => 'farm_map_geofield',
        'showInputField' => 1,
        'use_geocoder' => 1,
      ),
      'type' => 'openlayers_geofield',
      'weight' => 7,
    ),
  );

  // Exported field_instance: 'log-farm_soil_test-field_farm_images'.
  $field_instances['log-farm_soil_test-field_farm_images'] = array(
    'bundle' => 'farm_soil_test',
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
        'weight' => 7,
      ),
    ),
    'entity_type' => 'log',
    'field_name' => 'field_farm_images',
    'label' => 'Photos',
    'required' => 0,
    'settings' => array(
      'alt_field' => 0,
      'default_image' => 0,
      'file_directory' => 'farm/log/soil_test',
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
      'weight' => 6,
    ),
  );

  // Exported field_instance: 'log-farm_soil_test-field_farm_log_category'.
  $field_instances['log-farm_soil_test-field_farm_log_category'] = array(
    'bundle' => 'farm_soil_test',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => 'Use this to organize your logs into categories for easier searching and filtering later.',
    'display' => array(
      'default' => array(
        'label' => 'inline',
        'module' => 'taxonomy',
        'settings' => array(),
        'type' => 'taxonomy_term_reference_link',
        'weight' => 5,
      ),
    ),
    'entity_type' => 'log',
    'field_name' => 'field_farm_log_category',
    'label' => 'Log category',
    'required' => 0,
    'settings' => array(
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'options',
      'settings' => array(),
      'type' => 'options_select',
      'weight' => 11,
    ),
  );

  // Exported field_instance: 'log-farm_soil_test-field_farm_log_owner'.
  $field_instances['log-farm_soil_test-field_farm_log_owner'] = array(
    'bundle' => 'farm_soil_test',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => 'Optionally assign this task to one or more people.',
    'display' => array(
      'default' => array(
        'label' => 'inline',
        'module' => 'entityreference',
        'settings' => array(
          'bypass_access' => FALSE,
          'link' => FALSE,
        ),
        'type' => 'entityreference_label',
        'weight' => 6,
      ),
    ),
    'entity_type' => 'log',
    'field_name' => 'field_farm_log_owner',
    'label' => 'Assigned to',
    'required' => 0,
    'settings' => array(
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'options',
      'settings' => array(),
      'type' => 'options_select',
      'weight' => 9,
    ),
  );

  // Exported field_instance: 'log-farm_soil_test-field_farm_notes'.
  $field_instances['log-farm_soil_test-field_farm_notes'] = array(
    'bundle' => 'farm_soil_test',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'inline',
        'module' => 'text',
        'settings' => array(),
        'type' => 'text_default',
        'weight' => 4,
      ),
    ),
    'entity_type' => 'log',
    'field_name' => 'field_farm_notes',
    'label' => 'Notes',
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

  // Exported field_instance: 'log-farm_soil_test-field_farm_soil_lab'.
  $field_instances['log-farm_soil_test-field_farm_soil_lab'] = array(
    'bundle' => 'farm_soil_test',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => 'What laboratory performed the soil test?',
    'display' => array(
      'default' => array(
        'label' => 'inline',
        'module' => 'text',
        'settings' => array(),
        'type' => 'text_default',
        'weight' => 3,
      ),
    ),
    'entity_type' => 'log',
    'field_name' => 'field_farm_soil_lab',
    'label' => 'Laboratory',
    'required' => 0,
    'settings' => array(
      'text_processing' => 0,
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'text',
      'settings' => array(
        'size' => 60,
      ),
      'type' => 'text_textfield',
      'weight' => 1,
    ),
  );

  // Exported field_instance: 'log-farm_soil_test-field_farm_soil_names'.
  $field_instances['log-farm_soil_test-field_farm_soil_names'] = array(
    'bundle' => 'farm_soil_test',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => 'List the names of soils that are included in this sample. For soil maps and soil names, visit http://websoilsurvey.nrcs.usda.gov/app',
    'display' => array(
      'default' => array(
        'label' => 'inline',
        'module' => 'taxonomy',
        'settings' => array(),
        'type' => 'taxonomy_term_reference_link',
        'weight' => 2,
      ),
    ),
    'entity_type' => 'log',
    'field_name' => 'field_farm_soil_names',
    'label' => 'Soil Names',
    'required' => 0,
    'settings' => array(
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 0,
      'module' => 'taxonomy',
      'settings' => array(
        'autocomplete_path' => 'taxonomy/autocomplete',
        'size' => 60,
      ),
      'type' => 'taxonomy_autocomplete',
      'weight' => 8,
    ),
  );

  // Translatables
  // Included for use with string extractors like potx.
  t('Areas');
  t('Assigned to');
  t('Files');
  t('Laboratory');
  t('List the names of soils that are included in this sample. For soil maps and soil names, visit http://websoilsurvey.nrcs.usda.gov/app');
  t('Log category');
  t('Notes');
  t('Optionally assign this task to one or more people.');
  t('Photos');
  t('Sample points');
  t('Soil Names');
  t('Use the map to pinpoint the location(s) from which this sample is taken. If you are preparing a soil test, you may be sampling multiple points within a given area. All the points that comprise this sample should be included.');
  t('Use this to organize your logs into categories for easier searching and filtering later.');
  t('What areas is this sample being taken from?');
  t('What laboratory performed the soil test?');

  return $field_instances;
}
