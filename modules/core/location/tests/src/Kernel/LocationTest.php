<?php

namespace Drupal\Tests\farm_location\Kernel;

use Drupal\asset\Entity\Asset;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\farm_geo\Traits\WktTrait;
use Drupal\KernelTests\KernelTestBase;
use Drupal\log\Entity\Log;
use Drupal\Tests\farm_test\Kernel\FarmAssetTestTrait;
use Drupal\Tests\farm_test\Kernel\FarmEntityCacheTestTrait;

/**
 * Tests for farmOS location logic.
 *
 * @group farm
 */
class LocationTest extends KernelTestBase {

  use StringTranslationTrait;
  use FarmEntityCacheTestTrait;
  use FarmAssetTestTrait;
  use WktTrait;

  /**
   * WKT Generator service.
   *
   * @var \Drupal\geofield\WktGeneratorInterface
   */
  protected $wktGenerator;

  /**
   * Asset location service.
   *
   * @var \Drupal\farm_location\AssetLocationInterface
   */
  protected $assetLocation;

  /**
   * Log location service.
   *
   * @var \Drupal\farm_location\LogLocationInterface
   */
  protected $logLocation;

  /**
   * Array of polygon WKT strings.
   *
   * @var string[]
   */
  protected $polygons = [];

  /**
   * Array of location assets.
   *
   * @var \Drupal\asset\Entity\AssetInterface[]
   */
  protected $locations = [];

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'asset',
    'geofield',
    'log',
    'farm_entity_views',
    'farm_field',
    'farm_location',
    'farm_location_test',
    'farm_log',
    'farm_log_asset',
    'rest',
    'serialization',
    'state_machine',
    'system',
    'user',
    'views',
    'views_geojson',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->wktGenerator = \Drupal::service('geofield.wkt_generator');
    $this->assetLocation = \Drupal::service('asset.location');
    $this->logLocation = \Drupal::service('log.location');
    $this->installEntitySchema('asset');
    $this->installEntitySchema('log');
    $this->installEntitySchema('user');
    $this->installConfig([
      'farm_entity_views',
      'farm_location',
      'farm_location_test',
    ]);

    // Generate random WKT polygons.
    for ($i = 0; $i < 3; $i++) {
      $segments = rand(3, 7);
      $this->polygons[] = $this->reduceWkt($this->wktGenerator->wktGeneratePolygon(NULL, $segments));
    }

