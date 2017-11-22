<?php

/**
 * @file
 * Farm constraint hook implementations.
 */

/**
 * Implements hook_farm_constraint().
 */
function farm_constraint_farm_constraint($type, $id) {

  // This implementation of hook_farm_constraint provides constraints on behalf
  // of all entity and term reference fields in the system.

  // Define the field types, and entity type that they are used for.
  $field_types = array(
    'entityreference' => array(
      'farm_asset',
      'log',
      'user',
    ),
    'taxonomy_term_reference' => array(
      'taxonomy_term',
    ),
  );

  // Get information about all field instances.
  $instances = field_info_field_map();

  // Iterate through the instances.
  foreach ($instances as $field_name => $instance) {

    // If the field type is not one of the ones we care about, skip it.
    if (!array_key_exists($instance['type'], $field_types)) {
      continue;
    }

    // If the entity type does not match the field type, skip it.
    if (!in_array($type, $field_types[$instance['type']])) {
      continue;
    }

    // Load the field info.
    $field_info = field_info_field($field_name);

    // If this is an entityreference field, and the entity type does not match
    // the field target type, skip it.
    if (($instance['type'] == 'entityreference') && ($type != $field_info['settings']['target_type'])) {
      continue;
    }

    // Get the database storage details.
    $storage_details = $field_info['storage']['details'];

    // This only works with SQL, so skip if that information isn't available.
    if (empty($storage_details['sql']['FIELD_LOAD_CURRENT'])) {
      continue;
    }

    // Iterate through the database tables and column information. There should
    // only be one of each, so collect information about it.
    $table = '';
    $column = '';
    foreach ($storage_details['sql']['FIELD_LOAD_CURRENT'] as $table_name => $data) {
      $table = $table_name;
      foreach ($data as $key => $column_name) {
        $column = $column_name;
      }
    }

    // If a table and column are not found, skip.
    if (empty($table) || empty($column)) {
      continue;
    }

    // Finally, query the table to see if there are any references to this
    // entity.
    $references = db_query('SELECT COUNT(entity_id) FROM {' . $table . '} WHERE ' . $column . ' = :id', array(':id' => $id))->fetchField();

    // If references were found, a constraint was detected! Return TRUE.
    if (!empty($references)) {
      return TRUE;
    }
  }
}

