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
    'state_machine',
    'user',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->wktGenerator = \Drupal::service('geofield.wkt_generator');
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

}
