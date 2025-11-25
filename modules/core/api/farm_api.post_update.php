<?php

/**
 * @file
 * Post update functions for farm_api module.
 */

declare(strict_types=1);

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

/**
 * Enable static oauth2 scopes.
 */
function farm_api_post_update_enable_static_oauth2_scopes(&$sandbox = NULL) {

  // Enable static scope module.
  if (!\Drupal::service('module_handler')->moduleExists('simple_oauth_static_scope')) {
    \Drupal::service('module_installer')->install(['simple_oauth_static_scope']);
  }

  // Use static scope provider.
  $simple_oauth_settings = \Drupal::configFactory()->getEditable('simple_oauth.settings');
  $simple_oauth_settings->set('scope_provider', 'static');
  $simple_oauth_settings->save();
}

/**
 * Enable default consumer module.
 */
function farm_api_post_update_enable_default_consumer_module(&$sandbox = NULL) {

  // Check for an existing farm default consumer.
  $consumers = \Drupal::entityTypeManager()->getStorage('consumer')
    ->loadByProperties(['client_id' => 'farm']);
  if (!empty($consumers)) {

    // Enable default consumer module.
    if (!\Drupal::service('module_handler')->moduleExists('farm_api_default_consumer')) {
      \Drupal::service('module_installer')->install(['farm_api_default_consumer']);
    }

    // Update values on the consumer.
    /** @var \Drupal\consumers\Entity\ConsumerInterface $farm_default */
    $farm_default = reset($consumers);
    $farm_default->set('grant_types', ['authorization_code', 'refresh_token', 'password']);
    $farm_default->save();
  }

}

/**
 * Uninstall the JSON:API Extras module, unless other modules depend on it.
 */
function farm_api_post_update_uninstall_jsonapi_extras(&$sandbox = NULL) {
  if (\Drupal::service('module_handler')->moduleExists('jsonapi_extras')) {
    $modules = \Drupal::service('extension.list.module')->reset()->getList();
    $installed_dependents = [];
    if (!empty($modules['jsonapi_extras']->required_by)) {
      foreach (array_keys($modules['jsonapi_extras']->required_by) as $module) {
        if (\Drupal::service('module_handler')->moduleExists($module)) {
          $installed_dependents[] = $module;
        }
      }
    }
    if (empty($installed_dependents)) {
      \Drupal::service('module_installer')->uninstall(['jsonapi_extras']);
    }
  }
}
