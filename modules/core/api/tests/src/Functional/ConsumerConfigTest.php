<?php

namespace Drupal\Tests\farm_api\Functional;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Url;
use Drupal\user\Entity\Role;

/**
 * Tests using the consumer.client_id field.
 *
 * @group farm
 */
class ConsumerConfigTest extends OauthTestBase {

  /**
   * The URL for debugging tokens.
   *
   * @var \Drupal\Core\Url
   */
  protected $tokenDebugUrl;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {

    parent::setUp();

    $this->tokenDebugUrl = Url::fromRoute('oauth2_token.user_debug');

    // Override the additional roles created by parent.
    $this->additionalRoles = [];
    for ($i = 0; $i < 4; $i++) {
      $role = Role::create([
        'id' => 'scope_' . $i,
        'label' => 'Scope: ' . $i,
        'is_admin' => FALSE,
      ]);
      $role->save();
      $this->additionalRoles[] = $role;
    }
  }

  /**
   * Test consumer.grant_user_access config.
   */
  public function testGrantUserAccess() {

    // Set up the client.
    $this->client->set('grant_user_access', FALSE);
    $this->client->set('limit_requested_access', FALSE);
    $this->client->set('limit_user_access', FALSE);
    $this->client->save();

    // Grant the user more roles than the consumer.
    $this->user->addRole('scope_1');
    $this->user->addRole('scope_2');
    $this->user->save();

    // 1. Test that only the consumers roles are granted.
    // Prepare expected roles. Include all roles the consumer has.
    $expected_roles = array_merge($this->getClientRoleIds(), ['authenticated']);
    // Check the token.
    $access_token = $this->getAccessToken();
    $token_info = $this->getTokenInfo($access_token);
    $this->assertEquals($this->user->id(), $token_info['id']);
    $this->assertEqualsCanonicalizing($expected_roles, $token_info['roles']);

    // 2. Test that the user's roles are granted as well.
    // Update the client.
    $this->client->set('grant_user_access', TRUE);
    $this->client->save();
    // Include the consumer + user roles.
    $expected_roles = array_merge($expected_roles, ['scope_1', 'scope_2']);
    // Check the token.
    $access_token = $this->getAccessToken();
    $token_info = $this->getTokenInfo($access_token);
    $this->assertEquals($this->user->id(), $token_info['id']);
    $this->assertEqualsCanonicalizing($expected_roles, $token_info['roles']);

    // 3. Test that additional roles are not granted.
    // Request "scope_3" even though it is not given to the user or consumer.
    // Check the token.
    $access_token = $this->getAccessToken(['scope_3']);
    $token_info = $this->getTokenInfo($access_token);
    $this->assertEquals($this->user->id(), $token_info['id']);
    $this->assertEqualsCanonicalizing($expected_roles, $token_info['roles']);
  }

  /**
   * Test consumer.limit_requested_access.
   */
  public function testLimitRequestedAccess() {

    // Set up the client.
    $this->client->set('grant_user_access', FALSE);
    $this->client->set('limit_requested_access', FALSE);
    $this->client->set('limit_user_access', FALSE);
    $this->client->save();

    // Grant the user additional roles.
    $this->user->addRole('scope_1');
    $this->user->addRole('scope_2');
    $this->user->save();

    // Grant the client additional roles.
    $client_roles = array_merge(
      $this->getClientRoleIds(),
      ['scope_3']
    );
    $this->grantClientRoles($client_roles);

    // Array of expected roles. Includes all roles the consumer has.
    $expected_roles = array_merge($client_roles, ['authenticated']);

    // 1. Test that all roles on the consumer are granted.
    $access_token = $this->getAccessToken();
    $token_info = $this->getTokenInfo($access_token);
    $this->assertEquals($this->user->id(), $token_info['id']);
    $this->assertEqualsCanonicalizing($expected_roles, $token_info['roles']);

    // 2. Test that only the requested scopes (roles) are granted.
    // Update the client.
    $this->client->set('limit_requested_access', TRUE);
    $this->client->save();
    $requested_roles = ['scope_3'];
    $expected_roles = array_merge($requested_roles, ['authenticated']);
    // Check the token.
    $access_token = $this->getAccessToken($requested_roles);
    $token_info = $this->getTokenInfo($access_token);
    $this->assertEquals($this->user->id(), $token_info['id']);
    $this->assertEqualsCanonicalizing($expected_roles, $token_info['roles']);

    // 3. Test only the requested roles are granted,
    // even if user roles are granted.
    $this->client->set('limit_requested_access', TRUE);
    $this->client->set('grant_user_access', TRUE);
    $this->client->save();
    $requested_roles = ['scope_1', 'scope_3'];
    $expected_roles = array_merge($requested_roles, ['authenticated']);
    // Check the token.
    $access_token = $this->getAccessToken($requested_roles);
    $token_info = $this->getTokenInfo($access_token);
    $this->assertEquals($this->user->id(), $token_info['id']);
    $this->assertEqualsCanonicalizing($expected_roles, $token_info['roles']);
  }

