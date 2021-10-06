<?php

namespace Drupal\Tests\farm_group\Kernel;

use Drupal\asset\Entity\Asset;
use Drupal\KernelTests\KernelTestBase;
use Drupal\log\Entity\Log;
use Drupal\Tests\farm_test\Kernel\FarmEntityCacheTestTrait;

/**
 * Tests for farmOS group membership logic.
 *
 * @group farm
 */
class GroupTest extends KernelTestBase {

  use FarmEntityCacheTestTrait;

  /**
   * Group membership service.
   *
   * @var \Drupal\farm_group\GroupMembershipInterface
   */
  protected $groupMembership;

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
   * {@inheritdoc}
   */
  protected static $modules = [
    'asset',
    'log',
    'farm_field',
    'farm_group',
    'farm_group_test',
    'farm_location',
    'farm_log',
    'geofield',
    'state_machine',
    'user',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->groupMembership = \Drupal::service('group.membership');
    $this->assetLocation = \Drupal::service('asset.location');
    $this->logLocation = \Drupal::service('log.location');
    $this->installEntitySchema('asset');
    $this->installEntitySchema('log');
    $this->installEntitySchema('user');
    $this->installConfig([
      'farm_group',
      'farm_group_test',
    ]);
  }

  /**
   * Test asset group membership.
   */
  public function testGroupMembership() {

    // Create an animal asset.
    /** @var \Drupal\asset\Entity\AssetInterface $animal */
    $animal = Asset::create([
      'type' => 'animal',
      'name' => $this->randomMachineName(),
      'status' => 'active',
    ]);
    $animal->save();

    // Populate a cache value dependent on the animal's cache tags.
    $this->populateEntityTestCache($animal);

    // Create group assets.
    /** @var \Drupal\asset\Entity\AssetInterface $first_group */
    $first_group = Asset::create([
      'type' => 'group',
      'name' => $this->randomMachineName(),
      'status' => 'active',
    ]);
    $first_group->save();
    /** @var \Drupal\asset\Entity\AssetInterface $second_group */
    $second_group = Asset::create([
      'type' => 'group',
      'name' => $this->randomMachineName(),
      'status' => 'active',
    ]);
    $second_group->save();

    // When an asset has no group assignment logs, it has no group membership.
    $this->assertFalse($this->groupMembership->hasGroup($animal), 'New assets do not have group membership.');
    $this->assertEmpty($this->groupMembership->getGroup($animal), 'New assets do not reference any groups.');
    $this->assertEmpty($this->groupMembership->getGroupMembers($first_group), 'New groups have no members.');
    $this->assertEmpty($this->groupMembership->getGroupMembers($second_group), 'New groups have no members.');

    // Assert that the animal's cache tags were not invalidated.
    $this->assertEntityTestCache($animal, TRUE);

    // Create a "done" log that assigns the animal to the group.
    /** @var \Drupal\log\Entity\LogInterface $first_log */
    $first_log = Log::create([
      'type' => 'test',
      'status' => 'done',
      'is_group_assignment' => TRUE,
      'group' => ['target_id' => $first_group->id()],
      'asset' => ['target_id' => $animal->id()],
    ]);
    $first_log->save();

    // When an asset has a done group assignment logs, it has group membership.
    $this->assertTrue($this->groupMembership->hasGroup($animal), 'Asset with group assignment has group membership.');
    $this->assertEquals($first_group->id(), $this->groupMembership->getGroup($animal)[0]->id(), 'Asset with group assignment is in the assigned group.');
    $this->assertEquals(1, count($this->groupMembership->getGroupMembers($first_group)), 'When an asset becomes a group member, the group has one member.');
    $this->assertEmpty($this->groupMembership->getGroupMembers($second_group), 'When an asset becomes a group member, other groups are unaffected.');

    // Assert that the animal's cache tags were invalidated.
    $this->assertEntityTestCache($animal, FALSE);

    // Re-populate a cache value dependent on the animal's cache tags.
    $this->populateEntityTestCache($animal);

    // Create a "pending" log that assigns the animal to the second group.
    /** @var \Drupal\log\Entity\LogInterface $second_log */
    $second_log = Log::create([
      'type' => 'test',
      'status' => 'pending',
      'is_group_assignment' => TRUE,
      'group' => ['target_id' => $second_group->id()],
      'asset' => ['target_id' => $animal->id()],
    ]);
    $second_log->save();

    // When an asset has a pending group assignment logs, it still has the same
    // group membership as before.
    $this->assertEquals($first_group->id(), $this->groupMembership->getGroup($animal)[0]->id(), 'Pending group assignment logs do not affect membership.');
    $this->assertEmpty($this->groupMembership->getGroupMembers($second_group), 'Groups with only pending membership have zero members.');

    // Assert that the animal's cache tags were not invalidated.
    $this->assertEntityTestCache($animal, TRUE);

    // When the log is marked as "done", the asset's membership is updated.
    $second_log->status = 'done';
    $second_log->save();
    $this->assertEquals($second_group->id(), $this->groupMembership->getGroup($animal)[0]->id(), 'A second group assignment log updates group membership.');
    $this->assertEquals(1, count($this->groupMembership->getGroupMembers($second_group)), 'Completed group assignment logs add group members.');

    // Assert that the animal's cache tags were invalidated.
    $this->assertEntityTestCache($animal, FALSE);

    // Re-populate a cache value dependent on the animal's cache tags.
    $this->populateEntityTestCache($animal);

    // Create a third "done" log in the future.
    /** @var \Drupal\log\Entity\LogInterface $third_log */
    $third_log = Log::create([
      'type' => 'test',
      'timestamp' => \Drupal::time()->getRequestTime() + 86400,
      'status' => 'done',
      'is_group_assignment' => TRUE,
      'group' => ['target_id' => $first_group->id()],
      'asset' => ['target_id' => $animal->id()],
    ]);
    $third_log->save();

    // When an asset has a "done" group assignment log in the future, the asset
    // group membership remains the same as the previous "done" movement log.
    $this->assertEquals($second_group->id(), $this->groupMembership->getGroup($animal)[0]->id(), 'A third group assignment log in the future does not update group membership.');
    $this->assertEquals(1, count($this->groupMembership->getGroupMembers($second_group)), 'Future group assignment logs do not affect members.');

    // Assert that the animal's cache tags were not invalidated.
    $this->assertEntityTestCache($animal, TRUE);

    // Create a fourth log with no group reference.
    /** @var \Drupal\log\Entity\LogInterface $fourth_log */
    $fourth_log = Log::create([
      'type' => 'test',
      'status' => 'done',
      'is_group_assignment' => TRUE,
      'group' => [],
      'asset' => ['target_id' => $animal->id()],
    ]);
    $fourth_log->save();

    // When a group assignment log is created with no group references, it
    // effectively "unsets" the asset's group membership.
    $this->assertFalse($this->groupMembership->hasGroup($animal), 'Asset group membership can be unset.');
    $this->assertEmpty($this->groupMembership->getGroup($animal), 'Unset group membership does not reference any groups.');
    $this->assertEquals(0, count($this->groupMembership->getGroupMembers($first_group)), 'Unset group membership unsets group members.');
    $this->assertEquals(0, count($this->groupMembership->getGroupMembers($second_group)), 'Unset group membership unsets group members.');

    // Assert that the animal's cache tags were invalidated.
    $this->assertEntityTestCache($animal, FALSE);
  }

