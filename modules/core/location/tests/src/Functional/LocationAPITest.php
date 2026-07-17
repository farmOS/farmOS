<?php

declare(strict_types=1);

namespace Drupal\Tests\farm_location\Functional;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Url;
use Drupal\Tests\farm_test\Functional\FarmBrowserTestBase;
use Drupal\Tests\jsonapi\Functional\JsonApiRequestTestTrait;
use Drupal\farm_geo\Traits\WktTrait;
use GuzzleHttp\RequestOptions;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * Tests for the location api.
 */
#[Group('farm')]
#[RunTestsInSeparateProcesses]
class LocationAPITest extends FarmBrowserTestBase {

  use WktTrait;
  use JsonApiRequestTestTrait;
  use LocationFunctionalTestTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'basic_auth',
    'farm_location',
    'farm_location_test',
    'farm_api',
  ];

  /**
   * Test creating an asset via the API.
   *
   * Ensure that the geometry and location fields are not populated.
   */
  public function testApiAssetPostResponse() {

    // Logout the user so we don't need a CSRF token. Use basic auth.
    $this->drupalLogout();

    // Setup the request.
    $request_options[RequestOptions::HEADERS]['Accept'] = 'application/vnd.api+json';
    $request_options[RequestOptions::HEADERS]['Content-Type'] = 'application/vnd.api+json';
    // PHPStan throws the following errors on the next lines:
    // Cannot access property $name on bool|Drupal\user\UserInterface.
    // Cannot access property $passRaw on bool|Drupal\user\UserInterface.
    // We ignore this because we are following Drupal core's pattern.
    // @phpstan-ignore property.nonObject
    $user_name = $this->user->name->value;
    // @phpstan-ignore property.nonObject
    $user_pass = $this->user->passRaw;
    $request_options[RequestOptions::HEADERS]['Authorization'] = 'Basic ' . base64_encode($user_name . ':' . $user_pass);
    $asset_uri = "base://api/asset/object";

    // Create an asset with no intrinsic geometry.
    $post_data = [
      'data' => [
        'type' => 'asset--object',
        'attributes' => [
          'name' => 'Test object',
        ],
      ],
    ];
    $request_options[RequestOptions::BODY] = Json::encode($post_data);
    $response = $this->request('POST', Url::fromUri($asset_uri), $request_options);

    // Assert a valid response.
    $this->assertEquals(201, $response->getStatusCode());
    $response_data = Json::decode((string) $response->getBody());
    $this->assertArrayhasKey('data', $response_data);

    // Assert that there is no geometry.
    $this->assertArrayHasKey('attributes', $response_data['data']);
    $this->assertArrayHasKey('geometry', $response_data['data']['attributes']);
    $this->assertEmpty($response_data['data']['attributes']['geometry']);

    // Assert that there is no location.
    $this->assertArrayHasKey('relationships', $response_data['data']);
    $this->assertArrayHasKey('location', $response_data['data']['relationships']);
    $this->assertEmpty($response_data['data']['relationships']['location']['data']);

    // Create a fixed asset with intrinsic geometry.
    $post_data['data']['attributes']['is_fixed'] = TRUE;
    $post_data['data']['attributes']['intrinsic_geometry'] = $this->reduceWkt(\Drupal::service('geofield.wkt_generator')->wktGeneratePolygon(NULL, 5));
    $request_options[RequestOptions::BODY] = Json::encode($post_data);
    $response = $this->request('POST', Url::fromUri($asset_uri), $request_options);

    // Assert a valid response.
    $this->assertEquals(201, $response->getStatusCode());
    $response_data = Json::decode((string) $response->getBody());
    $this->assertArrayhasKey('data', $response_data);

    // Assert that the intrinsic_geometry was used.
    $this->assertArrayHasKey('attributes', $response_data['data']);
    $this->assertArrayHasKey('geometry', $response_data['data']['attributes']);
    $this->assertArrayHasKey('value', $response_data['data']['attributes']['geometry']);
    $this->assertEquals($response_data['data']['attributes']['geometry']['value'], $post_data['data']['attributes']['intrinsic_geometry']);

    // Assert that there is no location.
    $this->assertArrayHasKey('relationships', $response_data['data']);
    $this->assertArrayHasKey('location', $response_data['data']['relationships']);
    $this->assertEmpty($response_data['data']['relationships']['location']['data']);
  }

}