  /**
   * Test consumer.limit_user_access.
   */
  public function testLimitUserAccess() {

    // Set up the client.
    $this->client->set('grant_user_access', FALSE);
    $this->client->set('limit_requested_access', FALSE);
    $this->client->set('limit_user_access', FALSE);
    $this->client->save();

    // Grant the user one additional role.
    $this->user->addRole('scope_1');
    $this->user->save();

    // Grant the client all roles.
    $client_roles = array_merge(
      $this->getClientRoleIds(),
      ['scope_1', 'scope_2', 'scope_3']
    );
    $this->grantClientRoles($client_roles);

    // Array of expected roles. Includes all roles the consumer has.
    $expected_roles = array_merge($client_roles, ['authenticated']);

    // 1. Test that all roles on the consumer are granted.
    $access_token = $this->getAccessToken();
    $token_info = $this->getTokenInfo($access_token);
    $this->assertEquals($this->user->id(), $token_info['id']);
    $this->assertEqualsCanonicalizing($expected_roles, $token_info['roles']);

    // 2. Test that only the roles the user has are granted.
    // Update the client.
    $this->client->set('limit_user_access', TRUE);
    $this->client->save();
    $requested_roles = ['scope_1', 'scope_3'];
    $expected_roles = ['scope_1', 'authenticated'];
    // Check the token.
    $access_token = $this->getAccessToken($requested_roles);
    $token_info = $this->getTokenInfo($access_token);
    $this->assertEquals($this->user->id(), $token_info['id']);
    $this->assertEqualsCanonicalizing($expected_roles, $token_info['roles']);

    // 3. Test that limit_user_access and grant_user_access work together.
    $this->client->set('grant_user_access', TRUE);
    $this->client->set('limit_user_access', TRUE);
    $this->client->save();
    $requested_roles = [];
    $expected_roles = ['scope_1', 'authenticated'];
    // Check the token.
    $access_token = $this->getAccessToken($requested_roles);
    $token_info = $this->getTokenInfo($access_token);
    $this->assertEquals($this->user->id(), $token_info['id']);
    $this->assertEqualsCanonicalizing($expected_roles, $token_info['roles']);
  }

  /**
   * Return the response from oauth/debug.
   *
   * @param string $access_token
   *   The access_token to use for authentication.
   *
   * @return mixed
   *   The JSON parsed response.
   */
  private function getTokenInfo($access_token) {
    $response = $this->get(
      $this->tokenDebugUrl,
      [
        'query' => ['_format' => 'json'],
        'headers' => [
          'Authorization' => 'Bearer ' . $access_token,
        ],
      ]
    );
    return Json::decode((string) $response->getBody());
  }

  /**
   * Helper function to get role IDs the client has.
   *
   * @return array
   *   Array of role IDs.
   */
  private function getClientRoleIds() {
    return array_map(function ($role) {
      return $role['target_id'];
    }, $this->client->get('roles')->getValue());
  }

  /**
   * Helper function to grant roles to the client.
   *
   * @param array $role_ids
   *   Role IDs to add.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  private function grantClientRoles(array $role_ids) {
    $roles = [];
    foreach ($role_ids as $id) {
      $roles[] = ['target_id' => $id];
    }
    $this->client->set('roles', $roles);
    $this->client->save();
  }

  /**
   * Return an access token.
   *
   * @param array $scopes
   *   The scopes.
   *
   * @return string
   *   The access token.
   */
  private function getAccessToken(array $scopes = []) {
    $valid_payload = [
      'grant_type' => 'password',
      'client_id' => $this->client->get('client_id')->value,
      'username' => $this->user->getAccountName(),
      'password' => $this->user->pass_raw,
    ];
    if (!empty($scopes)) {
      $valid_payload['scope'] = implode(' ', $scopes);
    }
    $response = $this->post($this->url, $valid_payload);
    $parsed_response = Json::decode((string) $response->getBody());

    return isset($parsed_response['access_token'])
      ? $parsed_response['access_token']
      : NULL;
  }

}
