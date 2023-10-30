<?php

namespace Druapl\tests\farm_login\Functional;

use Drupal\Component\Serialization\Json;
use Drupal\Tests\farm_api\Functional\OauthTestBase;

/**
 * Tests using an email with OAuth Password Grant.
 *
 * These tests are based on the simple_oauth PasswordFunctionalTests.
 *
 * @see \Drupal\Tests\simple_oauth\Functional\PasswordFunctionalTest
 *
 * @group farm
 */
class OauthPasswordTest extends OauthTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'image',
    'node',
    'serialization',
    'simple_oauth',
    'text',
    'user',
    'farm_api_default_consumer',
    'farm_api_test',
    'farm_login',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    // Add support for password grant and password scope consumer.
    $this->client->get('grant_types')->appendItem('password');
    $this->client->set('scopes', ['test:password']);
    $this->client->save();
    $this->scope = 'test:password';
  }

  /**
   * Test a valid Password grant using username and email.
   */
  public function testPasswordGrant() {
    $valid_payload = [
      'grant_type' => 'password',
      'client_id' => $this->client->get('client_id')->value,
      'client_secret' => $this->clientSecret,
      'username' => $this->user->getAccountName(),
      'password' => $this->user->pass_raw,
      'scope' => $this->scope,
    ];

    // 1. Test the password grant with a username.
    $response = $this->post($this->url, $valid_payload);
    $this->assertValidTokenResponse($response, TRUE);

    // 2. Test the password grant with an email as the username.
    $payload_client_id = $valid_payload;
    $payload_client_id['username'] = $this->user->getEmail();
    $response = $this->post($this->url, $payload_client_id);
    $this->assertValidTokenResponse($response, TRUE);
  }

  /**
   * Test an invalid Password grant.
   */
  public function testInvalidPasswordGrant() {
    $valid_payload = [
      'grant_type' => 'password',
      'client_id' => $this->client->get('client_id')->value,
      'client_secret' => $this->clientSecret,
      'username' => $this->user->getAccountName(),
      'password' => $this->user->pass_raw,
      'scope' => $this->scope,
    ];

    // 1. Test the password grant with an invalid username.
    $invalid_payload = $valid_payload;
    $invalid_payload['username'] = $this->getRandomGenerator()->string();
    $response = $this->post($this->url, $invalid_payload);
    $parsed_response = Json::decode((string) $response->getBody());
    $this->assertSame('invalid_grant', $parsed_response['error']);
    $this->assertSame(400, $response->getStatusCode());

    // 2. Test the password grant with an invalid password.
    $invalid_payload = $valid_payload;
    $invalid_payload['password'] = $this->getRandomGenerator()->string();
    $response = $this->post($this->url, $invalid_payload);
    $parsed_response = Json::decode((string) $response->getBody());
    $this->assertSame('invalid_grant', $parsed_response['error']);
    $this->assertSame(400, $response->getStatusCode());
  }

}
