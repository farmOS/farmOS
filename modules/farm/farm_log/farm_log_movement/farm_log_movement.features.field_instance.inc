<?php
/**
 * @file
 * farm_log_movement.features.field_instance.inc
 */

/**
 * Implements hook_field_default_field_instances().
 */
function farm_log_movement_field_default_field_instances() {
  $field_instances = array();

  // Exported field_instance:
  // 'field_collection_item-field_farm_movement-field_farm_geofield'.
  $field_instances['field_collection_item-field_farm_movement-field_farm_geofield'] = array(
    'bundle' => 'field_farm_movement',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => 'This field allows you to optionally specify a more precise geometry for the new location of assets. If you leave it blank, the geometry will be copied from the areas that assets are moving to (if available).',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'openlayers_geofield',
        'settings' => array(
          'data' => 'full',
          'map_layer_preset' => 'farm_map_geofield_formatter:openlayers_geofield_layer_formatter',
        ),
        'type' => 'openlayers_geofield',
        'weight' => 2,
      ),
    ),
    'entity_type' => 'field_collection_item',
    'field_name' => 'field_farm_geofield',
    'label' => 'Movement geometry',
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
          'path' => 'path',
          'point' => 'point',
          'polygon' => 'polygon',
        ),
        'geocoder_field' => 'field_farm_animal_tag_location',
        'geocoder_handler' => 'bing',
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
        'use_geocoder' => 0,
      ),
      'type' => 'openlayers_geofield',
      'weight' => 6,
    ),
  );

  // Exported field_instance:
  // 'field_collection_item-field_farm_movement-field_farm_move_from'.
  $field_instances['field_collection_item-field_farm_movement-field_farm_move_from'] = array(
    'bundle' => 'field_farm_movement',
    'default_value' => array(),
    'deleted' => 0,
    'description' => 'Specify the area(s) that assets are being moved from. This field is optional, and is only used for reference. You may leave this blank as a way of setting initial location. If the assets currently have a location set from a previously logged movement, this field will be automatically filled using that information.',
    'display' => array(
      'default' => array(
        'label' => 'inline',
        'module' => 'taxonomy',
        'settings' => array(),
        'type' => 'taxonomy_term_reference_link',
        'weight' => 1,
      ),
    ),
    'entity_type' => 'field_collection_item',
    'field_name' => 'field_farm_move_from',
    'label' => 'Movement from',
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
        'view' => 'farm_area_entityreference_view|entityreference_view_widget',
        'view_mode' => 'full',
      ),
      'type' => 'entityreference_view_widget',
      'weight' => 4,
    ),
  );

  // Exported field_instance:
  // 'field_collection_item-field_farm_movement-field_farm_move_to'.
  $field_instances['field_collection_item-field_farm_movement-field_farm_move_to'] = array(
    'bundle' => 'field_farm_movement',
    'default_value' => array(),
    'deleted' => 0,
    'description' => 'Specify the area(s) that assets are being moved to. If you need to specify more precise locations (in addition to these areas), use the Geometry field below.',
    'display' => array(
      'default' => array(
        'label' => 'inline',
        'module' => 'taxonomy',
        'settings' => array(),
        'type' => 'taxonomy_term_reference_link',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'field_collection_item',
    'field_name' => 'field_farm_move_to',
    'label' => 'Movement to',
    'required' => 1,
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
        'view' => 'farm_area_entityreference_view|entityreference_view_widget',
        'view_mode' => 'full',
      ),
      'type' => 'entityreference_view_widget',
      'weight' => 2,
    ),
  );

  // Translatables
  // Included for use with string extractors like potx.
  t('Movement from');
  t('Movement geometry');
  t('Movement to');
  t('Specify the area(s) that assets are being moved from. This field is optional, and is only used for reference. You may leave this blank as a way of setting initial location. If the assets currently have a location set from a previously logged movement, this field will be automatically filled using that information.');
  t('Specify the area(s) that assets are being moved to. If you need to specify more precise locations (in addition to these areas), use the Geometry field below.');
  t('This field allows you to optionally specify a more precise geometry for the new location of assets. If you leave it blank, the geometry will be copied from the areas that assets are moving to (if available).');

  return $field_instances;
}
