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
  }

  /**
   * Test auto population of log geometry field.
   */
  public function testPopulateLogGeometry() {

    // Generate random WKT geometries.
    $geom1 = $this->reduceWkt($this->wktGenerator->WktGenerateGeometry());
    $geom2 = $this->reduceWkt($this->wktGenerator->WktGenerateGeometry());
    $geom3 = $this->reduceWkt($this->wktGenerator->WktGenerateGeometry());

    // Create two location assets.
    $location1 = Asset::create([
      'type' => 'location',
      'name' => $this->randomMachineName(),
      'status' => 'active',
      'geometry' => $geom1,
    ]);
    $location1->save();

    $location2 = Asset::create([
      'type' => 'location',
      'name' => $this->randomMachineName(),
      'status' => 'active',
      'geometry' => $geom2,
    ]);
    $location2->save();

    // When a log is saved with a location and without a geometry, the geometry
    // is copied from the location.
    $log = Log::create([
      'type' => 'movement',
      'status' => 'pending',
      'location' => ['target_id' => $location1->id()],
    ]);
    $log->save();
    $this->assertEquals($location1->get('geometry')->value, $log->get('geometry')->value, 'Empty geometry is populated from location.');

    // When multiple locations are added, all of their geometries are combined.
    $log->location = [
      ['target_id' => $location1->id()],
      ['target_id' => $location2->id()],
    ];
    $log->geometry->value = '';
    $log->save();
    $combined = $this->combineWkt([$geom1, $geom2]);
    $this->assertEquals($combined, $log->get('geometry')->value, 'Geometries from multiple locations are combined.');

    // When a log's geometry is set, it is saved.
    $log->geometry->value = $geom3;
    $log->save();
    $this->assertEquals($geom3, $log->get('geometry')->value, 'Custom geometry can be saved.');

    // When a log's locations change, and the geometry is customized, the
    // geometry is not updated.
    $log->location = ['target_id' => $location1->id()];
    $log->save();
    $this->assertEquals($geom3, $log->get('geometry')->value, 'Custom geometry is not overwritten when locations change.');
  }

}
