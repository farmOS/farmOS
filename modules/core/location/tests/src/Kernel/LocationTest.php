<?php

namespace Drupal\Tests\farm_location\Kernel;

use Drupal\asset\Entity\Asset;
use Drupal\farm_location\Traits\WktTrait;
use Drupal\KernelTests\KernelTestBase;
use Drupal\log\Entity\Log;

/**
 * Tests for farmOS location logic.
 *
 * @group farm
 */
class LocationTest extends KernelTestBase {

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
    'farm_field',
    'farm_location',
    'farm_location_test',
    'farm_log',
    'state_machine',
    'user',
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
        'geometry' => $this->polygons[$i],
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
      'type' => 'movement',
      'status' => 'pending',
      'location' => ['target_id' => $this->locations[0]->id()],
    ]);
    $log->save();
    $this->assertEquals($this->locations[0]->get('geometry')->value, $log->get('geometry')->value, 'Empty geometry is populated from location.');

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
    $this->assertEquals($this->locations[1]->get('geometry')->value, $log->get('geometry')->value, 'Geometry is updated when locations are changed.');

    // When a log's geometry is set, it is saved.
    $log->geometry->value = $this->polygons[2];
    $log->save();
    $this->assertEquals($this->polygons[2], $log->get('geometry')->value, 'Custom geometry can be saved.');

    // When a log's locations change, and the geometry is customized, the
    // geometry is not updated.
    $log->location = ['target_id' => $this->locations[0]->id()];
    $log->save();
    $this->assertEquals($this->polygons[2], $log->get('geometry')->value, 'Custom geometry is not overwritten when locations change.');
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

    // When an asset has no movement logs, it has no location or geometry.
    $this->assertFalse($this->assetLocation->hasLocation($asset), 'New assets do not have location.');
    $this->assertFalse($this->assetLocation->hasGeometry($asset), 'New assets do not have geometry.');

    // Create a "done" movement log that references the asset.
    /** @var \Drupal\log\Entity\LogInterface $first_log */
    $first_log = Log::create([
      'type' => 'movement',
      'status' => 'done',
      'asset' => ['target_id' => $asset->id()],
      'location' => ['target_id' => $this->locations[0]->id()],
      'movement' => TRUE,
    ]);
    $first_log->save();

    // When a movement log is created and marked as "done", the asset has
    // the same location and geometry as the log.
    $this->assertTrue($this->assetLocation->hasLocation($asset), 'Asset with movement log has location.');
    $this->assertTrue($this->assetLocation->hasGeometry($asset), 'Asset with movement log has geometry.');
    $this->assertEquals($this->logLocation->getLocation($first_log), $this->assetLocation->getLocation($asset), 'Asset with movement log has same location as log.');
    $this->assertEquals($this->logLocation->getGeometry($first_log), $this->assetLocation->getGeometry($asset), 'Asset with movement log has same geometry as log.');

    // When a movement log's locations are changed, the asset location changes.
    $first_log->location = ['target_id' => $this->locations[1]->id()];
    $first_log->save();
    $this->assertEquals($this->logLocation->getLocation($first_log), $this->assetLocation->getLocation($asset), 'Asset with changed movement log has same location as log.');
    $this->assertEquals($this->logLocation->getGeometry($first_log), $this->assetLocation->getGeometry($asset), 'Asset with changed movement log has same geometry as log.');

    // Create a "pending" movement log that references the asset.
    /** @var \Drupal\log\Entity\LogInterface $second_log */
    $second_log = Log::create([
      'type' => 'movement',
      'status' => 'pending',
      'asset' => ['target_id' => $asset->id()],
      'location' => ['target_id' => $this->locations[2]->id()],
      'movement' => TRUE,
    ]);
    $second_log->save();

    // When an asset has a "pending" movement log, the asset location and
    // geometry remain the same as the previous "done" movement log.
    $this->assertEquals($this->logLocation->getLocation($first_log), $this->assetLocation->getLocation($asset), 'Asset with pending movement log has original location');
    $this->assertEquals($this->logLocation->getGeometry($first_log), $this->assetLocation->getGeometry($asset), 'Asset with pending movement log has original geometry.');

    // When the log is marked as "done", the asset location is updated.
    $second_log->status = 'done';
    $second_log->save();
    $this->assertEquals($this->logLocation->getLocation($second_log), $this->assetLocation->getLocation($asset), 'Asset with second movement log has new location');
    $this->assertEquals($this->logLocation->getGeometry($second_log), $this->assetLocation->getGeometry($asset), 'Asset with second movement log has new geometry.');

    // Create a third "done" movement log in the future.
    /** @var \Drupal\log\Entity\LogInterface $third_log */
    $third_log = Log::create([
      'type' => 'movement',
      'timestamp' => \Drupal::time()->getRequestTime() + 86400,
      'status' => 'done',
      'asset' => ['target_id' => $asset->id()],
      'location' => ['target_id' => $this->locations[0]->id()],
      'movement' => TRUE,
    ]);
    $third_log->save();

    // When an asset has a "done" movement log in the future, the asset
    // location and geometry remain the same as the previous "done" movement
    // log.
    $this->assertEquals($this->logLocation->getLocation($second_log), $this->assetLocation->getLocation($asset), 'Asset with future movement log has current location');
    $this->assertEquals($this->logLocation->getGeometry($second_log), $this->assetLocation->getGeometry($asset), 'Asset with future movement log has current geometry.');
  }

}
