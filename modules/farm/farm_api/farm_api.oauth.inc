<?php
/**
 * @file
 * Farm api form for configuring OAuth Clients.
 */

/**
 * OAuth client configuration form.
 */
function farm_api_oauth_settings_form($form, &$form_state) {
  // Ask modules for oauth client rediect uri.
  $clients = module_invoke_all('farm_api_oauth2_client');

  if (empty($clients)) {
    $form['empty'] = array(
      '#type' => 'markup',
      '#markup' => 'There are no OAuth Clients available.',
    );
    return $form;
  }

  // Create a set of checkboxes for the clients.
  $options = array();
  foreach ($clients as $key => $client) {
    if (!empty($client['label'])) {
      $options[$key] = $client['label'];
    }
  }

  // Load the list of enabled quick forms from a variable.
  $enabled_oauth_clients = variable_get('farm_api_enabled_clients', array());

  // Display as a list of checkboxes.
  $form['farm_api_enabled_clients'] = array(
    '#type' => 'checkboxes',
    '#title' => t('Enable or disable OAuth Clients.'),
    '#options' => $options,
    '#default_value' => $enabled_oauth_clients,
  );

  // Wrap it in a system settings form.
  $form = system_settings_form($form);
  $form['#submit'][] = 'farm_api_oauth_settings_form_submit';

  // Return the form.
  return $form;

}

/**
 * Form submit handler for farm_api_oauth_settings_form
 */
function farm_api_oauth_settings_form_submit(array $form, array &$form_state) {

  // Save the submitted form values.
  $submitted = $form_state['values']['farm_api_enabled_clients'];

  // Filter only the enabled clients.
  $submitted_enabled = array_filter($submitted);

  // Start an array to collect existing clients.
  // It is easier to check this list than an array of
  // OAuth2 Server Client entities.
  $enabled_clients = array();

  // Load oauth2_server clients
  $query = new EntityFieldQuery();
  $query->entityCondition('entity_type', 'oauth2_server_client');
  $result = $query->execute();

  // Check if we need to disable any existing clients.
  if (isset($result['oauth2_server_client'])) {

    // Load the entities
    $client_ids = array_keys($result['oauth2_server_client']);
    $active_clients = entity_load('oauth2_server_client', $client_ids);

    // Check for the "client_key" in supplied hooks.
    foreach ($active_clients as $active_client) {
      // Load the client key.
      $client_wrapper = entity_metadata_wrapper('oauth2_server_client', $active_client);
      $client_key = $client_wrapper->client_key->value();
      $client_label = $client_wrapper->label->value();

      // Check if client is included with the the form.
      if (isset($submitted[$client_key])) {

        // See if the client should be enabled.
        $enabled = isset($submitted_enabled[$client_key]);

        // If disabled, remove the client.
        if (!$enabled) {

          // Delete OAuth2 Server Client Entity
          $client_id = $client_wrapper->getIdentifier();
          entity_delete('oauth2_server_client', $client_id);

          // Notify that client was disabled.
          drupal_set_message('Disabled OAuth Client: ' . $client_label);
        } else {

          // This client is still enabled. Add to enabled list.
          $enabled_clients[] = $client_key;
        }
      }
    }
  }

  // Ask modules for oauth clients.
  $clients = module_invoke_all('farm_api_oauth2_client');
  foreach($clients as $client) {

    // Save the client key.
    $client_key = $client['client_key'];

    // See if the client should be enabled.
    $enabled = isset($submitted_enabled[$client_key]);

    // Enable the client if not already enabled.
    if ($enabled && !in_array($client_key, $enabled_clients)) {

      $server_name = variable_get('farmos_oauth2_server_name', 'farmos_oauth');

      // Create OAuth2 Server Client Entity
      $new_client = entity_create('oauth2_server_client', array());
      $new_client->server = $server_name;
      $new_client->label = $client['label'];
      $new_client->client_key = $client['client_key'];
      // The module supports entering multiple redirect uris separated by a
      // newline. Both a dummy and the real uri are specified to confirm that
      // validation passes.
      $new_client->redirect_uri = $client['redirect_uri'];
      $new_client->automatic_authorization = FALSE;
      $new_client->save();

      // Notify that client was created.
      drupal_set_message('Created OAuth Client for ' . $client['label']);
    }
  }
}

/**
 * Implements hook_form().
 * Callback page after authorizing OAuth2 farmOS API Clients.
 */
function farm_api_oauth_authorized_form($form, &$form_state){
  global $base_root;
  $request_url = $base_root . request_uri();

  $params = drupal_get_query_parameters();

  // Load JS to load data from URL Fragments that aren't sent to the server into the form fields
  $form['#attached']['js'][] = drupal_get_path('module', 'farm_api') . '/api_callback.js';

  $form['redirect_url'] = array(
    '#type' => 'textfield',
    '#title' => t('Redirect URI'),
    '#description' => t('Copy this link which includes the following values:'),
    '#default_value' => $request_url,
    '#attributes' => array('readonly' => 'readonly'),
  );

  // Only display authorization_code if 'code' is in the query parameters.
  $form['authorization_code'] = array(
    '#type' => 'textfield',
    '#title' => t('Authorization Code'),
    '#description' => t('Use the Authorization Code to get an Access Token.'),
    '#default_value' => isset($params['code']) ? $params['code'] : '',
    '#attributes' => array('readonly' => 'readonly'),
    '#access' => isset($params['code']),
  );

  // Only display auth_code_state if 'code' is in the query parameters.
  // This displays the same 'state' parameter as below - but is an additional
  // form element to make updaing input values with JS easier.
  $form['auth_code_state'] = array(
    '#type' => 'textfield',
    '#title' => t('State'),
    '#description' => t('Include this in your header to maintain CORS.'),
    '#default_value' => isset($params['state']) ? $params['state'] : '',
    '#attributes' => array('readonly' => 'readonly'),
    '#access' => isset($params['code']),
  );

  // Only display the following input fields if 'code' is not in the query parameters.
  // That means is is not an Authorization Code Flow, so these values exist.
  // The following fields are populated with JS in /api_callback.js
  $form['access_token'] = array(
    '#type' => 'textfield',
    '#title' => t('Access Token'),
    '#description' => t('Include this Token in an HTTP \'Bearer Authentication\' Header to access
      protected resources.'),
    '#attributes' => array('readonly' => 'readonly'),
    '#access' => !isset($params['code']),
  );

  $form['expires_in'] = array(
    '#type' => 'textfield',
    '#title' => t('Expires In'),
    '#description' => t('Seconds until expiration.'),
    '#attributes' => array('readonly' => 'readonly'),
    '#access' => !isset($params['code']),
  );

  $form['token_type'] = array(
    '#type' => 'textfield',
    '#title' => t('Token Type'),
    '#description' => t('Bearer by default.'),
    '#attributes' => array('readonly' => 'readonly'),
    '#access' => !isset($params['code']),
  );

  $form['scope'] = array(
    '#type' => 'textfield',
    '#title' => t('Scope'),
    '#description' => t('Authorized OAuth2 Scopes'),
    '#attributes' => array('readonly' => 'readonly'),
    '#access' => !isset($params['code']),
  );

  $form['state'] = array(
    '#type' => 'textfield',
    '#title' => t('State'),
    '#description' => t('Include this in your header to maintain CORS.'),
    '#attributes' => array('readonly' => 'readonly'),
    '#access' => !isset($params['code']),
  );

  return $form;
}
