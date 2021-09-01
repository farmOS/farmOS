<?php

namespace Drupal\Tests\farm_location\Functional;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Url;
use Drupal\farm_geo\Traits\WktTrait;
use Drupal\Tests\farm_test\Functional\FarmBrowserTestBase;
use Drupal\Tests\jsonapi\Functional\JsonApiRequestTestTrait;
use GuzzleHttp\RequestOptions;

/**
 * Tests for the location api.
 *
 * @group farm
 */
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
    $request_options[RequestOptions::HEADERS]['Authorization'] = 'Basic ' . base64_encode($this->user->name->value . ':' . $this->user->passRaw);
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

  /**
   * Test retrieving asset geometry and location fields via API.
   */
  public function testApiLocation() {

    $location_uuid = $this->location->uuid();
    $geometry = $this->location->get('intrinsic_geometry')->value;

    $this->assertApiAssetLocationEquals($location_uuid, $geometry);

    // Test that removing the log movement flag removes the asset location.
    $this->log->is_movement = FALSE;
    $this->log->save();
    $this->assertApiAssetLocationEquals(NULL, NULL);

    // Test that setting the log movement flag sets the asset location.
    $this->log->is_movement = TRUE;
    $this->log->save();
    $this->assertApiAssetLocationEquals($location_uuid, $geometry);

    // Test that changing the log status to pending removes the asset location.
    $this->log->status = 'pending';
    $this->log->save();
    $this->assertApiAssetLocationEquals(NULL, NULL);

    // Test that changing the log status to done sets the asset location.
    $this->log->status = 'done';
    $this->log->save();
    $this->assertApiAssetLocationEquals($location_uuid, $geometry);

    // Test that removing the asset from the log removes the asset location.
    $this->log->asset = [];
    $this->log->save();
    $this->assertApiAssetLocationEquals(NULL, NULL);

    // Test that adding the asset to the log sets the asset location.
    $this->log->asset = ['target_id' => $this->asset->id()];
    $this->log->save();
    $this->assertApiAssetLocationEquals($location_uuid, $geometry);
  }

  /**
   * Helper function to test asset location returned by the API.
   *
   * @param string|null $location_uuid
   *   The expected location asset uuid.
   * @param string|null $geometry
   *   The expected geometry.
   */
  protected function assertApiAssetLocationEquals($location_uuid, $geometry) {

    // Fetch the asset from the API.
    $response = $this->requestApiEntity($this->asset);

    // Test that the location field is included in the response.
    $this->assertArrayHasKey('data', $response['data']['relationships']['location']);

    // If no uuid is provided, test that the asset has no location.
    if (empty($location_uuid)) {
      $this->assertEquals(0, count($response['data']['relationships']['location']['data']));
    }

    // Ia a location_uuid is provided, test that the location matches.
    if (!empty($location_uuid)) {
      $this->assertEquals(1, count($response['data']['relationships']['location']['data']));
      $this->assertArrayHasKey('id', $response['data']['relationships']['location']['data'][0]);
      $this->assertEquals($location_uuid, $response['data']['relationships']['location']['data'][0]['id']);
    }

    // Test that the geometry field is included in the response.
    $this->assertArrayHasKey('geometry', $response['data']['attributes']);

    // If no geometry is provided, test that the asset has no geometry.
    if (empty($geometry)) {
      $this->assertEquals(NULL, $response['data']['attributes']['geometry']);
    }

    // Ia a geometry is provided, test that the geometry matches.
    if (!empty($geometry)) {
      $this->assertArrayHasKey('value', $response['data']['attributes']['geometry']);
      $this->assertEquals($geometry, $response['data']['attributes']['geometry']['value']);
    }
  }

  /**
   * Helper function to request an entity from the API.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity to request.
   *
   * @return array
   *   The json-decoded response.
   */
  protected function requestApiEntity(EntityInterface $entity) {
    $request_options[RequestOptions::HEADERS]['Accept'] = 'application/vnd.api+json';
    $asset_uri = "base://api/{$entity->getEntityType()->id()}/{$entity->bundle()}/{$entity->uuid()}";
    $response = $this->request('GET', Url::fromUri($asset_uri), $request_options);
    return Json::decode((string) $response->getBody());
  }

}
