<?php

/**
 * @file
 * Post update functions for farm_fieldkit module.
 */

/**
 * Enable simple oauth password grant.
 */
function farm_fieldkit_post_update_enable_password_grant(&$sandbox = NULL) {

  // Enable password grant module.
  if (!\Drupal::service('module_handler')->moduleExists('simple_oauth_password_grant')) {
    \Drupal::service('module_installer')->install(['simple_oauth_password_grant']);
  }

  // Update existing fieldkit consumer.
  $consumers = \Drupal::entityTypeManager()->getStorage('consumer')
    ->loadByProperties(['client_id' => 'fieldkit']);
  if (!empty($consumers)) {
    /** @var \Drupal\consumers\Entity\ConsumerInterface $fieldkit */
    $fieldkit = reset($consumers);
    $fieldkit->set('user_id', NULL);
    $fieldkit->set('grant_types', ['refresh_token', 'password']);
    $fieldkit->save();
  }
}
