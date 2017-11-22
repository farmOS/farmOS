<?php

/**
 * @file
 * Farm constraint hook implementations.
 */

/**
 * Implements hook_farm_constraint().
 */
function farm_constraint_farm_constraint($type, $id) {

  // Check to see if there are any field references to this entity.
  // Limit to 1 because we don't need a full list of references, we only want
  // to know if ANY references exist.
  $limit = 1;
  $references = farm_constraint_field_references($type, $id, $limit);

  // If references were found, a constraint was detected! Return TRUE.
  if (!empty($references)) {
    return TRUE;
  }

  // Otherwise, return FALSE.
  return FALSE;
}

/**
 * Find all field references to an entity.
 *
 * @param $type
 *   The entity type.
 * @param $id
 *   The entity id.
 * @param $limit
 *   An optional limit to the number of references to return. This is disabled
 *   by default, which means all references will be returned. Setting this to
 *   1 is a good way to check if ANY references exist without loading all of
 *   them. It will stop looking as soon as the limit is reached.
 *
 * @return array
 *   Returns an array of references to the entity.
 */
function farm_constraint_field_references($type, $id, $limit = FALSE) {

  // Start an empty references array.
  $references = array();

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
    $result = db_query('SELECT entity_type, entity_id FROM {' . $table . '} WHERE ' . $column . ' = :id AND deleted != 1', array(':id' => $id));

    // Iterate through the results.
    foreach ($result as $row) {
      if (!empty($row)) {

        // Add the reference to the array.
        $references[] = array(
          'entity_type' => $row->entity_type,
          'entity_id' => $row->entity_id,
          'field' => $field_name,
          'table' => $table,
          'column' => $column,
        );

        // If we are at or over our limit, return now.
        if (!empty($limit) && count($references) >= $limit) {
          return $references;
        }
      }
    }
  }

  // Return the references array.
  return $references;
}
