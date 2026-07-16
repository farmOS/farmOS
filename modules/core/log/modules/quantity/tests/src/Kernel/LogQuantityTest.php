<?php

declare(strict_types=1);

namespace Drupal\Tests\farm_log_quantity\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\log\Entity\Log;
use Drupal\log\Event\LogEvent;
use Drupal\quantity\Entity\Quantity;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * Tests for farmOS log quantity module.
 */
#[Group('farm')]
#[RunTestsInSeparateProcesses]
class LogQuantityTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'entity',
    'entity_reference_revisions',
    'log',
    'farm_field',
    'farm_log_quantity',
    'farm_log_quantity_test',
    'farm_unit',
    'fraction',
    'options',
    'quantity',
    'state_machine',
    'taxonomy',
    'text',
    'user',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->installEntitySchema('log');
    $this->installEntitySchema('quantity');
    $this->installEntitySchema('taxonomy_term');
    $this->installEntitySchema('user');
    $this->installConfig([
      'farm_log_quantity_test',
      'farm_unit',
    ]);
  }

  /**
   * Test log quantity events.
   */
  public function testLogQuantityEvents() {
    $log_storage = \Drupal::entityTypeManager()->getStorage('log');
    $quantity_storage = \Drupal::entityTypeManager()->getStorage('quantity');

    // Create a test log with a test quantity.
    $quantity = Quantity::create([
      'type' => 'test',
      'value' => 1,
    ]);
    $quantity->save();
    $log = Log::create([
      'type' => 'test',
      'quantity' => [
        [
          'target_id' => $quantity->id(),
        ],
      ],
    ]);
    $log->save();

    // Test that cloning a log clones its quantities.
    // This replicates the logic for cloning logs from
    // \Drupal\log\Form\LogCloneActionForm::submitForm().
    $cloned_log = $log->createDuplicate();
    $event = new LogEvent($cloned_log);
    \Drupal::service('event_dispatcher')->dispatch($event, LogEvent::CLONE);
    $event->log->save();
    $logs = $log_storage->loadMultiple();
    $quantities = $quantity_storage->loadMultiple();
    $this->assertCount(2, $logs);
    $this->assertCount(2, $quantities);
    $this->assertEquals($quantities[1]->get('value')->value, $quantities[2]->get('value')->value);

    // Test that deleting a log deletes its quantities.
    $logs[2]->delete();
    $logs = $log_storage->loadMultiple();
    $quantities = $quantity_storage->loadMultiple();
    $this->assertCount(1, $logs);
    $this->assertCount(1, $quantities);

    // Test that deleting a quantity cleans up the log's reference to it.
    $quantity->delete();
    $logs = $log_storage->loadMultiple();
    $this->assertEmpty($logs[1]->get('quantity')->getValue());
  }

}
