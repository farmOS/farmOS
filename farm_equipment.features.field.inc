<?php
/**
 * @file
 * farm_equipment.features.field.inc
 */

/**
 * Implements hook_field_default_fields().
 */
function farm_equipment_field_default_fields() {
  $fields = array();

  // Exported field: 'log-farm_maintenance-field_farm_equipment'.
  $fields['log-farm_maintenance-field_farm_equipment'] = array(
    'field_config' => array(
      'active' => '1',
      'cardinality' => '1',
      'deleted' => '0',
      'entity_types' => array(),
      'field_name' => 'field_farm_equipment',
      'foreign keys' => array(
        'farm_asset' => array(
          'columns' => array(
            'target_id' => 'id',
          ),
          'table' => 'farm_asset',
        ),
      ),
      'indexes' => array(
        'target_id' => array(
          0 => 'target_id',
        ),
      ),
      'locked' => '0',
      'module' => 'entityreference',
      'settings' => array(
        'handler' => 'base',
        'handler_settings' => array(
          'behaviors' => array(
            'views-select-list' => array(
              'status' => 0,
            ),
          ),
          'sort' => array(
            'type' => 'none',
          ),
          'target_bundles' => array(
            'equipment' => 'equipment',
          ),
        ),
        'target_type' => 'farm_asset',
      ),
      'translatable' => '0',
      'type' => 'entityreference',
    ),
    'field_instance' => array(
      'bundle' => 'farm_maintenance',
      'default_value' => NULL,
      'deleted' => '0',
      'description' => 'What equipment is being maintained?',
      'display' => array(
        'default' => array(
          'label' => 'inline',
          'module' => 'entityreference',
          'settings' => array(
            'link' => 1,
          ),
          'type' => 'entityreference_label',
          'weight' => '0',
        ),
      ),
      'entity_type' => 'log',
      'field_name' => 'field_farm_equipment',
      'label' => 'Equipment',
      'required' => 1,
      'settings' => array(
        'user_register_form' => FALSE,
      ),
      'widget' => array(
        'active' => 1,
        'module' => 'entityreference',
        'settings' => array(
          'match_operator' => 'CONTAINS',
          'path' => '',
          'size' => '60',
        ),
        'type' => 'entityreference_autocomplete',
        'weight' => '2',
      ),
    ),
  );

  // Exported field: 'log-farm_maintenance-field_farm_notes'.
  $fields['log-farm_maintenance-field_farm_notes'] = array(
    'field_config' => array(
      'active' => '1',
      'cardinality' => '1',
      'deleted' => '0',
      'entity_types' => array(),
      'field_name' => 'field_farm_notes',
      'foreign keys' => array(
        'format' => array(
          'columns' => array(
            'format' => 'format',
          ),
          'table' => 'filter_format',
        ),
      ),
      'indexes' => array(
        'format' => array(
          0 => 'format',
        ),
      ),
      'locked' => '0',
      'module' => 'text',
      'settings' => array(),
      'translatable' => '0',
      'type' => 'text_long',
    ),
    'field_instance' => array(
      'bundle' => 'farm_maintenance',
      'default_value' => NULL,
      'deleted' => '0',
      'description' => '',
      'display' => array(
        'default' => array(
          'label' => 'inline',
          'module' => 'text',
          'settings' => array(),
          'type' => 'text_default',
          'weight' => '2',
        ),
      ),
      'entity_type' => 'log',
      'field_name' => 'field_farm_notes',
      'label' => 'Notes',
      'required' => 0,
      'settings' => array(
        'text_processing' => '0',
        'user_register_form' => FALSE,
      ),
      'widget' => array(
        'active' => 1,
        'module' => 'text',
        'settings' => array(
          'rows' => '5',
        ),
        'type' => 'text_textarea',
        'weight' => '3',
      ),
    ),
  );

  // Translatables
  // Included for use with string extractors like potx.
  t('Equipment');
  t('Notes');
  t('What equipment is being maintained?');

  return $fields;
}
