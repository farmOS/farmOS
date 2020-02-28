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

  // Load the list of farm roles.
  $roles = farm_access_roles();

  // Add permission for admins to administer oauth clients.
  if (!empty($roles[$role]['access']['config'])) {
    $perms[] = 'administer oauth clients';
  }

  // Grant access to RESTful Web Services resources.
  $perms[] = 'access farm api info';
  $perms[] = 'access resource farm_asset';
  $perms[] = 'access resource field_collection_item';
  $perms[] = 'access resource file';
  $perms[] = 'access resource log';
  $perms[] = 'access resource taxonomy_term';
  $perms[] = 'access resource taxonomy_vocabulary';
  $perms[] = 'access resource user';

  return $perms;
}
