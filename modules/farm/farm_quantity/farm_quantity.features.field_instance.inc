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
  // 'field_collection_item-field_farm_quantity-field_farm_quantity_label'.
  $field_instances['field_collection_item-field_farm_quantity-field_farm_quantity_label'] = array(
    'bundle' => 'field_farm_quantity',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => 'Optionally give this quantity measurement a label. This helps to differentiate multiple quantities with the same unit and measure that are in the same log.',
    'display' => array(
      'default' => array(
        'label' => 'hidden',
        'module' => 'text',
        'settings' => array(),
        'type' => 'text_default',
        'weight' => 1,
      ),
    ),
    'entity_type' => 'field_collection_item',
    'field_name' => 'field_farm_quantity_label',
    'label' => 'Label',
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
      'weight' => 3,
    ),
  );

  // Exported field_instance:
  // 'field_collection_item-field_farm_quantity-field_farm_quantity_measure'.
  $field_instances['field_collection_item-field_farm_quantity-field_farm_quantity_measure'] = array(
    'bundle' => 'field_farm_quantity',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => 'What type of measurement is this quantity?',
    'display' => array(
      'default' => array(
        'label' => 'hidden',
        'module' => 'list',
        'settings' => array(),
        'type' => 'list_default',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'field_collection_item',
    'field_name' => 'field_farm_quantity_measure',
    'label' => 'Measure',
    'required' => 0,
    'settings' => array(
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'options',
      'settings' => array(),
      'type' => 'options_select',
      'weight' => 0,
    ),
  );

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
        'weight' => 3,
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
      'weight' => 2,
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
          'prefix_suffix' => TRUE,
        ),
        'type' => 'fraction_decimal',
        'weight' => 2,
      ),
    ),
    'entity_type' => 'field_collection_item',
    'field_name' => 'field_farm_quantity_value',
    'label' => 'Value',
    'required' => 1,
    'settings' => array(
      'max' => '',
      'min' => '',
      'prefix' => '',
      'suffix' => '',
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
      'weight' => 1,
    ),
  );

  // Translatables
  // Included for use with string extractors like potx.
  t('Label');
  t('Measure');
  t('Optionally give this quantity measurement a label. This helps to differentiate multiple quantities with the same unit and measure that are in the same log.');
  t('Units');
  t('Value');
  t('What type of measurement is this quantity?');

  return $field_instances;
}
