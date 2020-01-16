<?php

/**
 * @file
 * Hooks provided by farm_api.
 *
 * This file contains no working PHP code; it exists to provide additional
 * documentation for doxygen as well as to document hooks in the standard
 * Drupal manner.
 */

/**
 * @defgroup farm_api Farm API module integrations.
 *
 * Module integrations with the farm_api module.
 */

/**
 * @defgroup farm_api_hooks Farm API's hooks
 * @{
 * Hooks that can be implemented by other modules in order to extend farm_api.
 */

/**
 * Provide general information about this farmOS system.
 *
 * @return array
 *   Returns an array of farm information.
 */
function hook_farm_info() {
  global $base_url, $conf, $user;

  // Info items can be added simply:
  $info = array(
    'name' => $conf['site_name'],
    'url' => $base_url,
  );

  //  Or an entire array of info can be added simply:
  if (!empty($user->uid)) {
    $info['user'] = array(
      'uid' => $user->uid,
      'name' => $user->name,
      'mail' => $user->mail,
    );
  }

  // Or, they can be arrays with 'info' and 'scope' keys. The 'info' is what
  // will be included in the farmOS API info array. The 'scope' is an OAuth2
  // scope that will be checked for access. This scope must be defined in
  // the farmos_api_client OAuth2 Server. This allows some information to be
  // available to OAuth2-authenticated services without a full user log in.
  $info['foo'] = array(
    'info' => 'bar',
    'scope' => 'access_foo',
  );

  return $info;
}

/**
 * @}
 */
