<?php

/**
 * @file
 * Provides Views data for farm_input module.
 */

/**
 * Implements hook_views_data().
 */
function farm_input_views_data() {

  $data = [];

  // Add a quantity_material_type pseudo field to the log_field_data table.
  // This pseudo field only has a filter configured to support filtering logs
  // by the quantity material type.
  $data['log_field_data']['quantity_material_type'] = [
    'description' => t('Filter by the material type of quantities referenced by this log.'),
    'entity_type' => 'quantity',
    'entity_field' => 'material_type',
    'filter' => [
      'title' => t('Quantity material type'),
      'description' => t('Filter by the material type of quantities referenced by this log.'),
      'id' => 'log_quantity_material_type',
      'field_name' => 'material_type',
    ],
  ];

  return $data;
}
