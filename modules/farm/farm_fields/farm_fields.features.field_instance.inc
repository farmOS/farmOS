<?php
/**
 * @file
 * farm_fields.features.field_instance.inc
 */

/**
 * Implements hook_field_default_field_instances().
 */
function farm_fields_field_default_field_instances() {
  $field_instances = array();

  // Get a list of log types.
  $log_types = log_types();

  // Iterate through the log types and add a data field to each.
  foreach ($log_types as $bundle => $info) {
    $field_instances['log-' . $bundle . '-field_farm_data'] = array(
      'bundle' => $bundle,
      'default_value' => NULL,
      'deleted' => 0,
      'description' => t('The data field can be used to store arbitrary data on the log.'),
      'display' => array(
        'default' => array(
          'label' => 'above',
          'type' => 'hidden',
          'weight' => 0,
        ),
      ),
      'entity_type' => 'log',
      'field_name' => 'field_farm_data',
      'label' => t('Data'),
      'required' => 0,
      'settings' => array(
        'text_processing' => 0,
        'user_register_form' => FALSE,
      ),
      'widget' => array(
        'active' => 1,
        'module' => 'text',
        'settings' => array(
          'rows' => 5,
        ),
        'type' => 'text_textarea',
        'weight' => 0,
      ),
    );
  }

  return $field_instances;
}
