<?php

/**
 * @file
 * Farm constraint hook implementations.
 */

/**
 * Implements hook_farm_constraint().
 */
function farm_constraint_farm_constraint($type, $bundle, $id) {

  // Start an empty array of constraints.
  $constraints = array();

  // Check to see if there are any field references to this entity and add them
  // to the constraints.
  $references = farm_constraint_field_references($type, $id);
  if (!empty($references)) {
    $constraints += $references;
  }

  // If this is a taxonomy term, check to see if it has any children and add
  // them to the constraints.
  if ($type == 'taxonomy_term') {
    $children = farm_constraint_taxonomy_term_children($id);
    if (!empty($children)) {
      $constraints += $children;
    }
  }

  // Return constraints.
  return $constraints;
}

/**
 * Find all field references to an entity.
 *
 * @param $type
 *   The entity type.
 * @param $id
 *   The entity id.
 *
 * @return array
 *   Returns an array of references to the entity.
 */
function farm_constraint_field_references($type, $id) {

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

    // Iterate through the results and add the reference to the array.
    foreach ($result as $row) {
      if (!empty($row)) {
        $references[] = array(
          'constraint' => 'field_reference',
          'entity_type' => $row->entity_type,
          'entity_id' => $row->entity_id,
          'field' => $field_name,
          'table' => $table,
          'column' => $column,
        );
      }
    }
  }

  // Return the references array.
  return $references;
}

/**
 * Find taxonomy term child constraints.
 *
 * @param $tid
 *   The taxonomy term ID.
 *
 * @return array
 *   Returns an array of references to the entity.
 */
function farm_constraint_taxonomy_term_children($tid) {

  // Start an empty children array.
  $children = array();

  // Query the term hierarchy table to find child terms.
  $result = db_query('SELECT tid FROM {taxonomy_term_hierarchy} WHERE parent = :id', array(':id' => $tid));

  // Iterate through the results and add the reference to the array.
  foreach ($result as $row) {
    if (!empty($row)) {
      $children[] = array(
        'constraint' => 'taxonomy_term_child',
        'entity_type' => 'taxonomy_term',
        'entity_id' => $row->tid,
      );
    }
  }

  // Return the children array.
  return $children;
}
