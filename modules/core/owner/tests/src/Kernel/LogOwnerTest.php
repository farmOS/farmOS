<?php

namespace Drupal\Tests\farm_owner\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\log\Entity\Log;
use Drupal\Tests\user\Traits\UserCreationTrait;

/**
 * Tests for farmOS log owner logic.
 *
 * @group farm
 */
class LogOwnerTest extends KernelTestBase {

  use UserCreationTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'log',
    'farm_field',
    'farm_owner',
    'farm_owner_test',
    'state_machine',
    'system',
    'user',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->installSchema('system', 'sequences');
    $this->installEntitySchema('log');
    $this->installEntitySchema('user');
    $this->installConfig(['farm_owner_test']);
  }

  /**
   * Test that saving a log sets its owner.
   */
  public function testLogOwner() {

    // Create two users.
    $user1 = $this->createUser();
    $user2 = $this->createUser();

    // Test that a new log does not have an owner, if no one is logged in.
    $log = Log::create([
      'type' => 'test',
    ]);
    $log->save();
    $this->assertEmpty($log->get('owner')->referencedEntities());

    // Log in the first user.
    $this->setCurrentUser($user1);

    // Test that creating a log without any owners results in the current user
    // becoming an owner.
    $log = Log::create([
      'type' => 'test',
    ]);
    $log->save();
    $this->assertNotEmpty($log->get('owner')->referencedEntities());
    $this->assertEquals($user1->id(), $log->get('owner')->referencedEntities()[0]->id());

    // Test that creating a log with an owner does not override that owner.
    $log = Log::create([
      'type' => 'test',
      'owner' => [['target_id' => $user2->id()]],
    ]);
    $log->save();
    $this->assertNotEmpty($log->get('owner')->referencedEntities());
    $this->assertEquals(1, count($log->get('owner')->referencedEntities()));
    $this->assertEquals($user2->id(), $log->get('owner')->referencedEntities()[0]->id());
  }

}
