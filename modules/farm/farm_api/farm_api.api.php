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

  // Or an entire array of info can be added simply:
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
 * Provide a client to the farmOS OAuth2 Server.
 *
 * @return array
 *   Returns an array of oauth2 clients.
 */
function hook_farm_api_oauth2_client() {
  $clients = array();

  // Define an array of redirect URI's used by the third party for the
  // OAuth Authorization Flow.
  $redirect_uris = array(
    'https://authorize.domain.com',
    'https://third.party.com/authorize',
  );

  $clients['third_party_name'] = array(

    // A name for the OAuth2 Client that will be displayed to farmOS Admins.
    'label' => 'Third Party Feature Integration',

    // Unique client machine name.
    'client_key' => 'third_party_machine_name',

    // Optional. OAuth Clients can require a client_secret to authorize with
    // that client. Most 3rd parties won't need this because the code
    // implementing this hook will likely be open source.
    // 'client_secret' => 'secret',

    // The oauth2_server module supports multiple redirect URIs separated by a
    // newline. Both a dummy and the real uri are specified to confirm that
    // validation passes.
    'redirect_uri' => implode("\n", $redirect_uris),

    // Optional. OAuth Clients can override some server-level OAuth settings.
    // Only supply these settings if overriding grant types.
    'settings' => array(
      'override_grant_types' => TRUE,

      // Allow the Implicit Grant type.
      'allow_implicit' => FALSE,

      // List of grant types the client will support.
      'grant_types' => array(
        'authorization_code' => 'authorization_code',
        'client_credentials' => 'client_credentials',
        'password' => 'password',
        'refresh_token' => 'refresh_token',
      ),

      // These do not default to the server default. Set these
      // settings when overriding server-level settings.
      'always_issue_new_refresh_token' => TRUE,
      'unset_refresh_token_after_use' => TRUE,
    )
  );

  return $clients;
}

/**
 * @}
 */
