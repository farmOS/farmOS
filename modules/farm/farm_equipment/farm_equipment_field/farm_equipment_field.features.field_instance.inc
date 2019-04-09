<?php
/**
 * @file
 * farm_equipment_field.features.field_instance.inc
 */

/**
 * Implements hook_field_default_field_instances().
 */
function farm_equipment_field_field_default_field_instances() {
  $field_instances = array();

  // Get a list of log types.
  $log_types = log_types();

  // Iterate through the log types and add an equipment reference field to each.
  foreach ($log_types as $bundle => $info) {
    $field_instances['log-' . $bundle . '-field_farm_equipment'] = array(
      'bundle' => $bundle,
      'deleted' => 0,
      'description' => 'What equipment was used?',
      'display' => array(
        'default' => array(
          'label' => 'inline',
          'module' => 'entityreference',
          'settings' => array(
            'bypass_access' => FALSE,
            'link' => 1,
          ),
          'type' => 'entityreference_label',
          'weight' => 0,
        ),
      ),
      'entity_type' => 'log',
      'field_name' => 'field_farm_equipment',
      'label' => t('Equipment used'),
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
        'weight' => 2,
      ),
    );
  }

  return $field_instances;
}
