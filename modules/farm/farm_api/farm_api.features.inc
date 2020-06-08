<?php

/**
 * @file
 * farm_api.features.inc
 */

/**
 * Implements hook_default_oauth2_server().
 */
function farm_api_default_oauth2_server() {
  $items = array();
  $items['farmos_oauth'] = entity_import('oauth2_server', '{
    "name" : "farmos_oauth",
    "label" : "Farm OAuth Server",
    "settings" : {
      "enforce_state" : true,
      "allow_implicit" : false,
      "use_openid_connect" : false,
      "use_crypto_tokens" : false,
      "store_encrypted_token_string" : false,
      "require_exact_redirect_uri" : true,
      "grant_types" : {
        "authorization_code" : "authorization_code",
        "refresh_token" : "refresh_token",
        "password" : "password"
      },
      "always_issue_new_refresh_token" : true,
      "access_lifetime" : 3600,
      "id_lifetime" : 3600,
      "refresh_token_lifetime" : 1209600
    },
    "rdf_mapping" : [],
    "scopes" : [
      {
        "name" : "user_access",
        "description" : "Allow full user access to farmOS records.",
        "rdf_mapping" : []
      },
      {
        "name" : "farm_info",
        "description" : "Allow access to farmOS server info.",
        "rdf_mapping" : []
      }
    ]
  }');
  return $items;
}
