<?php

namespace Drupal\Tests\farm_entity\Functional;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\farm_location\Traits\WktTrait;
use Drupal\Tests\farm\Functional\FarmBrowserTestBase;

/**
 * Tests for farmOS location logic.
 *
 * @group farm
 */
class LocationTest extends FarmBrowserTestBase {

  use StringTranslationTrait;
  use WktTrait;

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
      'movement' => TRUE,
    ]);
    $this->log->save();
  }

  /**
   * Test computed asset location.
   */
  public function testComputedAssetLocation() {

    // The computed location of the asset is the location asset.
    $current_location = $this->asset->get('current_location')->referencedEntities();
    $this->assertEquals($this->location->id(), $current_location[0]->id(), 'Computed asset location is the location asset.');

    // The computed geometry of the asset is the location asset geometry.
    $this->assertEquals($this->location->get('intrinsic_geometry')->value, $this->asset->get('current_geometry')->value, 'Computed asset geometry is the location asset geometry.');
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
    $this->assertSession()->fieldNotExists('current_geometry[0][value]');
    $this->assertSession()->fieldNotExists('current_location[0][target_id]');

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

}
