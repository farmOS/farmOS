<?php
/**
 * @file
 * Farm Access module permissions.
 */

/**
 * Implements hook_farm_access_perms().
 */
function log_farm_access_perms($role) {
  $perms = array();

  // View all logs.
  $perms[] = 'view all logs';

  // Access RESTful Web Services log resources.
  $perms[] = 'access resource log';

  return $perms;
}

/**
 * Implements hook_farm_access_perms().
 */
function node_farm_access_perms($role) {
  $perms = array();

  // This is needed to view nodes and taxonomy terms.
  $perms[] = 'access content';

  return $perms;
}

/**
 * Implements hook_farm_access_perms().
 */
function system_farm_access_perms($role) {
  $perms = array();

  // Grant access to admin pages.
  $perms[] = 'access administration pages';

  return $perms;
}

/**
 * Implements hook_farm_access_perms().
 */
function taxonomy_farm_access_perms($role) {
  $perms = array();

  // This is needed to add terms.
  if (in_array($role, array('Farm Manager', 'Farm Worker'))) {
    $perms[] = 'administer taxonomy';
  }

  // Access RESTful Web Services taxonomy_term resources.
  $perms[] = 'access resource taxonomy_term';

  return $perms;
}
