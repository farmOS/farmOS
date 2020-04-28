<?php
/**
 * @file
 * farm_trait.features.field_base.inc
 */

/**
 * Implements hook_field_default_field_bases().
 */
function farm_trait_field_default_field_bases() {
  $field_bases = array();

  // Exported field_base: 'field_farm_trait'.
  $field_bases['field_farm_trait'] = array(
    'active' => 1,
    'cardinality' => -1,
    'deleted' => 0,
    'entity_types' => array(),
    'field_name' => 'field_farm_trait',
    'indexes' => array(
      'revision_id' => array(
        0 => 'revision_id',
      ),
      'value' => array(
        0 => 'value',
      ),
    ),
    'locked' => 0,
    'module' => 'field_collection',
    'settings' => array(
      'hide_blank_items' => 1,
      'hide_initial_item' => 1,
      'path' => '',
    ),
    'translatable' => 0,
    'type' => 'field_collection',
  );

  // Exported field_base: 'field_farm_trait_type'.
  $field_bases['field_farm_trait_type'] = array(
      'active' => 1,
      'cardinality' => -1,
      'deleted' => 0,
      'entity_types' => array(),
      'field_name' => 'field_farm_trait_type',
      'indexes' => array(
          'tid' => array(
              0 => 'tid',
          ),
      ),
      'locked' => 0,
      'module' => 'taxonomy',
      'settings' => array(
          'allowed_values' => array(
              0 => array(
                  'vocabulary' => 'farm_trait_types',
                  'parent' => 0,
              ),
          ),
      ),
      'translatable' => 0,
      'type' => 'taxonomy_term_reference',
  );

  // Exported field_base: 'field_farm_trait_value'.
  $field_bases['field_farm_trait_value'] = array(
      'active' => 1,
      'cardinality' => 1,
      'deleted' => 0,
      'entity_types' => array(),
      'field_name' => 'field_farm_trait_value',
      'indexes' => array(
          'format' => array(
              0 => 'format',
          ),
      ),
      'locked' => 0,
      'module' => 'text',
      'settings' => array(
          'max_length' => 64,
      ),
      'translatable' => 0,
      'type' => 'text',
  );

  // Exported field_base: 'field_farm_trait_type_ref_id'.
  $field_bases['field_farm_trait_type_ref_id'] = array(
    'active' => 1,
    'cardinality' => 1,
    'deleted' => 0,
    'entity_types' => array(),
    'field_name' => 'field_farm_trait_type_ref_id',
    'indexes' => array(
        'format' => array(
            0 => 'format',
        ),
    ),
    'locked' => 0,
    'module' => 'text',
    'settings' => array(
        'max_length' => 64,
    ),
    'translatable' => 0,
    'type' => 'text',
  );

  // Exported field_base: 'field_farm_trait_type_desc'.
  $field_bases['field_farm_trait_type_desc'] = array(
      'active' => 1,
      'cardinality' => 1,
      'deleted' => 0,
      'entity_types' => array(),
      'field_name' => 'field_farm_trait_type_desc',
      'indexes' => array(
          'format' => array(
              0 => 'format',
          ),
      ),
      'locked' => 0,
      'module' => 'text',
      'settings' => array(
          'max_length' => 255,
      ),
      'translatable' => 0,
      'type' => 'text',
  );

  return $field_bases;
}
