<?php

namespace Drupal\Tests\farm_quick_group\Kernel;

use Drupal\asset\Entity\Asset;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Tests\farm_quick\Kernel\QuickFormTestBase;

/**
 * Tests for farmOS group quick form.
 *
 * @group farm
 */
class QuickGroupTest extends QuickFormTestBase {

  /**
   * Quick form ID.
   *
   * @var string
   */
  protected $quickFormId = 'group';

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'farm_equipment',
    'farm_group',
    'farm_observation',
    'farm_quick_group',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->installConfig([
      'farm_equipment',
      'farm_group',
      'farm_observation',
    ]);
  }

  /**
   * Test group quick form submission.
   */
  public function testQuickGroup() {

    // Get today's date.
    $today = new DrupalDateTime('midnight');

    // Create two equipment assets and two group assets.
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
    $group1 = Asset::create([
      'name' => 'Group 1',
      'type' => 'group',
      'status' => 'active',
    ]);
    $group1->save();
    $group2 = Asset::create([
      'name' => 'Group 2',
      'type' => 'group',
      'status' => 'active',
    ]);
    $group2->save();

    // Programmatically submit the group quick form.
    $form_values = [
      'date' => [
        'date' => $today->format('Y-m-d'),
        'time' => $today->format('H:i:s'),
      ],
      'asset' => [
        ['target_id' => $equipment1->id()],
        ['target_id' => $equipment2->id()],
      ],
      'group' => [
        ['target_id' => $group1->id()],
        ['target_id' => $group2->id()],
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

    // Check that the observation log's fields were populated correctly.
    $log = $logs[1];
    $this->assertEquals('observation', $log->bundle());
    $this->assertEquals($today->getTimestamp(), $log->get('timestamp')->value);
    $this->assertEquals("Group Tractor, Mike's Combine into Group 1, Group 2", $log->label());
    $this->assertEquals($equipment1->id(), $log->get('asset')->referencedEntities()[0]->id());
    $this->assertEquals($equipment2->id(), $log->get('asset')->referencedEntities()[1]->id());
    $this->assertEquals($group1->id(), $log->get('group')->referencedEntities()[0]->id());
    $this->assertEquals($group2->id(), $log->get('group')->referencedEntities()[1]->id());
    $this->assertEquals('Lorem ipsum', $log->get('notes')->value);
    $this->assertEquals('done', $log->get('status')->value);
  }

}