  /**
   * Test asset location with group membership.
   */
  public function testAssetLocation() {

    // Create an animal asset.
    /** @var \Drupal\asset\Entity\AssetInterface $animal */
    $animal = Asset::create([
      'type' => 'animal',
      'name' => $this->randomMachineName(),
      'status' => 'active',
    ]);
    $animal->save();

    // Create a group asset.
    /** @var \Drupal\asset\Entity\AssetInterface $group */
    $group = Asset::create([
      'type' => 'group',
      'name' => $this->randomMachineName(),
      'status' => 'active',
    ]);
    $group->save();

    // Create a log that assigns the animal to the group.
    /** @var \Drupal\log\Entity\LogInterface $first_log */
    $first_log = Log::create([
      'type' => 'test',
      'status' => 'done',
      'is_group_assignment' => TRUE,
      'group' => ['target_id' => $group->id()],
      'asset' => ['target_id' => $animal->id()],
    ]);
    $first_log->save();

    // Create two pasture assets.
    /** @var \Drupal\asset\Entity\AssetInterface $first_pasture */
    $first_pasture = Asset::create([
      'type' => 'pasture',
      'name' => $this->randomMachineName(),
      'status' => 'active',
    ]);
    $first_pasture->save();
    /** @var \Drupal\asset\Entity\AssetInterface $second_pasture */
    $second_pasture = Asset::create([
      'type' => 'pasture',
      'name' => $this->randomMachineName(),
      'status' => 'active',
    ]);
    $second_pasture->save();

    // Confirm that new locations are empty.
    $this->assertEmpty($this->assetLocation->getAssetsByLocation($first_pasture), 'New locations are empty.');

    // Create a log that moves the animal to the first pasture.
    /** @var \Drupal\log\Entity\LogInterface $second_log */
    $second_log = Log::create([
      'type' => 'test',
      'status' => 'done',
      'is_movement' => TRUE,
      'location' => ['target_id' => $first_pasture->id()],
      'asset' => ['target_id' => $animal->id()],
    ]);
    $second_log->save();

    // Confirm that the animal is located in the first pasture.
    $this->assertEquals($this->logLocation->getLocation($second_log), $this->assetLocation->getLocation($animal), 'Asset location is determined by asset membership log.');
    $this->assertEquals($this->logLocation->getGeometry($second_log), $this->assetLocation->getGeometry($animal), 'Asset geometry is determined by asset membership log.');
    $this->assertEquals(1, count($this->assetLocation->getAssetsByLocation($first_pasture)), 'Locations have assets that are moved to them.');
    $this->assertEmpty($this->assetLocation->getAssetsByLocation($second_pasture), 'Locations that do not have assets moved to them are unaffected.');

    // Create a log that moves the group to the second pasture.
    /** @var \Drupal\log\Entity\LogInterface $third_log */
    $third_log = Log::create([
      'type' => 'test',
      'status' => 'done',
      'is_movement' => TRUE,
      'location' => ['target_id' => $second_pasture->id()],
      'asset' => ['target_id' => $group->id()],
    ]);
    $third_log->save();

    // Confirm that the animal is located in the second pasture.
    $this->assertEquals($this->logLocation->getLocation($third_log), $this->assetLocation->getLocation($animal), 'Asset location is determined by group membership log.');
    $this->assertEquals($this->logLocation->getGeometry($third_log), $this->assetLocation->getGeometry($animal), 'Asset geometry is determined by group membership log.');
    $this->assertEmpty($this->assetLocation->getAssetsByLocation($first_pasture), 'A group movement removes assets from their previous location.');
    $this->assertEquals(2, count($this->assetLocation->getAssetsByLocation($second_pasture)), 'A group movement adds assets to their new location.');

    // Create a log that unsets the group location.
    /** @var \Drupal\log\Entity\LogInterface $fourth_log */
    $fourth_log = Log::create([
      'type' => 'test',
      'status' => 'done',
      'is_movement' => TRUE,
      'location' => [],
      'asset' => ['target_id' => $group->id()],
    ]);
    $fourth_log->save();

    // Confirm that the animal location was unset.
    $this->assertEquals($this->logLocation->getLocation($fourth_log), $this->assetLocation->getLocation($animal), 'Asset location can be unset by group membership log.');
    $this->assertEquals($this->logLocation->getGeometry($fourth_log), $this->assetLocation->getGeometry($animal), 'Asset geometry can be unset by group membership log.');
    $this->assertEmpty($this->assetLocation->getAssetsByLocation($first_pasture), 'Unsetting group location removes member assets from all locations.');
    $this->assertEmpty($this->assetLocation->getAssetsByLocation($second_pasture), 'Unsetting group location removes member assets from all locations.');

    // Create a log that unsets the animal's group membership.
    /** @var \Drupal\log\Entity\LogInterface $fifth_log */
    $fifth_log = Log::create([
      'type' => 'test',
      'status' => 'done',
      'is_group_assignment' => TRUE,
      'group' => [],
      'asset' => ['target_id' => $animal->id()],
    ]);
    $fifth_log->save();

    // Confirm that the animal's location is determined by its own movement
    // logs now.
    $this->assertEquals($this->logLocation->getLocation($second_log), $this->assetLocation->getLocation($animal), 'Asset location is determined by asset membership log.');
    $this->assertEquals($this->logLocation->getGeometry($second_log), $this->assetLocation->getGeometry($animal), 'Asset geometry is determined by asset membership log.');
    $this->assertEquals(1, count($this->assetLocation->getAssetsByLocation($first_pasture)), 'Unsetting group membership adds assets to their previous location.');
    $this->assertEmpty($this->assetLocation->getAssetsByLocation($second_pasture), 'Unsetting group membership removes member assets from the group location.');
  }

}
