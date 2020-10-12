<?php

namespace Drupal\Tests\farm_api\Functional;

use Drupal\Component\Serialization\Json;

/**
 * Tests using the consumer.client_id field.
 *
 * @group farm
 */
class ConsumerClientIdTest extends OauthTestBase {

  /**
   * Test a valid Password grant using the consumer.client_id field.
   */
  public function testValidClientId() {

    // 1. Test the valid request using a UUID.
    // Using the consumer.client_id instead of UUID should be optional.
    $valid_payload = [
      'grant_type' => 'password',
      'client_id' => $this->client->uuid(),
      'username' => $this->user->getAccountName(),
      'password' => $this->user->pass_raw,
      'scope' => $this->scope,
    ];
    $response = $this->post($this->url, $valid_payload);
    $this->assertValidTokenResponse($response, TRUE);

    // Repeat the request but pass an obtained access token as a header in
    // order to check the authentication in parallel, which will precede
    // the creation of a new token.
    $parsed = Json::decode((string) $response->getBody());
    $response = $this->post($this->url, $valid_payload, [
      'headers' => ['Authorization' => 'Bearer ' . $parsed['access_token']],
    ]);
    $this->assertValidTokenResponse($response, TRUE);

    // 2. Test the valid request using the consumer.client_id field.
    $payload_client_id = $valid_payload;
    $payload_client_id['client_id'] = $this->client->get('client_id')->value;
    $response = $this->post($this->url, $payload_client_id);
    $this->assertValidTokenResponse($response, TRUE);

    // 3. Test the valid request without scopes.
    $payload_no_scope = $valid_payload;
    unset($payload_no_scope['scope']);
    $response = $this->post($this->url, $payload_no_scope);
    $this->assertValidTokenResponse($response, TRUE);

    // 4. Test valid request using HTTP Basic Auth.
    $payload_no_client = $valid_payload;
    unset($payload_no_client['client_id']);
    $response = $this->post($this->url, $payload_no_scope,
      [
        'auth' => [
          $this->client->get('client_id')->value,
          '',
        ],
      ]
    );
    $this->assertValidTokenResponse($response, TRUE);
  }

  /**
   * Test invalid Password grant using the consumer.client_id field.
   */
  public function testInvalidClientId() {

    // Build a valid payload.
    $valid_payload = [
      'grant_type' => 'password',
      'client_id' => $this->client->get('client_id')->value,
      'username' => $this->user->getAccountName(),
      'password' => $this->user->pass_raw,
      'scope' => $this->scope,
    ];

    // 1. Test an incorrect client_id.
    $invalid_payload = $valid_payload;
    $invalid_payload['client_id'] = $this->getRandomGenerator()->string();
    $response = $this->post($this->url, $invalid_payload);
    $parsed_response = Json::decode((string) $response->getBody());
    $this->assertSame('invalid_client', $parsed_response['error']);
    $this->assertSame(401, $response->getStatusCode());

    // 2. Test a missing client_id.
    $invalid_payload = $valid_payload;
    unset($invalid_payload['client_id']);
    $response = $this->post($this->url, $invalid_payload);
    $parsed_response = Json::decode((string) $response->getBody());
    $this->assertSame('invalid_request', $parsed_response['error']);
    $this->assertSame(400, $response->getStatusCode());
  }

}
