<?php
/**
 * @file
 * Farm access hooks implemented by farm_api module.
 */

/**
 * Implements hook_farm_access_perms().
 */
function farm_api_farm_access_perms($role) {
  $perms = array();

  // Grant access to RESTful Web Services resources.
  $perms[] = 'access resource farm_asset';
  $perms[] = 'access resource log';
  $perms[] = 'access resource taxonomy_term';

  return $perms;
}
