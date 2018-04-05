<?php

/**
 * @file
 * Definition of farm_fields_handler_filter_log_owner.
 */

/**
 * Provide an exposed filter that presents user options in a select list.
 *
 * @ingroup views_filter_handlers
 */
class farm_fields_handler_filter_log_owner extends farm_fields_handler_filter_entity {

  var $entity_type = 'user';

  /**
   * Define the list of user IDs to include in the exposed filter.
   */
  function entity_ids() {

    // Select all active users.
    $query = db_select('users', 'u');
    $query->addField('u', 'uid', 'id');
    $query->condition('u.status', 1);
    $query->orderby('u.name');

    // Add a custom tag to the query so other modules can modify.
    $query->addTag('farm_log_owner');

    // Execute the query.
    $result = $query->execute();

    // Create an array of IDs.
    $ids = array();
    foreach ($result as $row) {
      $ids[] = $row->id;
    }

    // Return the IDs.
    return $ids;
  }
}
