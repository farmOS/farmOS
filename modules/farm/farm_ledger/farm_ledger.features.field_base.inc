<?php
/**
 * @file
 * farm_ledger.features.field_base.inc
 */

/**
 * Implements hook_field_default_field_bases().
 */
function farm_ledger_field_default_field_bases() {
  $field_bases = array();

  // Exported field_base: 'field_farm_customer'.
  $field_bases['field_farm_customer'] = array(
    'active' => 1,
    'cardinality' => 1,
    'deleted' => 0,
    'entity_types' => array(),
    'field_name' => 'field_farm_customer',
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

  // Exported field_base: 'field_farm_invoice_number'.
  $field_bases['field_farm_invoice_number'] = array(
    'active' => 1,
    'cardinality' => 1,
    'deleted' => 0,
    'entity_types' => array(),
    'field_name' => 'field_farm_invoice_number',
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

  // Exported field_base: 'field_farm_total_price'.
  $field_bases['field_farm_total_price'] = array(
    'active' => 1,
    'cardinality' => 1,
    'deleted' => 0,
    'entity_types' => array(),
    'field_name' => 'field_farm_total_price',
    'indexes' => array(),
    'locked' => 0,
    'module' => 'fraction',
    'settings' => array(),
    'translatable' => 0,
    'type' => 'fraction',
  );

  // Exported field_base: 'field_farm_unit_price'.
  $field_bases['field_farm_unit_price'] = array(
    'active' => 1,
    'cardinality' => 1,
    'deleted' => 0,
    'entity_types' => array(),
    'field_name' => 'field_farm_unit_price',
    'indexes' => array(),
    'locked' => 0,
    'module' => 'fraction',
    'settings' => array(),
    'translatable' => 0,
    'type' => 'fraction',
  );

  return $field_bases;
}
