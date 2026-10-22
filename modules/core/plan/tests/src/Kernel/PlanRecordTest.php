<?php

declare(strict_types=1);

namespace Drupal\Tests\plan\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\Tests\user\Traits\UserCreationTrait;
use Drupal\plan\Entity\Plan;
use Drupal\plan\Entity\PlanRecord;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * Tests for plan_record entities.
 */
#[Group('farm')]
#[RunTestsInSeparateProcesses]
class PlanRecordTest extends KernelTestBase {

  use UserCreationTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'entity',
    'plan',
    'plan_test',
    'user',
    'state_machine',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->installEntitySchema('plan');
    $this->installEntitySchema('plan_record');
    $this->installEntitySchema('user');
    $this->installConfig(['plan_test']);
  }

  /**
   * Test plan_record entities.
   */
  public function testPlanRecord() {

    // Get storage for plan and plan_record entities.
    $plan_storage = \Drupal::entityTypeManager()->getStorage('plan');
    $plan_record_storage = \Drupal::entityTypeManager()->getStorage('plan_record');

    // Create a plan entity.
    $plan = Plan::create([
      'name' => 'Test plan',
      'type' => 'default',
    ]);
    $plan->save();

    // Confirm that the plan entity was created.
    $plans = $plan_storage->loadMultiple();
    $this->assertCount(1, $plans);

    // Create two plan_record entities that reference the plan.
    $plan_record1 = PlanRecord::create([
      'plan' => $plan,
      'type' => 'default',
    ]);
    $plan_record1->save();
    $plan_record2 = PlanRecord::create([
      'plan' => $plan,
      'type' => 'default',
    ]);
    $plan_record2->save();

    // Confirm that the plan_record entities were created.
    $plan_records = $plan_record_storage->loadMultiple();
    $this->assertCount(2, $plan_records);

    // Delete the plan.
    $plan->delete();

    // Confirm that the plan and plan_record entities were all deleted.
    $plans = $plan_storage->loadMultiple();
    $this->assertCount(0, $plans);
    $plan_records = $plan_record_storage->loadMultiple();
    $this->assertCount(0, $plan_records);
  }

  /**
   * Test that plan_record access inherits the parent plan's cacheability.
   *
   * @see https://github.com/farmOS/farmOS/issues/1089
   */
  public function testPlanRecordAccessCacheability() {

    // Create a plan and a plan_record that references it.
    $plan = Plan::create([
      'name' => 'Test plan',
      'type' => 'default',
    ]);
    $plan->save();
    $plan_record = PlanRecord::create([
      'plan' => $plan,
      'type' => 'default',
    ]);
    $plan_record->save();

    // Create a user to perform access checks as.
    $account = $this->createUser();

    // The plan's access result carries cacheability metadata (the plan uses the
    // entity API uncacheable access handler, which varies access by the current
    // user's permissions). The plan_record's access result must inherit that
    // same cacheability, so it is invalidated whenever the plan's access is.
    foreach (['view', 'update', 'delete'] as $operation) {
      $plan_access = $plan->access($operation, $account, TRUE);
      $record_access = $plan_record->access($operation, $account, TRUE);
      $this->assertEqualsCanonicalizing($plan_access->getCacheContexts(), $record_access->getCacheContexts(), "plan_record '$operation' access inherits the plan's cache contexts.");
      $this->assertEqualsCanonicalizing($plan_access->getCacheTags(), $record_access->getCacheTags(), "plan_record '$operation' access inherits the plan's cache tags.");
      $this->assertEquals($plan_access->getCacheMaxAge(), $record_access->getCacheMaxAge(), "plan_record '$operation' access inherits the plan's cache max-age.");
    }
  }

}
