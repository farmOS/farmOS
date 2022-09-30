<?php

namespace Drupal\Tests\farm_api\Functional;

use Drupal\consumers\Entity\Consumer;
use Drupal\Core\Url;
use Drupal\Tests\farm_test\Functional\FarmBrowserTestBase;
use Drupal\Tests\jsonapi\Functional\JsonApiRequestTestTrait;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;

/**
 * Tests that CORS headers are properly added.
 *
 * @group farm
 */
class CorsResponseEventSubscriberTest extends FarmBrowserTestBase {

  use JsonApiRequestTestTrait;

  /**
   * Test consumer entity.
   *
   * @var \Drupal\consumers\Entity\Consumer
   */
  protected $consumer;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'farm_api',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    // Create a consumer for testing.
    $this->consumer = Consumer::create([
      'label' => $this->getRandomGenerator()->name(),
    ]);
    $this->consumer->save();
  }

  /**
   * Test CORS response headers are correctly added.
   */
  public function testCorsResponseHeaders() {

    // A request with no Origin should not have CORS headers on the response.
    $request_options[RequestOptions::HEADERS]['Accept'] = 'application/vnd.api+json';
    $uri = "base://api";
    $response = $this->request('OPTIONS', Url::fromUri($uri), $request_options);
    $this->assertSame(200, $response->getStatusCode());
    $this->assertValidCorsHeaders($response);

    // Try an invalid origin.
    $farmos_app_origin = 'https://farmOS.app';
    $request_options[RequestOptions::HEADERS]['Origin'] = $farmos_app_origin;
    $response = $this->request('OPTIONS', Url::fromUri($uri), $request_options);
    $this->assertSame(200, $response->getStatusCode());
    $this->assertValidCorsHeaders($response);

    // Configure an allowed origin on the consumer.
    $this->consumer->set('allowed_origins', [$farmos_app_origin]);
    $this->consumer->save();

    // Make a request with the allowed origin configured.
    $response = $this->request('OPTIONS', Url::fromUri($uri), $request_options);
    $this->assertSame(200, $response->getStatusCode());
    $this->assertValidCorsHeaders($response, $farmos_app_origin);

    // Add another allowed_origin and test that multiple allowed origins work.
    $custom_app_origin = 'https://customApp.com';
    $this->consumer->set('allowed_origins', [$farmos_app_origin, $custom_app_origin]);
    $this->consumer->save();

    // Make a request from the first allowed origin.
    $request_options[RequestOptions::HEADERS]['Origin'] = $farmos_app_origin;
    $response = $this->request('OPTIONS', Url::fromUri($uri), $request_options);
    $this->assertSame(200, $response->getStatusCode());
    $this->assertValidCorsHeaders($response, $farmos_app_origin);

    // Make a request from the second allowed origin.
    $request_options[RequestOptions::HEADERS]['Origin'] = $custom_app_origin;
    $response = $this->request('OPTIONS', Url::fromUri($uri), $request_options);
    $this->assertSame(200, $response->getStatusCode());
    $this->assertValidCorsHeaders($response, $custom_app_origin);
  }

  /**
   * Helper method to test valid CORS headers.
   *
   * @param \Psr\Http\Message\ResponseInterface $response
   *   The response to check.
   * @param string|null $origin
   *   An optional origin to check. If NULL, then the request should have no
   *   CORS headers.
   */
  protected function assertValidCorsHeaders(ResponseInterface $response, string $origin = NULL) {

    // Cors headers to test.
    $cors_headers = [
      'Access-Control-Allow-Origin' => $origin,
      'Access-Control-Allow-Credentials' => 'true',
      'Access-Control-Allow-Headers' => 'Content-Type,Content-Disposition,Authorization,X-CSRF-Token',
      'Access-Control-Allow-Methods' => 'GET,POST,PUT,DELETE,HEAD,OPTIONS',
    ];

    // Check if the response should contain headers.
    $needs_cors = !empty($origin);
    foreach ($cors_headers as $header => $value) {
      $this->assertEquals($needs_cors, $response->hasHeader($header), 'Response has correct CORS headers.');
      if ($needs_cors) {
        $this->assertEquals($value, $response->getHeader($header)[0], 'Response has correct header value.');
      }
    }

    // Confirm that the "Vary" header contains "Origin" when CORS is in use.
    if ($needs_cors) {
      $this->assertTrue(str_contains($response->getHeader('Vary')[0], 'Origin'), 'Response Vary header contains Origin.');
    }
  }

}
