<?php

/**
 * @file
 * Farm constraint hook implementations.
 */

/**
 * Implements hook_farm_constraint().
 */
function farm_plan_farm_constraint($type, $bundle, $id) {

  // Get available relationships between plans and other record types.
  $relationships = farm_plan_record_relationships();

  // Define plan references to check for
  // (see farm_constraint_table_references_exist()).
  $plan_references = array();
  foreach ($relationships as $record_type => $info) {
    $plan_references[$record_type] = array(
      'type' => $info['entity_type'],
      'tables' => array(
        $info['table'] => $info['field'],
      ),
    );
  }

  // Check to see if references exist.
  return farm_constraint_table_references_exist($plan_references, $type, $bundle, $id);
}
