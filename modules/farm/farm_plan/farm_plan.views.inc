<?php

/**
 * @file
 * Farm plan views hooks.
 */

/**
 * Implements hook_views_data_alter().
 */
function farm_plan_views_data_alter(&$data) {

  // Get available relationships between plans and other record types.
  $relationships = farm_plan_record_relationships();

  // Iterate through the relationships.
  foreach ($relationships as $record_type => $info) {

    // If a database table, field, or entity type are not available, skip it.
    if (empty($info['table']) || empty($info['field']) || empty($info['entity_type'])) {
      continue;
    }

    // Describe the database table to Views.
    $data[$info['table']]['table']['group'] = t('Farm plan records');

    // Create an implicit relationship with the entity type table.
    // Sometimes the entity type table name is not the same as the entity type
    // machine name (for example: taxonomy_term entities are in a table named
    // taxonomy_term_data), so we allow for the entity type table name to be
    // defined explicitly. If it isn't, then we use the machine name.
    $entity_type_table = !empty($info['entity_type_table']) ? $info['entity_type_table'] : $info['entity_type'];
    $data[$info['table']]['table']['join'] = array(
      $entity_type_table => array(
        'left_field' => $info['entity_pk'],
        'field' => $info['field'],
      ),
    );

    // Describe the Plan ID column.
    $data[$info['table']]['plan_id'] = array(
      'title' => t('Plan ID'),
      'help' => t('The plan ID that this @entity_type is associated with.', array('@entity_type' => $info['entity_type'])),
      'relationship' => array(
        'base' => 'farm_plan',
        'field' => 'id',
        'label' => t('Plan'),
      ),
      'argument' => array(
        'help' => t('The plan associated with the @entity_type.', array('@entity_type' => $info['entity_type'])),
        'handler' => 'views_handler_argument_numeric',
      ),
    );
  }
}

/**
 * Implements hook_views_plugins().
 */
function farm_plan_views_plugins() {
  return array(
    'argument validator' => array(
      'farm_plan' => array(
        'title' => t('Farm plan'),
        'handler' => 'farm_plan_plugin_argument_validate_farm_plan',
        'path' => drupal_get_path('module', 'farm_plan') . '/views/plugins',
      ),
    ),
  );
}
