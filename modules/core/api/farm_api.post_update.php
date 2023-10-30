<?php

/**
 * @file
 * Post update functions for farm_settings module.
 */

/**
 * Remove farm_api consumer bundle fields.
 */
function farm_api_post_update_remove_consumer_fields(&$sandbox = NULL) {

  // Remove old consumer fields.
  $fields = [
    'grant_user_access',
    'limit_user_access',
    'limit_requested_access',
  ];
  foreach ($fields as $field) {
    $entity_definition_update_manager = \Drupal::entityDefinitionUpdateManager();
    $roles_field_definition = $entity_definition_update_manager->getFieldStorageDefinition($field, 'consumer');
    $entity_definition_update_manager->uninstallFieldStorageDefinition($roles_field_definition);
  }
}
