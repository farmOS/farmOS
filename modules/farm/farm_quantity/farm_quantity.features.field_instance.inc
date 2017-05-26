<?php
/**
 * @file
 * farm_quantity.features.field_instance.inc
 */

/**
 * Implements hook_field_default_field_instances().
 */
function farm_quantity_field_default_field_instances() {
  $field_instances = array();

  // Exported field_instance:
  // 'field_collection_item-field_farm_quantity-field_farm_quantity_units'.
  $field_instances['field_collection_item-field_farm_quantity-field_farm_quantity_units'] = array(
    'bundle' => 'field_farm_quantity',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'hidden',
        'module' => 'taxonomy',
        'settings' => array(),
        'type' => 'taxonomy_term_reference_plain',
        'weight' => 1,
      ),
    ),
    'entity_type' => 'field_collection_item',
    'field_name' => 'field_farm_quantity_units',
    'label' => 'Units',
    'required' => 1,
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
      'weight' => 1,
    ),
  );

  // Exported field_instance:
  // 'field_collection_item-field_farm_quantity-field_farm_quantity_value'.
  $field_instances['field_collection_item-field_farm_quantity-field_farm_quantity_value'] = array(
    'bundle' => 'field_farm_quantity',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'hidden',
        'module' => 'fraction',
        'settings' => array(
          'auto_precision' => 1,
          'precision' => 0,
        ),
        'type' => 'fraction_decimal',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'field_collection_item',
    'field_name' => 'field_farm_quantity_value',
    'label' => 'Value',
    'required' => 1,
    'settings' => array(
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'fraction',
      'settings' => array(
        'auto_precision' => 1,
        'precision' => 0,
      ),
      'type' => 'fraction_decimal',
      'weight' => 0,
    ),
  );

  // Translatables
  // Included for use with string extractors like potx.
  t('Units');
  t('Value');

  return $field_instances;
}
