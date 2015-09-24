<?php
/**
 * @file
 * Farm Manager module permissions.
 */

/**
 * Implements hook_farm_manager_perms().
 */
function farm_asset_farm_manager_perms() {
  $perms = array();

  // Grant manager access to RESTful Web Services resources.
  $perms[] = 'access resource farm_asset';

  return $perms;
}

/**
 * Implements hook_farm_manager_perms().
 */
function log_farm_manager_perms() {
  $perms = array();

  // Access farm dashboard.
  $perms[] = 'view log plan';

  // Access RESTful Web Services log resources.
  $perms[] = 'access resource log';

  return $perms;
}

/**
 * Implements hook_farm_manager_perms().
 */
function node_farm_manager_perms() {
  $perms = array();

  // This is needed to view nodes and taxonomy terms.
  $perms[] = 'access content';

  return $perms;
}

/**
 * Implements hook_farm_manager_perms().
 */
function system_farm_manager_perms() {
  $perms = array();

  // Give farm managers the ability to see admin pages.
  $perms[] = 'access administration pages';

  return $perms;
}

/**
 * Implements hook_farm_manager_perms().
 */
function taxonomy_farm_manager_perms() {
  $perms = array();

  // This is needed to add terms.
  $perms[] = 'administer taxonomy';

  // Access RESTful Web Services taxonomy_term resources.
  $perms[] = 'access resource taxonomy_term';

  return $perms;
}
