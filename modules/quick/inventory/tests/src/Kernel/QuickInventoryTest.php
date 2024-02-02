<?php

namespace Drupal\Tests\farm_quick_inventory\Kernel;

use Drupal\asset\Entity\Asset;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\taxonomy\Entity\Term;
use Drupal\Tests\farm_quick\Kernel\QuickFormTestBase;

/**
 * Tests for farmOS inventory quick form.
 *
 * @group farm
 */
class QuickInventoryTest extends QuickFormTestBase {

  /**
   * Quick form ID.
   *
   * @var string
   */
  protected $quickFormId = 'inventory';

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'farm_activity',
    'farm_equipment',
    'farm_inventory',
    'farm_observation',
    'farm_quantity_standard',
    'farm_quick_inventory',
    'farm_unit',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->installConfig([
      'farm_activity',
      'farm_equipment',
      'farm_observation',
      'farm_quantity_standard',
      'farm_unit',
    ]);
  }

  /**
   * Test inventory quick form submission.
   */
  public function testQuickInventory() {

    // Get today's date.
    $today = new DrupalDateTime('midnight');

    // Create an equipment asset.
    $equipment = Asset::create([
      'name' => 'Tractor',
      'type' => 'equipment',
      'status' => 'active',
    ]);
    $equipment->save();

    // Programmatically submit the inventory quick form (reset to 1).
    $form_values = [
      'date' => [
        'date' => $today->format('Y-m-d'),
        'time' => $today->format('H:i:s'),
      ],
      'asset' => [
        ['target_id' => $equipment->id()],
      ],
      'quantity' => [
        'value' => '1',
        'units' => '',
        'measure' => '',
      ],
      'inventory_adjustment' => 'reset',
      'notes' => [
        'value' => 'Lorem ipsum',
        'format' => 'default',
      ],
      'log_type' => 'observation',
      'done' => TRUE,
    ];
    $this->submitQuickForm($form_values);

    // Load logs.
    $logs = $this->logStorage->loadMultiple();

    // Confirm that one log exists.
    $this->assertCount(1, $logs);

    // Check that the log's fields were populated correctly.
    $log = $logs[1];
    $this->assertEquals('observation', $log->bundle());
    $this->assertEquals($today->getTimestamp(), $log->get('timestamp')->value);
    $this->assertEquals('Reset inventory of Tractor to 1', $log->label());
    $this->assertEquals('1', $log->get('quantity')->referencedEntities()[0]->get('value')[0]->get('decimal')->getValue());
    $this->assertCount(0, $log->get('quantity')->referencedEntities()[0]->get('units')->referencedEntities());
    $this->assertEquals('', $log->get('quantity')->referencedEntities()[0]->get('measure')->value);
    $this->assertEquals('reset', $log->get('quantity')->referencedEntities()[0]->get('inventory_adjustment')->value);
    $this->assertEquals($equipment->id(), $log->get('quantity')->referencedEntities()[0]->get('inventory_asset')->referencedEntities()[0]->id());
    $this->assertEquals('Lorem ipsum', $log->get('notes')->value);
    $this->assertEquals('done', $log->get('status')->value);

    // Check that the asset has a single inventory of 1.
    $inventory = \Drupal::service('asset.inventory')->getInventory($equipment);
    $this->assertCount(1, $inventory);
    $this->assertEquals('1', $inventory[0]['value']);
    $this->assertEquals('', $inventory[0]['units']);
    $this->assertEquals('', $inventory[0]['measure']);

    // Programmatically submit the inventory quick form (increment by 1 with an
    // activity log).
    $form_values = [
      'date' => [
        'date' => $today->format('Y-m-d'),
        'time' => $today->format('H:i:s'),
      ],
      'asset' => [
        ['target_id' => $equipment->id()],
      ],
      'quantity' => [
        'value' => '1',
        'units' => '',
        'measure' => '',
      ],
      'inventory_adjustment' => 'increment',
      'log_type' => 'activity',
      'done' => TRUE,
    ];
    $this->submitQuickForm($form_values);

    // Confirm that two logs exists.
    $logs = $this->logStorage->loadMultiple();
    $this->assertCount(2, $logs);

    // Check that the log is an activity and that the name was populated
    // correctly.
    $log = $logs[2];
    $this->assertEquals('activity', $log->bundle());
    $this->assertEquals('Increment inventory of Tractor by 1', $log->label());

    // Check that the asset has a single inventory of 2.
    $inventory = \Drupal::service('asset.inventory')->getInventory($equipment);
    $this->assertCount(1, $inventory);
    $this->assertEquals('2', $inventory[0]['value']);
    $this->assertEquals('', $inventory[0]['units']);
    $this->assertEquals('', $inventory[0]['measure']);

    // Programmatically submit the inventory quick form (decrement by 1).
    $form_values = [
      'date' => [
        'date' => $today->format('Y-m-d'),
        'time' => $today->format('H:i:s'),
      ],
      'asset' => [
        ['target_id' => $equipment->id()],
      ],
      'quantity' => [
        'value' => '1',
        'units' => '',
        'measure' => '',
      ],
      'inventory_adjustment' => 'decrement',
      'log_type' => 'observation',
      'done' => TRUE,
    ];
    $this->submitQuickForm($form_values);

    // Confirm that three logs exists.
    $logs = $this->logStorage->loadMultiple();
    $this->assertCount(3, $logs);

    // Check that the log name was populated correctly.
    $log = $logs[3];
    $this->assertEquals('Decrement inventory of Tractor by 1', $log->label());

    // Check that the asset has a single inventory of 1.
    $inventory = \Drupal::service('asset.inventory')->getInventory($equipment);
    $this->assertCount(1, $inventory);
    $this->assertEquals('1', $inventory[0]['value']);
    $this->assertEquals('', $inventory[0]['units']);
    $this->assertEquals('', $inventory[0]['measure']);

    // Create a unit term.
    $unit = Term::create([
      'name' => 'liters',
      'vid' => 'unit',
    ]);
    $unit->save();

    // Programmatically submit the inventory quick form with units and measure.
    $form_values = [
      'date' => [
        'date' => $today->format('Y-m-d'),
        'time' => $today->format('H:i:s'),
      ],
      'asset' => [
        ['target_id' => $equipment->id()],
      ],
      'quantity' => [
        'value' => '10',
        'units' => [
          ['target_id' => $unit->id()],
        ],
        'measure' => 'volume',
      ],
      'inventory_adjustment' => 'reset',
      'log_type' => 'observation',
      'done' => TRUE,
    ];
    $this->submitQuickForm($form_values);

    // Confirm that four logs exists.
    $logs = $this->logStorage->loadMultiple();
    $this->assertCount(4, $logs);

    // Check that the log's name and quantity measure and units were populated.
    $log = $logs[4];
    $this->assertEquals('Reset inventory of Tractor to 10 liters (volume)', $log->label());
    $this->assertEquals('liters', $log->get('quantity')->referencedEntities()[0]->get('units')->referencedEntities()[0]->get('name')->value);
    $this->assertEquals('volume', $log->get('quantity')->referencedEntities()[0]->get('measure')->value);

    // Check that the asset has two inventories.
    $inventory = \Drupal::service('asset.inventory')->getInventory($equipment);
    $this->assertCount(2, $inventory);

    // Load the volume (liters) inventory and confirm that it is 10.
    $inventory = \Drupal::service('asset.inventory')->getInventory($equipment, 'volume', $unit->id());
    $this->assertEquals('volume', $inventory[0]['measure']);
    $this->assertEquals('liters', $inventory[0]['units']);
    $this->assertEquals('10', $inventory[0]['value']);

    // Test customizing the log name.
    $form_values = [
      'date' => [
        'date' => $today->format('Y-m-d'),
        'time' => $today->format('H:i:s'),
      ],
      'asset' => [
        ['target_id' => $equipment->id()],
      ],
      'quantity' => [
        'value' => '1',
        'units' => '',
        'measure' => '',
      ],
      'inventory_adjustment' => 'reset',
      'log_type' => 'observation',
      'done' => TRUE,
      'custom_name' => TRUE,
      'name' => 'Test custom log name',
    ];
    $this->submitQuickForm($form_values);

    // Confirm that five logs exists.
    $logs = $this->logStorage->loadMultiple();
    $this->assertCount(5, $logs);

    // Check that the log name was populated correctly.
    $log = $logs[5];
    $this->assertEquals('Test custom log name', $log->label());
  }

}