    // Generate location assets.
    for ($i = 0; $i < 3; $i++) {
      $location = Asset::create([
        'type' => 'location',
        'name' => $this->randomMachineName(),
        'status' => 'active',
        'intrinsic_geometry' => $this->polygons[$i],
        'is_fixed' => TRUE,
        'is_location' => TRUE,
      ]);
      $location->save();
      $this->locations[] = $location;
    }
  }

  /**
   * Test auto population of log geometry field.
   */
  public function testPopulateLogGeometry() {

    // When a log is saved with a location and without a geometry, the geometry
    // is copied from the location.
    /** @var \Drupal\log\Entity\LogInterface $log */
    $log = Log::create([
      'type' => 'activity',
      'status' => 'pending',
      'location' => ['target_id' => $this->locations[0]->id()],
    ]);
    $log->save();
    $this->assertEquals($this->locations[0]->get('intrinsic_geometry')->value, $log->get('geometry')->value, 'Empty geometry is populated from location.');

    // When multiple locations are added, all of their geometries are combined.
    $log->location = [
      ['target_id' => $this->locations[0]->id()],
      ['target_id' => $this->locations[1]->id()],
    ];
    $log->geometry->value = '';
    $log->save();
    $combined = $this->combineWkt([$this->polygons[0], $this->polygons[1]]);
    $this->assertEquals($combined, $log->get('geometry')->value, 'Geometries from multiple locations are combined.');

    // When a log's locations change, and the geometry is not customized, the
    // geometry is updated.
    $log->location = ['target_id' => $this->locations[1]->id()];
    $log->save();
    $this->assertEquals($this->locations[1]->get('intrinsic_geometry')->value, $log->get('geometry')->value, 'Geometry is updated when locations are changed.');

    // When a log's geometry is cleared, it is re-copied from locations.
    $log->geometry->value = '';
    $log->save();
    $this->assertEquals($this->locations[1]->get('intrinsic_geometry')->value, $log->get('geometry')->value, 'Geometry is re-copied from locations when it is cleared.');

    // When a log's geometry is set, it is saved.
    $log->geometry->value = $this->polygons[2];
    $log->save();
    $this->assertEquals($this->polygons[2], $log->get('geometry')->value, 'Custom geometry can be saved.');

    // When a log's locations change, and the geometry is customized, the
    // geometry is not updated.
    $log->location = ['target_id' => $this->locations[0]->id()];
    $log->save();
    $this->assertEquals($this->polygons[2], $log->get('geometry')->value, 'Custom geometry is not overwritten when locations change.');

    // When a log's custom geometry is cleared, it is re-copied from locations.
    $log->geometry->value = '';
    $log->save();
    $this->assertEquals($this->locations[0]->get('intrinsic_geometry')->value, $log->get('geometry')->value, 'Geometry is re-copied from locations when custom geometry is cleared.');

    // When a log references a location asset in the asset field, but nothing in
    // the location or geometry fields, the geometry is copied from the asset.
    /** @var \Drupal\log\Entity\LogInterface $log */
    $log = Log::create([
      'type' => 'activity',
      'status' => 'pending',
      'asset' => ['target_id' => $this->locations[0]->id()],
    ]);
    $log->save();
    $this->assertEquals($this->locations[0]->get('intrinsic_geometry')->value, $log->get('geometry')->value, 'Geometry is copied from asset reference field if no location assets or custom geometry provided.');

    // If a non-location asset is referenced, its geometry is not copied.
    $object_geom = $this->reduceWkt($this->wktGenerator->wktGeneratePolygon(NULL, 5));
    $object = Asset::create([
      'type' => 'object',
      'name' => $this->randomMachineName(),
      'status' => 'active',
      'intrinsic_geometry' => $object_geom,
      'is_fixed' => TRUE,
      'is_location' => FALSE,
    ]);
    $object->save();
    $log->asset = ['target_id' => $object->id()];
    $log->location = [];
    $log->geometry->value = '';
    $log->save();
    $this->assertEmpty($log->get('geometry')->value, 'Non-location asset geometry is not copied.');

    // If both location and non-location assets are referenced, only the
    // location asset geometry is copied.
    $log->asset = [
      ['target_id' => $this->locations[0]->id()],
      ['target_id' => $object->id()],
    ];
    $log->location = [];
    $log->geometry->value = '';
    $log->save();
    $this->assertEquals($this->locations[0]->get('intrinsic_geometry')->value, $log->get('geometry')->value, 'Only location asset geometry is copied.');

    // If location assets are referenced in both the asset and location field,
    // only the geometry of the asset referenced in the location field is
    // copied.
    $log->asset = ['target_id' => $this->locations[0]->id()];
    $log->location = ['target_id' => $this->locations[1]->id()];
    $log->geometry->value = '';
    $log->save();
    $this->assertEquals($this->locations[1]->get('intrinsic_geometry')->value, $log->get('geometry')->value, 'Only location asset geometry is copied when both assets and locations are referenced.');

    // If the log is a movement, and only references a location asset in the
    // asset reference field, the geometry is not copied.
    /** @var \Drupal\log\Entity\LogInterface $movement_log */
    $movement_log = Log::create([
      'type' => 'movement',
      'status' => 'pending',
      'asset' => ['target_id' => $this->locations[0]->id()],
      'location' => [],
      'is_movement' => TRUE,
    ]);
    $movement_log->save();
    $this->assertEmpty($movement_log->get('geometry')->value, 'Movement logs do not copy geometry from locations referenced in the asset field.');
  }

  /**
   * Test asset location.
   */
  public function testAssetLocation() {

    // Create a new asset.
    /** @var \Drupal\asset\Entity\AssetInterface $asset */
    $asset = Asset::create([
      'type' => 'object',
      'name' => $this->randomMachineName(),
      'status' => 'active',
    ]);
    $asset->save();

    // Populate a cache value dependent on the asset's cache tags.
    $this->populateEntityTestCache($asset);

    // When an asset has no movement logs, it has no location or geometry.
    $this->assertFalse($this->assetLocation->hasLocation($asset), 'New assets do not have location.');
    $this->assertFalse($this->assetLocation->hasGeometry($asset), 'New assets do not have geometry.');

    // Assert that the asset's cache tags were not invalidated.
    $this->assertEntityTestCache($asset, TRUE);

    // Create a "done" movement log that references the asset.
    /** @var \Drupal\log\Entity\LogInterface $first_log */
    $first_log = Log::create([
      'type' => 'movement',
      'status' => 'done',
      'asset' => ['target_id' => $asset->id()],
      'location' => ['target_id' => $this->locations[0]->id()],
      'is_movement' => TRUE,
    ]);
    $first_log->save();

    // When a movement log is created and marked as "done", the asset has
    // the same location and geometry as the log.
    $this->assertTrue($this->assetLocation->hasLocation($asset), 'Asset with movement log has location.');
    $this->assertTrue($this->assetLocation->hasGeometry($asset), 'Asset with movement log has geometry.');
    $this->assertEquals($this->logLocation->getLocation($first_log), $this->assetLocation->getLocation($asset), 'Asset with movement log has same location as log.');
    $this->assertEquals($this->logLocation->getGeometry($first_log), $this->assetLocation->getGeometry($asset), 'Asset with movement log has same geometry as log.');

    // Assert that the asset's cache tags were invalidated.
    $this->assertEntityTestCache($asset, FALSE);

    // Re-populate a cache value dependent on the asset's cache tags.
    $this->populateEntityTestCache($asset);

    // When a movement log's locations are changed, the asset location changes.
    $first_log->location = ['target_id' => $this->locations[1]->id()];
    $first_log->save();
    $this->assertEquals($this->logLocation->getLocation($first_log), $this->assetLocation->getLocation($asset), 'Asset with changed movement log has same location as log.');
    $this->assertEquals($this->logLocation->getGeometry($first_log), $this->assetLocation->getGeometry($asset), 'Asset with changed movement log has same geometry as log.');

    // Assert that the asset's cache tags were invalidated.
    $this->assertEntityTestCache($asset, FALSE);

    // Re-populate a cache value dependent on the asset's cache tags.
    $this->populateEntityTestCache($asset);

    // Create a "pending" movement log that references the asset.
    /** @var \Drupal\log\Entity\LogInterface $second_log */
    $second_log = Log::create([
      'type' => 'movement',
      'status' => 'pending',
      'asset' => ['target_id' => $asset->id()],
      'location' => ['target_id' => $this->locations[2]->id()],
      'is_movement' => TRUE,
    ]);
    $second_log->save();

    // When an asset has a "pending" movement log, the asset location and
    // geometry remain the same as the previous "done" movement log.
    $this->assertEquals($this->logLocation->getLocation($first_log), $this->assetLocation->getLocation($asset), 'Asset with pending movement log has original location');
    $this->assertEquals($this->logLocation->getGeometry($first_log), $this->assetLocation->getGeometry($asset), 'Asset with pending movement log has original geometry.');

    // Assert that the asset's cache tags were not invalidated.
    $this->assertEntityTestCache($asset, TRUE);

    // When the log is marked as "done", the asset location is updated.
    $second_log->status = 'done';
    $second_log->save();
    $this->assertEquals($this->logLocation->getLocation($second_log), $this->assetLocation->getLocation($asset), 'Asset with second movement log has new location');
    $this->assertEquals($this->logLocation->getGeometry($second_log), $this->assetLocation->getGeometry($asset), 'Asset with second movement log has new geometry.');

    // Assert that the asset's cache tags were invalidated.
    $this->assertEntityTestCache($asset, FALSE);

    // Re-populate a cache value dependent on the asset's cache tags.
    $this->populateEntityTestCache($asset);

    // Create a third "done" movement log in the future.
    /** @var \Drupal\log\Entity\LogInterface $third_log */
    $third_log = Log::create([
      'type' => 'movement',
      'timestamp' => \Drupal::time()->getRequestTime() + 86400,
      'status' => 'done',
      'asset' => ['target_id' => $asset->id()],
      'location' => ['target_id' => $this->locations[0]->id()],
      'is_movement' => TRUE,
    ]);
    $third_log->save();

    // When an asset has a "done" movement log in the future, the asset
    // location and geometry remain the same as the previous "done" movement
    // log.
    $this->assertEquals($this->logLocation->getLocation($second_log), $this->assetLocation->getLocation($asset), 'Asset with future movement log has current location');
    $this->assertEquals($this->logLocation->getGeometry($second_log), $this->assetLocation->getGeometry($asset), 'Asset with future movement log has current geometry.');

    // Assert that the asset's cache tags were not invalidated.
    $this->assertEntityTestCache($asset, TRUE);

    // Create a fourth "done" movement log without location.
    /** @var \Drupal\log\Entity\LogInterface $fourth_log */
    $fourth_log = Log::create([
      'type' => 'movement',
      'status' => 'done',
      'asset' => ['target_id' => $asset->id()],
      'is_movement' => TRUE,
    ]);
    $fourth_log->save();

    // When a movement log is created with no location/geometry, it effectively
    // "unsets" the asset's location/geometry.
    $this->assertFalse($this->assetLocation->hasLocation($asset), 'Asset location can be unset.');
    $this->assertFalse($this->assetLocation->hasGeometry($asset), 'Asset geometry can be unset.');

    // Assert that the asset's cache tags were invalidated.
    $this->assertEntityTestCache($asset, FALSE);

    // Re-populate a cache value dependent on the asset's cache tags.
    $this->populateEntityTestCache($asset);

    // Delete the fourth log.
    $fourth_log->delete();

    // When a movement log is deleted, the previous location is used.
    $this->assertEquals($this->logLocation->getLocation($second_log), $this->assetLocation->getLocation($asset), 'When a movement log is deleted, the previous location is used.');
    $this->assertEquals($this->logLocation->getGeometry($second_log), $this->assetLocation->getGeometry($asset), 'When a movement log is deleted, the previous locations geometry is used. .');

    // Assert that the asset's cache tags were invalidated.
    $this->assertEntityTestCache($asset, FALSE);
  }

  /**
   * Test fixed asset location.
   */
  public function testFixedAssetLocation() {

    // Create a new "fixed" asset.
    /** @var \Drupal\asset\Entity\AssetInterface $asset */
    $asset = Asset::create([
      'type' => 'object',
      'name' => $this->randomMachineName(),
      'status' => 'active',
      'is_fixed' => TRUE,
    ]);
    $asset->save();

    // When a new asset is saved, it does not have a geometry.
    $this->assertFalse($this->assetLocation->hasGeometry($asset), 'New assets do not have geometry.');

    // When an asset is fixed, and has intrinsic geometry, it is the asset's
    // geometry.
    $this->assetLocation->setIntrinsicGeometry($asset, $this->polygons[0]);
    $asset->save();
    $this->assertTrue($this->assetLocation->hasGeometry($asset), 'Assets with intrinsic geometry have geometry.');
    $this->assertEquals($this->polygons[0], $this->assetLocation->getGeometry($asset), 'Asset intrinsic geometry is asset geometry.');

    // Create a "done" movement log that references the asset.
    /** @var \Drupal\log\Entity\LogInterface $log */
    $log = Log::create([
      'type' => 'movement',
      'status' => 'done',
      'asset' => ['target_id' => $asset->id()],
      'location' => ['target_id' => $this->locations[0]->id()],
      'is_movement' => TRUE,
    ]);
    $log->save();

    // Movement logs of a fixed asset do not affect that asset's location or
    // geometry.
    $this->assertEquals([], $this->assetLocation->getLocation($asset), 'Movement logs of a fixed asset do not affect location.');
    $this->assertEquals($this->polygons[0], $this->assetLocation->getGeometry($asset), 'Movement logs of a fixed asset do not affect geometry.');

    // Set is_fixed to FALSE on the asset.
    $asset->is_fixed = FALSE;
    $asset->save();

    // If an asset has a movement log and is no longer fixed, it's location and
    // geometry equal location and geometry of the log.
    $this->assertEquals($this->logLocation->getLocation($log), $this->assetLocation->getLocation($asset), 'Movement logs of a not fixed asset do affect location.');
    $this->assertEquals($this->logLocation->getGeometry($log), $this->assetLocation->getGeometry($asset), 'Movement logs of a not fixed asset do affect geometry.');
  }

  /**
   * Test assets in location.
   */
  public function testLocationAssets() {

    // Locations have no assets.
    $this->assertEmpty($this->assetLocation->getAssetsByLocation([$this->locations[0], $this->locations[1]]));

    // Create an asset and move it to the first location.
    /** @var \Drupal\asset\Entity\AssetInterface $first_asset */
    $first_asset = Asset::create([
      'type' => 'object',
      'name' => $this->randomMachineName(),
      'status' => 'active',
    ]);
    $first_asset->save();
    /** @var \Drupal\log\Entity\LogInterface $first_log */
    $first_log = Log::create([
      'type' => 'movement',
      'status' => 'done',
      'asset' => ['target_id' => $first_asset->id()],
      'location' => ['target_id' => $this->locations[0]->id()],
      'is_movement' => TRUE,
    ]);
    $first_log->save();

    // First location has one asset, second has none.
    $this->assertCorrectAssets([$first_asset], $this->assetLocation->getAssetsByLocation([$this->locations[0]]), TRUE);
    $this->assertCorrectAssets([], $this->assetLocation->getAssetsByLocation([$this->locations[1]]), TRUE);
    $this->assertCorrectAssets([$first_asset], $this->assetLocation->getAssetsByLocation([$this->locations[0], $this->locations[1]]), TRUE);

    // Create a second asset and move it to the second location.
    /** @var \Drupal\asset\Entity\AssetInterface $second_asset */
    $second_asset = Asset::create([
      'type' => 'object',
      'name' => $this->randomMachineName(),
      'status' => 'active',
    ]);
    $second_asset->save();
    /** @var \Drupal\log\Entity\LogInterface $first_log */
    $second_log = Log::create([
      'type' => 'movement',
      'status' => 'done',
      'asset' => ['target_id' => $second_asset->id()],
      'location' => ['target_id' => $this->locations[1]->id()],
      'is_movement' => TRUE,
    ]);
    $second_log->save();

    // Both locations have one asset.
    $this->assertCorrectAssets([$first_asset], $this->assetLocation->getAssetsByLocation([$this->locations[0]]), TRUE);
    $this->assertCorrectAssets([$second_asset], $this->assetLocation->getAssetsByLocation([$this->locations[1]]), TRUE);
    $this->assertCorrectAssets([$first_asset, $second_asset], $this->assetLocation->getAssetsByLocation([$this->locations[0], $this->locations[1]]), TRUE);

    // Create a third log that moves both assets to the first location.
    /** @var \Drupal\log\Entity\LogInterface $third_log */
    $third_log = Log::create([
      'type' => 'movement',
      'status' => 'done',
      'asset' => [
        ['target_id' => $first_asset->id()],
        ['target_id' => $second_asset->id()],
      ],
      'location' => ['target_id' => $this->locations[0]->id()],
      'is_movement' => TRUE,
    ]);
    $third_log->save();

    // First location has two assets, second has none.
    $this->assertCorrectAssets([$first_asset, $second_asset], $this->assetLocation->getAssetsByLocation([$this->locations[0]]), TRUE);
    $this->assertCorrectAssets([], $this->assetLocation->getAssetsByLocation([$this->locations[1]]), TRUE);
    $this->assertCorrectAssets([$first_asset, $second_asset], $this->assetLocation->getAssetsByLocation([$this->locations[0], $this->locations[1]]), TRUE);

    // Create a fourth log that moves first asset to the second location.
    /** @var \Drupal\log\Entity\LogInterface $fourth_log */
    $fourth_log = Log::create([
      'type' => 'movement',
      'status' => 'done',
      'asset' => [
        ['target_id' => $first_asset->id()],
      ],
      'location' => ['target_id' => $this->locations[1]->id()],
      'is_movement' => TRUE,
    ]);
    $fourth_log->save();

    // Both locations have one asset.
    $this->assertCorrectAssets([$second_asset], $this->assetLocation->getAssetsByLocation([$this->locations[0]]), TRUE);
    $this->assertCorrectAssets([$first_asset], $this->assetLocation->getAssetsByLocation([$this->locations[1]]), TRUE);
    $this->assertCorrectAssets([$first_asset, $second_asset], $this->assetLocation->getAssetsByLocation([$this->locations[0], $this->locations[1]]), TRUE);

    // Create a fifth log that moves first asset to the both locations.
    /** @var \Drupal\log\Entity\LogInterface $fifth_log */
    $fifth_log = Log::create([
      'type' => 'movement',
      'status' => 'done',
      'asset' => [
        ['target_id' => $first_asset->id()],
      ],
      'location' => [
        ['target_id' => $this->locations[0]->id()],
        ['target_id' => $this->locations[1]->id()],
      ],
      'is_movement' => TRUE,
    ]);
    $fifth_log->save();

    // First location has two asset, second location has one asset.
    $this->assertCorrectAssets([$first_asset, $second_asset], $this->assetLocation->getAssetsByLocation([$this->locations[0]]), TRUE);
    $this->assertCorrectAssets([$first_asset], $this->assetLocation->getAssetsByLocation([$this->locations[1]]), TRUE);
    $this->assertCorrectAssets([$first_asset, $second_asset], $this->assetLocation->getAssetsByLocation([$this->locations[0], $this->locations[1]]), TRUE);
  }

  /**
   * Test past/future asset location.
   */
  public function testAssetLocationTimestamp() {

    // Create a new asset.
    /** @var \Drupal\asset\Entity\AssetInterface $asset */
    $asset = Asset::create([
      'type' => 'object',
      'name' => $this->randomMachineName(),
      'status' => 'active',
    ]);
    $asset->save();

    // Create a series of timestamps, each 24 hours apart.
    $now = \Drupal::time()->getRequestTime();
    $timestamps = [];
    for ($i = 0; $i < 4; $i++) {
      $timestamps[$i] = $now + (86400 * $i);
    }

    // Create a series of movement logs, moving the asset into each of the
    // locations, and then one that removes location at the end.
    /** @var \Drupal\log\Entity\LogInterface[] $logs */
    $logs = [];
    $locations = [];
    for ($i = 0; $i < 3; $i++) {
      $locations[] = ['target_id' => $this->locations[$i]->id()];
    }
    $locations[] = [];
    for ($i = 0; $i < 4; $i++) {
      $log = Log::create([
        'type' => 'movement',
        'timestamp' => $timestamps[$i],
        'status' => 'done',
        'asset' => ['target_id' => $asset->id()],
        'location' => $locations[$i],
        'is_movement' => TRUE,
      ]);
      $log->save();
      $logs[] = $log;
    }

    // Confirm that the asset has no location before all logs.
    $timestamp = $now - 86400;
    $this->assertEquals(FALSE, $this->assetLocation->hasLocation($asset, $timestamp));
    $this->assertEquals(FALSE, $this->assetLocation->hasGeometry($asset, $timestamp));
    $this->assertEquals([], $this->assetLocation->getLocation($asset, $timestamp));
    $this->assertEquals('', $this->assetLocation->getGeometry($asset, $timestamp));
    $this->assertNull($this->assetLocation->getMovementLog($asset, $timestamp));
    $this->assertEquals([], $this->assetLocation->getAssetsByLocation($this->locations, $timestamp));

    // Confirm that the asset is where it should be after each log.
    for ($i = 0; $i < 3; $i++) {
      $this->assertEquals(TRUE, $this->assetLocation->hasLocation($asset, $timestamps[$i]));
      $this->assertEquals(TRUE, $this->assetLocation->hasGeometry($asset, $timestamps[$i]));
      $this->assertEquals($this->logLocation->getLocation($logs[$i]), $this->assetLocation->getLocation($asset, $timestamps[$i]));
      $this->assertEquals($this->logLocation->getGeometry($logs[$i]), $this->assetLocation->getGeometry($asset, $timestamps[$i]));
      $this->assertEquals($logs[$i]->id(), $this->assetLocation->getMovementLog($asset, $timestamps[$i])->id());
      $this->assertCorrectAssets([$asset], $this->assetLocation->getAssetsByLocation([$this->locations[$i]], $timestamps[$i]), TRUE);
    }

    // Confirm that the asset is still in the same location 1 second later.
    // This tests the <= operator.
    $this->assertEquals(TRUE, $this->assetLocation->hasLocation($asset, $timestamps[2] + 1));
    $this->assertEquals(TRUE, $this->assetLocation->hasGeometry($asset, $timestamps[2] + 1));
    $this->assertEquals($this->logLocation->getLocation($logs[2]), $this->assetLocation->getLocation($asset, $timestamps[2] + 1));
    $this->assertEquals($this->logLocation->getGeometry($logs[2]), $this->assetLocation->getGeometry($asset, $timestamps[2] + 1));
    $this->assertEquals($logs[2]->id(), $this->assetLocation->getMovementLog($asset, $timestamps[2] + 1)->id());
    $this->assertCorrectAssets([$asset], $this->assetLocation->getAssetsByLocation([$this->locations[2]], $timestamps[2] + 1), TRUE);

    // Confirm that the asset has no location after the last log.
    $this->assertEquals(FALSE, $this->assetLocation->hasLocation($asset, $timestamps[3]));
    $this->assertEquals(FALSE, $this->assetLocation->hasGeometry($asset, $timestamps[3]));
    $this->assertEquals([], $this->assetLocation->getLocation($asset, $timestamps[3]));
    $this->assertEquals('', $this->assetLocation->getGeometry($asset, $timestamps[3]));
    $this->assertEquals($logs[3]->id(), $this->assetLocation->getMovementLog($asset, $timestamps[3])->id());
    $this->assertEquals([], $this->assetLocation->getAssetsByLocation($this->locations, $timestamps[3]));
  }

  /**
   * Test that circular asset location is prevented.
   */
  public function testCircularAssetLocation() {

    // First, make sure we can't create a movement log that references the same
    // asset in both the asset and location fields.
    /** @var \Drupal\log\Entity\LogInterface $first_log */
    $first_log = Log::create([
      'type' => 'movement',
      'asset' => ['target_id' => $this->locations[0]->id()],
      'location' => ['target_id' => $this->locations[0]->id()],
      'is_movement' => TRUE,
      'status' => 'done',
    ]);

    // Validate the log and confirm that a circular location constraint
    // violation was set.
    $violations = $first_log->validate();
    $this->assertCount(1, $violations);
    $this->assertEquals($this->t('%asset cannot be located within itself.', ['%asset' => $this->locations[0]->label()]), $violations[0]->getMessage());

    // Tweak the log so that it moves the first location to the second location.
    $first_log->set('location', ['target_id' => $this->locations[1]->id()]);

    // Confirm that validation passes.
    $violations = $first_log->validate();
    $this->assertCount(0, $violations);

    // Save the first log.
    $first_log->save();

    // Start a log that moves the second location to the first location (which
    // would cause a circular asset location).
    /** @var \Drupal\log\Entity\LogInterface $second_log */
    $second_log = Log::create([
      'type' => 'movement',
      'asset' => ['target_id' => $this->locations[1]->id()],
      'location' => ['target_id' => $this->locations[0]->id()],
      'is_movement' => TRUE,
      'status' => 'done',
    ]);

    // Validate the second log and confirm that a circular location constraint
    // violation was set.
    $violations = $second_log->validate();
    $this->assertCount(1, $violations);
    $this->assertEquals($this->t('%asset cannot be located within itself.', ['%asset' => $this->locations[1]->label()]), $violations[0]->getMessage());

    // Update the second log to reference the third location instead.
    $second_log->set('location', ['target_id' => $this->locations[2]->id()]);

    // Confirm that validation passes.
    $violations = $second_log->validate();
    $this->assertCount(0, $violations);

    // Save the second log.
    $second_log->save();

    // Now, start a third log that moves the third location to the first
    // location (which would create a recursive circular asset location).
    /** @var \Drupal\log\Entity\LogInterface $first_log */
    $third_log = Log::create([
      'type' => 'movement',
      'asset' => ['target_id' => $this->locations[2]->id()],
      'location' => ['target_id' => $this->locations[0]->id()],
      'is_movement' => TRUE,
      'status' => 'done',
    ]);

    // Validate the third log and confirm that a circular location constraint
    // violation was set.
    $violations = $third_log->validate();
    $this->assertCount(1, $violations);
    $this->assertEquals($this->t('%asset cannot be located within itself.', ['%asset' => $this->locations[2]->label()]), $violations[0]->getMessage());
  }

}
