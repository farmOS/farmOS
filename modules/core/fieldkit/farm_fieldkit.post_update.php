<?php

/**
 * @file
 * Post update functions for farm_fieldkit module.
 */

use Drupal\simple_oauth\Oauth2ScopeInterface;

/**
 * Enable simple oauth password grant.
 */
function farm_fieldkit_post_update_enable_password_grant(&$sandbox = NULL) {

  // Enable password grant module.
  if (!\Drupal::service('module_handler')->moduleExists('simple_oauth_password_grant')) {
    \Drupal::service('module_installer')->install(['simple_oauth_password_grant']);
  }

  // Check for default role scopes.
  /** @var \Drupal\simple_oauth\Oauth2ScopeProviderInterface $scope_provider */
  $scope_provider = \Drupal::service('simple_oauth.oauth2_scope.provider');
  $scopes = $scope_provider->loadMultiple(['farm_manager', 'farm_worker']);
  $scope_ids = array_map(function (Oauth2ScopeInterface $scope) {
    return $scope->id();
  }, $scopes);

  // Update existing fieldkit consumer.
  $consumers = \Drupal::entityTypeManager()->getStorage('consumer')
    ->loadByProperties(['client_id' => 'fieldkit']);
  if (!empty($consumers)) {
    /** @var \Drupal\consumers\Entity\ConsumerInterface $fieldkit */
    $fieldkit = reset($consumers);
    $fieldkit->set('grant_types', ['refresh_token', 'password']);
    $fieldkit->set('scopes', array_values($scope_ids));
    $fieldkit->save();
  }
}
