<?php
/**
 * @file
 * Farm plan consideration constraints.
 */

/**
 * Implements hook_farm_constraint().
 */
function farm_plan_consideration_farm_constraint($type, $bundle, $id) {

  // Start an empty constraints array.
  $constraints = array();

  // Check to see if this entity is referenced in the
  // {farm_plan_consideration_entity} table and add them as constraints.
  $result = db_query('SELECT consideration_id FROM {farm_plan_consideration_entity} WHERE entity_type = :entity_type AND entity_id = :entity_id', array(':entity_type' => $type, ':entity_id' => $id));
  foreach ($result as $row) {
    if (!empty($row)) {
      $constraints[] = array(
        'constraint' => 'farm_plan_consideration',
        'consideration_id' => $row->consideration_id,
        'entity_type' => $type,
        'entity_id' => $id,
      );
    }
  }

  // Return the constraints array.
  return $constraints;
}
