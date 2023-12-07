<?php

namespace Drupal\Tests\farm_quick_movement\Kernel;

use Drupal\asset\Entity\Asset;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Tests\farm_quick\Kernel\QuickFormTestBase;

/**
 * Tests for farmOS movement quick form.
 *
 * @group farm
 */
class QuickMovementTest extends QuickFormTestBase {

  /**
   * Quick form ID.
   *
   * @var string
   */
  protected $quickFormId = 'movement';

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'farm_equipment',
    'farm_activity',
    'farm_land',
    'farm_quick_movement',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->installConfig([
      'farm_activity',
      'farm_equipment',
      'farm_land',
    ]);
  }

  /**
   * Test movement quick form submission.
   */
  public function testQuickMovement() {

    // Get today's date.
    $today = new DrupalDateTime('midnight');

    // Create two equipment assets and two land assets.
    $equipment1 = Asset::create([
      'name' => 'Tractor',
      'type' => 'equipment',
      'status' => 'active',
    ]);
    $equipment1->save();
    $equipment2 = Asset::create([
      'name' => "Mike's Combine",
      'type' => 'equipment',
      'status' => 'active',
    ]);
    $equipment2->save();
    $location1 = Asset::create([
      'name' => 'Field A',
      'type' => 'land',
      'land_type' => 'field',
      'is_fixed' => TRUE,
      'is_location' => TRUE,
      'intrinsic_geometry' => 'POLYGON ((30 10, 40 40, 20 40, 10 20, 30 10))',
      'status' => 'active',
    ]);
    $location1->save();
    $location2 = Asset::create([
      'name' => 'Field B',
      'type' => 'land',
      'land_type' => 'field',
      'is_fixed' => TRUE,
      'is_location' => TRUE,
      'intrinsic_geometry' => 'POLYGON ((20 40, 40 80, 60 60, 10 20, 20 40))',
      'status' => 'active',
    ]);
    $location2->save();

    // Programmatically submit the movement quick form.
    $form_values = [
      'date' => [
        'date' => $today->format('Y-m-d'),
        'time' => $today->format('H:i:s'),
      ],
      'asset' => [
        ['target_id' => $equipment1->id()],
        ['target_id' => $equipment2->id()],
      ],
      'location' => [
        ['target_id' => $location1->id()],
        ['target_id' => $location2->id()],
      ],
      'notes' => [
        'value' => 'Lorem ipsum',
        'format' => 'default',
      ],
      'done' => TRUE,
    ];
    $this->submitQuickForm($form_values);

    // Load logs.
    $logs = $this->logStorage->loadMultiple();

    // Confirm that one log exists.
    $this->assertCount(1, $logs);

    // Check that the activity log's fields were populated correctly.
    $log = $logs[1];
    $this->assertEquals('activity', $log->bundle());
    $this->assertEquals($today->getTimestamp(), $log->get('timestamp')->value);
    $this->assertEquals("Move Tractor, Mike's Combine to Field A, Field B", $log->label());
    $this->assertEquals($equipment1->id(), $log->get('asset')->referencedEntities()[0]->id());
    $this->assertEquals($equipment2->id(), $log->get('asset')->referencedEntities()[1]->id());
    $this->assertEquals($location1->id(), $log->get('location')->referencedEntities()[0]->id());
    $this->assertEquals($location2->id(), $log->get('location')->referencedEntities()[1]->id());
    $this->assertEquals('Lorem ipsum', $log->get('notes')->value);
    $this->assertEquals('GEOMETRYCOLLECTION (POLYGON ((30 10, 40 40, 20 40, 10 20, 30 10)),POLYGON ((20 40, 40 80, 60 60, 10 20, 20 40)))', $log->get('geometry')->value);
    $this->assertEquals('done', $log->get('status')->value);

    // Programmatically submit the movement quick form again, but this time
    // override the geometry.
    $form_values['geometry']['value'] = 'POINT (30 10)';
    $this->submitQuickForm($form_values);

    // Load logs.
    $logs = $this->logStorage->loadMultiple();

    // Confirm that two logs exist.
    $this->assertCount(2, $logs);

    // Check that the geometry was overridden.
    $log = $logs[2];
    $this->assertEquals($form_values['geometry']['value'], $log->get('geometry')->value);

    // Programmatically submit the movement quick form again, but this time
    // remove the location without removing geometry. This should fail
    // validation.
    $form_values['location'] = NULL;
    $this->submitQuickForm($form_values);

    // Load logs.
    $logs = $this->logStorage->loadMultiple();

    // Confirm that only two logs still exist.
    $this->assertCount(2, $logs);
  }

}
