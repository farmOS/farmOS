<?php

namespace Drupal\Tests\farm_group\Kernel;

use Drupal\asset\Entity\Asset;
use Drupal\KernelTests\KernelTestBase;
use Drupal\log\Entity\Log;
use Drupal\Tests\farm_test\Kernel\FarmAssetTestTrait;
use Drupal\Tests\farm_test\Kernel\FarmEntityCacheTestTrait;

/**
 * Tests for farmOS group membership logic.
 *
 * @group farm
 */
class GroupTest extends KernelTestBase {

  use FarmAssetTestTrait;
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
    'farm_log_asset',
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
    $this->assertCorrectAssets([], $this->groupMembership->getGroupMembers([$first_group]), TRUE, 'New groups have no members.');
    $this->assertCorrectAssets([], $this->groupMembership->getGroupMembers([$second_group]), TRUE, 'New groups have no members.');

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
    $this->assertCorrectAssets([$animal], $this->groupMembership->getGroupMembers([$first_group]), TRUE, 'When an asset becomes a group member, the group has one member.');
    $this->assertCorrectAssets([], $this->groupMembership->getGroupMembers([$second_group]), TRUE, 'When an asset becomes a group member, other groups are unaffected.');

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
    $this->assertCorrectAssets([], $this->groupMembership->getGroupMembers([$second_group]), TRUE, 'Groups with only pending membership have zero members.');

    // Assert that the animal's cache tags were not invalidated.
    $this->assertEntityTestCache($animal, TRUE);

    // When the log is marked as "done", the asset's membership is updated.
    $second_log->status = 'done';
    $second_log->save();
    $this->assertEquals($second_group->id(), $this->groupMembership->getGroup($animal)[0]->id(), 'A second group assignment log updates group membership.');
    $this->assertCorrectAssets([$animal], $this->groupMembership->getGroupMembers([$second_group]), TRUE, 'Completed group assignment logs add group members.');

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
    $this->assertCorrectAssets([$animal], $this->groupMembership->getGroupMembers([$second_group]), TRUE, 'Future group assignment logs do not affect members.');

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
    $this->assertCorrectAssets([], $this->groupMembership->getGroup($animal), 'Unset group membership does not reference any groups.');
    $this->assertCorrectAssets([], $this->groupMembership->getGroupMembers([$first_group]), TRUE, 'Unset group membership unsets group members.');
    $this->assertCorrectAssets([], $this->groupMembership->getGroupMembers([$second_group]), TRUE, 'Unset group membership unsets group members.');

    // Assert that the animal's cache tags were invalidated.
    $this->assertEntityTestCache($animal, FALSE);

    // Re-populate a cache value dependent on the animal's cache tags.
    $this->populateEntityTestCache($animal);

    // Delete the fourth log.
    $fourth_log->delete();

    // When a group membership is deleted the last group membership log is used.
    $this->assertEquals($second_group->id(), $this->groupMembership->getGroup($animal)[0]->id(), 'Deleting a group membership log updates group membership.');
    $this->assertCorrectAssets([$animal], $this->groupMembership->getGroupMembers([$second_group]), TRUE, 'Deleting a group membership log updates group members.');

    // Assert that the animal's cache tags were invalidated.
    $this->assertEntityTestCache($animal, FALSE);

    // Create a second animal.
    /** @var \Drupal\asset\Entity\AssetInterface $second_animal */
    $second_animal = Asset::create([
      'type' => 'animal',
      'name' => $this->randomMachineName(),
      'status' => 'active',
    ]);
    $second_animal->save();

    // Create a "done" log that assigns the second animal to the first group.
    /** @var \Drupal\log\Entity\LogInterface $fifth_log */
    $fifth_log = Log::create([
      'type' => 'test',
      'status' => 'done',
      'is_group_assignment' => TRUE,
      'group' => ['target_id' => $first_group->id()],
      'asset' => ['target_id' => $second_animal->id()],
    ]);
    $fifth_log->save();

