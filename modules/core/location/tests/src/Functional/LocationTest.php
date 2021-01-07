<?php

namespace Drupal\Tests\farm_entity\Functional;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Url;
use Drupal\farm_location\Traits\WktTrait;
use Drupal\Tests\farm\Functional\FarmBrowserTestBase;
use Drupal\Tests\jsonapi\Functional\JsonApiRequestTestTrait;
use GuzzleHttp\RequestOptions;

/**
 * Tests for farmOS location logic.
 *
 * @group farm
 */
class LocationTest extends FarmBrowserTestBase {

  use StringTranslationTrait;
  use WktTrait;
  use JsonApiRequestTestTrait;

  /**
   * Test user.
   *
   * @var \Drupal\user\Entity\User
   */
  protected $user;

  /**
   * Test location asset.
   *
   * @var \Drupal\asset\Entity\AssetInterface
   */
  protected $location;

  /**
   * Test asset.
   *
   * @var \Drupal\asset\Entity\AssetInterface
   */
  protected $asset;

  /**
   * Test movement log.
   *
   * @var \Drupal\log\Entity\LogInterface
   */
  protected $log;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'farm_location',
    'farm_location_test',
    'farm_api',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    // Load asset and log storage.
    $entity_type_manager = $this->container->get('entity_type.manager');
    $asset_storage = $entity_type_manager->getStorage('asset');
    $log_storage = $entity_type_manager->getStorage('log');

    // Create and login a user with permission to administer assets and logs.
    $this->user = $this->createUser(['administer assets', 'administer log']);
    $this->drupalLogin($this->user);

    // Generate a location with random WKT polygon.
    $this->location = $asset_storage->create([
      'type' => 'location',
      'name' => $this->randomMachineName(),
      'intrinsic_geometry' => $this->reduceWkt(\Drupal::service('geofield.wkt_generator')->wktGeneratePolygon(NULL, 5)),
      'is_fixed' => TRUE,
    ]);
    $this->location->save();

    // Create a new asset.
    $this->asset = $asset_storage->create([
      'type' => 'object',
      'name' => $this->randomMachineName(),
    ]);
    $this->asset->save();

    // Create a "done" movement log that references the asset.
    $this->log = $log_storage->create([
      'type' => 'movement',
      'status' => 'done',
      'asset' => ['target_id' => $this->asset->id()],
      'location' => ['target_id' => $this->location->id()],
      'is_movement' => TRUE,
    ]);
    $this->log->save();
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
    $this->assertSession()->statusCodeEquals(200);

    // Test that intrinsic geometry, current geometry, and current location
    // fields are all hidden.
    $this->assertSession()->fieldNotExists('intrinsic_geometry[0][value]');
    $this->assertSession()->fieldNotExists('geometry[0][value]');
    $this->assertSession()->fieldNotExists('location[0][target_id]');

    // Go to the asset view page.
    $this->drupalGet('asset/' . $this->asset->id());
    $this->assertSession()->statusCodeEquals(200);

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
    $this->assertSession()->statusCodeEquals(200);

    // Test that the intrinsic geometry field is visible.
    $this->assertSession()->fieldExists('intrinsic_geometry[0][value]');
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
