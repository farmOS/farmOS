<?php

/**
 * @file
 * Hooks provided by farm_plan.
 *
 * This file contains no working PHP code; it exists to provide additional
 * documentation for doxygen as well as to document hooks in the standard
 * Drupal manner.
 */

/**
 * @defgroup farm_plan Farm plan module integrations.
 *
 * Module integrations with the farm_plan module.
 */

/**
 * @defgroup farm_plan_hooks Farm plan's hooks
 * @{
 * Hooks that can be implemented by other modules in order to extend farm_plan.
 */

/**
 * Define farm plan record relationships.
 *
 * This will be used to automatically integrate with Views, and create
 * constraints on linked records.
 *
 * @return array
 *   Returns an array of record types with the database table and field names
 *   used to store relationships, using the following keys:
 *     - label: The human-readable label for this record type.
 *     - entity_type: The Drupal entity type.
 *     - entity_pk: The column name of the entity type's primary key.
 *     - table: The table that links the record to the plan.
 *     - field: The column name in the table that links to the record's PK.
 */
function hook_farm_plan_record_relationships() {
  return array(
    'my_plan_log' => array(
      'label' => t('Log'),
      'entity_type' => 'log',
      'entity_pk' => 'id',
      'table' => 'my_plan_log',
      'field' => 'log_id',
    ),
  );
}

/**
 * @}
 */