    // Assert that group members from multiple groups can be queried together.
    $this->assertEquals($second_group->id(), $this->groupMembership->getGroup($animal)[0]->id(), 'The first animal is in the second group.');
    $this->assertEquals($first_group->id(), $this->groupMembership->getGroup($second_animal)[0]->id(), 'The second animal is in the first group.');
    $group_members = $this->groupMembership->getGroupMembers([$first_group, $second_group]);
    $this->assertCorrectAssets([$animal, $second_animal], $group_members, TRUE, 'Group members from multiple groups can be queried together.');
  }

  /**
   * Test recursive asset group membership.
   */
  public function testRecursiveGroupMembership() {

    // Create an animal asset.
    /** @var \Drupal\asset\Entity\AssetInterface $animal */
    $animal = Asset::create([
      'type' => 'animal',
      'name' => $this->randomMachineName(),
      'status' => 'active',
    ]);
    $animal->save();

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

    // Create a "done" log to assign the animal to the second group.
    /** @var \Drupal\log\Entity\LogInterface $first_log */
    $first_log = Log::create([
      'type' => 'test',
      'status' => 'done',
      'is_group_assignment' => TRUE,
      'group' => ['target_id' => $second_group->id()],
      'asset' => ['target_id' => $animal->id()],
    ]);
    $first_log->save();

    // Create a "pending" log to assign the second group to the first group.
    /** @var \Drupal\log\Entity\LogInterface $second_log */
    $second_log = Log::create([
      'type' => 'test',
      'status' => 'pending',
      'is_group_assignment' => TRUE,
      'group' => ['target_id' => $first_group->id()],
      'asset' => ['target_id' => $second_group->id()],
    ]);
    $second_log->save();

    // Assert that the second group has no group and a single member.
    $this->assertFalse($this->groupMembership->hasGroup($second_group), 'The second group does not have a group.');
    $this->assertCorrectAssets([$animal], $this->groupMembership->getGroupMembers([$second_group]), TRUE, 'The second group has one member.');

    // Assert that the first group has no members.
    $this->assertCorrectAssets([], $this->groupMembership->getGroupMembers([$first_group], FALSE), TRUE, 'The first group has no members.');
    $this->assertCorrectAssets([], $this->groupMembership->getGroupMembers([$first_group], TRUE), TRUE, 'The first group has no recursive members.');

    // Save the second log to create the nested group membership.
    $second_log->status = 'done';
    $second_log->save();

    // Assert that the second group has a group and a single member.
    $this->assertTrue($this->groupMembership->hasGroup($second_group), 'The second group has a group.');
    $this->assertCorrectAssets([$animal], $this->groupMembership->getGroupMembers([$second_group]), TRUE, 'The second group has one member.');

    // Assert that the first group has a single direct member.
    $first_group_members = $this->groupMembership->getGroupMembers([$first_group], FALSE);
    $this->assertCorrectAssets([$second_group], $first_group_members, TRUE, 'The first group has one direct member.');

    // Assert that the first group has two recursive members.
    $first_group_recursive_members = $this->groupMembership->getGroupMembers([$first_group], TRUE);
    $this->assertCorrectAssets([$animal, $second_group], $first_group_recursive_members, TRUE, 'The first group has two recursive members.');
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

    // Populate a cache value dependent on the animal's cache tags.
    $this->populateEntityTestCache($animal);

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

    // Assert that the animal's cache tags were invalidated.
    $this->assertEntityTestCache($animal, FALSE);

    // Re-populate a cache value dependent on the animal's cache tags.
    $this->populateEntityTestCache($animal);

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
    $this->assertCorrectAssets([], $this->assetLocation->getAssetsByLocation([$first_pasture]), TRUE, 'New locations are empty.');
    $this->assertCorrectAssets([], $this->assetLocation->getAssetsByLocation([$first_pasture]), TRUE);

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
    $this->assertCorrectAssets([$animal], $this->assetLocation->getAssetsByLocation([$first_pasture]), TRUE, 'Locations have assets that are moved to them.');
    $this->assertCorrectAssets([], $this->assetLocation->getAssetsByLocation([$second_pasture]), TRUE, 'Locations that do not have assets moved to them are unaffected.');

    // Assert that the animal's cache tags were invalidated.
    $this->assertEntityTestCache($animal, FALSE);

    // Re-populate a cache value dependent on the animal's cache tags.
    $this->populateEntityTestCache($animal);

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
    $this->assertCorrectAssets([], $this->assetLocation->getAssetsByLocation([$first_pasture]), TRUE, 'A group movement removes assets from their previous location.');
    $this->assertCorrectAssets([$animal, $group], $this->assetLocation->getAssetsByLocation([$second_pasture]), TRUE, 'A group movement adds assets to their new location.');

    // Assert that the animal's cache tags were invalidated.
    $this->assertEntityTestCache($animal, FALSE);

    // Re-populate a cache value dependent on the animal's cache tags.
    $this->populateEntityTestCache($animal);

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
    $this->assertCorrectAssets([], $this->assetLocation->getAssetsByLocation([$first_pasture]), TRUE, 'Unsetting group location removes member assets from all locations.');
    $this->assertCorrectAssets([], $this->assetLocation->getAssetsByLocation([$second_pasture]), TRUE, 'Unsetting group location removes member assets from all locations.');

    // Assert that the animal's cache tags were invalidated.
    $this->assertEntityTestCache($animal, FALSE);

    // Re-populate a cache value dependent on the animal's cache tags.
    $this->populateEntityTestCache($animal);

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
    $this->assertCorrectAssets([$animal], $this->assetLocation->getAssetsByLocation([$first_pasture]), TRUE, 'Unsetting group membership adds assets to their previous location.');
    $this->assertCorrectAssets([], $this->assetLocation->getAssetsByLocation([$second_pasture]), TRUE, 'Unsetting group membership removes member assets from the group location.');

    // Assert that the animal's cache tags were invalidated.
    $this->assertEntityTestCache($animal, FALSE);

    // Delete the fifth log before re-populating asset cache.
    $fifth_log->delete();

    // Re-populate a cache value dependent on the animal's cache tags.
    $this->populateEntityTestCache($animal);

    // Delete the fourth log.
    // When a group's location log is deleted the group's last location is used.
    $fourth_log->delete();

    // Confirm that the animal is located in the second pasture.
    $this->assertEquals($this->logLocation->getLocation($third_log), $this->assetLocation->getLocation($animal), 'Asset location is determined by group membership log.');
    $this->assertEquals($this->logLocation->getGeometry($third_log), $this->assetLocation->getGeometry($animal), 'Asset geometry is determined by group membership log.');
    $this->assertCorrectAssets([], $this->assetLocation->getAssetsByLocation([$first_pasture]), TRUE, 'A group movement removes assets from their previous location.');
    $this->assertCorrectAssets([$animal, $group], $this->assetLocation->getAssetsByLocation([$second_pasture]), TRUE, 'A group movement adds assets to their new location.');

    // Assert that the animal's cache tags were invalidated.
    $this->assertEntityTestCache($animal, FALSE);
  }

}
