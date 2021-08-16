<?php

namespace Drupal\Tests\farm_location\Functional;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Url;
use Drupal\farm_location\Traits\WktTrait;
use Drupal\FunctionalJavascriptTests\WebDriverTestBase;
use Drupal\Tests\jsonapi\Functional\JsonApiRequestTestTrait;
use GuzzleHttp\RequestOptions;

/**
 * Tests for farmOS location logic.
 *
 * @group farm
 */
class LocationTest extends WebDriverTestBase {

  use StringTranslationTrait;
  use WktTrait;
  use JsonApiRequestTestTrait;
  use LocationFunctionalTestTrait {
    setUp as locationSetup;
  }

  /**
   * {@inheritdoc}
   */
  protected $profile = 'farm';

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
   * {@inheritdoc}
   */
  protected function setUp(): void {
    $GLOBALS['farm_test'] = TRUE;
    $this->locationSetup();
  }

  /**
   * Test computed asset location.
   */
  public function testComputedAssetLocation() {

    // The computed location of the asset is the location asset.
    $location = $this->asset->get('location')->referencedEntities();
    $this->assertEquals($this->location->id(), $location[0]->id(), 'Computed asset location is the location asset.');

    // The computed geometry of the asset is the location asset geometry.
    $this->assertEquals($this->location->get('intrinsic_geometry')->value, $this->asset->get('geometry')->value, 'Computed asset geometry is the location asset geometry.');
  }

  /**
   * Test geometry and location field visibility.
   */
  public function testLocationFieldVisibility() {

    // Go to the asset edit form.
    $this->drupalGet('asset/' . $this->asset->id() . '/edit');

    // Test that current geometry and current location fields are all hidden.
    $this->assertSession()->fieldNotExists('geometry[0][value]');
    $this->assertSession()->fieldNotExists('location[0][target_id]');

    // Test that intrinsic_geometry field is hidden.
    $page = $this->getSession()->getPage();
    $intrinsic_geometry_field = $page->findById('edit-intrinsic-geometry-wrapper');
    $this->assertNotEmpty($intrinsic_geometry_field);
    $this->assertFalse($intrinsic_geometry_field->isVisible());

    // Go to the asset view page.
    $this->drupalGet('asset/' . $this->asset->id());

    // Test that current geometry and location fields are visible.
    $this->assertSession()->pageTextContains("Current geometry");
    $this->assertSession()->pageTextContains("Current location");

    // Test that the intrinsic geometry field is hidden.
    $this->assertSession()->pageTextNotContains("Intrinsic geometry");

    // Make the asset fixed.
    $this->asset->is_fixed = TRUE;
    $this->asset->save();

    // Go back to the edit form.
    $this->drupalGet('asset/' . $this->asset->id() . '/edit');

    // Test that the intrinsic geometry field is visible.
    $page = $this->getSession()->getPage();
    $intrinsic_geometry_field = $page->findById('edit-intrinsic-geometry-wrapper');
    $this->assertNotEmpty($intrinsic_geometry_field);
    $this->assertTrue($intrinsic_geometry_field->isVisible());
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
