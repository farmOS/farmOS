<?php

namespace Drupal\Tests\farm_location\Functional;

/**
 * Trait for setting up functional location tests.
 */
trait LocationFunctionalTestTrait {

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
  public function setUp(): void {
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

}
