<?php

namespace Drupal\Tests\farm_inventory\Kernel;

use Drupal\asset\Entity\Asset;
use Drupal\asset\Entity\AssetInterface;
use Drupal\fraction\Fraction;
use Drupal\KernelTests\KernelTestBase;
use Drupal\log\Entity\Log;
use Drupal\quantity\Entity\Quantity;
use Drupal\taxonomy\Entity\Term;
use Drupal\Tests\farm_test\Kernel\FarmEntityCacheTestTrait;

/**
 * Tests for farmOS inventory logic.
 *
 * @group farm
 */
class InventoryTest extends KernelTestBase {

  use FarmEntityCacheTestTrait;

  /**
   * Asset inventory service.
   *
   * @var \Drupal\farm_inventory\AssetInventoryInterface
   */
  protected $assetInventory;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'asset',
    'entity_reference_revisions',
    'farm_field',
    'farm_inventory',
    'farm_inventory_test',
    'farm_log',
    'farm_log_quantity',
    'farm_unit',
    'fraction',
    'log',
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
    $this->assetInventory = \Drupal::service('asset.inventory');
    $this->installEntitySchema('asset');
    $this->installEntitySchema('log');
    $this->installEntitySchema('user');
    $this->installEntitySchema('quantity');
    $this->installEntitySchema('taxonomy_term');
    $this->installConfig([
      'farm_inventory_test',
    ]);
  }

  /**
   * Test simple asset inventory.
   */
  public function testSimpleAssetInventory() {

    // Create a new asset.
    /** @var \Drupal\asset\Entity\AssetInterface $asset */
    $asset = Asset::create([
      'type' => 'container',
      'name' => $this->randomMachineName(),
      'status' => 'active',
    ]);
    $asset->save();

    // Populate a cache value dependent on the asset's cache tags.
    $this->populateEntityTestCache($asset);

    // When an asset has no adjustment logs, it has no inventory.
    $inventory = $this->assetInventory->getInventory($asset);
    $this->assertEmpty($inventory, 'New assets do not have inventory.');

    // Assert that the asset's cache tags were not invalidated.
    $this->assertEntityTestCache($asset, TRUE);

    // Reset the asset's inventory to 1.
    $this->adjustInventory($asset, 'reset', '1');

    // Confirm that the asset now has inventory.
    $inventory = $this->assetInventory->getInventory($asset);
    $this->assertNotEmpty($inventory, 'Asset with an inventory adjustment has inventory.');
    $this->assertEquals('1', $inventory[0]['value'], 'Asset inventory is 1.');

    // Assert that the asset's cache tags were invalidated.
    $this->assertEntityTestCache($asset, FALSE);

    // Re-populate a cache value dependent on the asset's cache tags.
    $this->populateEntityTestCache($asset);

    // Increment the inventory by 5.
    $this->adjustInventory($asset, 'increment', '5');
    $inventory = $this->assetInventory->getInventory($asset);
    $this->assertEquals('6', $inventory[0]['value'], 'Asset inventory is 6.');

    // Assert that the asset's cache tags were invalidated.
    $this->assertEntityTestCache($asset, FALSE);

    // Re-populate a cache value dependent on the asset's cache tags.
    $this->populateEntityTestCache($asset);

    // Decrement the inventory by 1.
    $this->adjustInventory($asset, 'decrement', '1');
    $inventory = $this->assetInventory->getInventory($asset);
    $this->assertEquals('5', $inventory[0]['value'], 'Asset inventory is 5.');

    // Assert that the asset's cache tags were invalidated.
    $this->assertEntityTestCache($asset, FALSE);

    // Re-populate a cache value dependent on the asset's cache tags.
    $this->populateEntityTestCache($asset);

    // Reset the inventory back to zero.
    $this->adjustInventory($asset, 'reset', '0');
    $inventory = $this->assetInventory->getInventory($asset);
    $this->assertEquals('0', $inventory[0]['value'], 'Asset inventory is 0.');

    // Assert that the asset's cache tags were invalidated.
    $this->assertEntityTestCache($asset, FALSE);

    // Add a pending adjustment, and confirm that it does not affect the current
    // inventory.
    $log = $this->adjustInventory($asset, 'increment', '1');
    $log->set('status', 'pending');
    $log->save();

    // Re-populate a cache value dependent on the asset's cache tags.
    $this->populateEntityTestCache($asset);
    $log->save();

    $inventory = $this->assetInventory->getInventory($asset);
    $this->assertEquals('0', $inventory[0]['value'], 'Pending adjustments do not affect inventory.');

    // Assert that the asset's cache tags were not invalidated.
    $this->assertEntityTestCache($asset, TRUE);

    // Add an adjustment in the future, and confirm that it does not affect
    // the current inventory.
    $log = $this->adjustInventory($asset, 'increment', '1');
    $log->set('timestamp', \Drupal::time()->getRequestTime() + 86400);
    $log->save();

    // Re-populate a cache value dependent on the asset's cache tags.
    $this->populateEntityTestCache($asset);
    $log->save();

    $inventory = $this->assetInventory->getInventory($asset);
    $this->assertEquals('0', $inventory[0]['value'], 'Future adjustments do not affect inventory.');

    // Assert that the asset's cache tags were not invalidated.
    $this->assertEntityTestCache($asset, TRUE);

    // Reset to a decimal inventory.
    $this->adjustInventory($asset, 'reset', '1.1');
    $inventory = $this->assetInventory->getInventory($asset);
    $this->assertEquals('1.1', $inventory[0]['value'], 'Asset inventory is 1.1.');

    // Assert that the asset's cache tags were invalidated.
    $this->assertEntityTestCache($asset, FALSE);

    // Re-populate a cache value dependent on the asset's cache tags.
    $this->populateEntityTestCache($asset);

    // Increment by a decimal value.
    $this->adjustInventory($asset, 'increment', '1.4');
    $inventory = $this->assetInventory->getInventory($asset);
    $this->assertEquals('2.5', $inventory[0]['value'], 'Asset inventory is 2.5.');

    // Assert that the asset's cache tags were invalidated.
    $this->assertEntityTestCache($asset, FALSE);

    // Re-populate a cache value dependent on the asset's cache tags.
    $this->populateEntityTestCache($asset);

    // Test floating point arithmetic precision.
    $this->adjustInventory($asset, 'reset', '0.1');
    $log = $this->adjustInventory($asset, 'increment', '0.2');
    $inventory = $this->assetInventory->getInventory($asset);
    $this->assertEquals('0.3', $inventory[0]['value'], 'Inventory calculations handle floating point arithmetic properly.');

    // Assert that the asset's cache tags were invalidated.
    $this->assertEntityTestCache($asset, FALSE);

    // Re-populate a cache value dependent on the asset's cache tags.
    $this->populateEntityTestCache($asset);

    // Delete the last increment adjustment to use the last reset adjustment.
    $log->delete();
    $inventory = $this->assetInventory->getInventory($asset);
    $this->assertEquals('0.1', $inventory[0]['value'], 'Asset inventory is updated when a log is deleted.');

    // Assert that the asset's cache tags were invalidated.
    $this->assertEntityTestCache($asset, FALSE);
  }

  /**
   * Test multiple asset inventories with measure+units pairs.
   */
  public function testMultipleAssetInventory() {

    // Create a new asset.
    /** @var \Drupal\asset\Entity\AssetInterface $asset */
    $asset = Asset::create([
      'type' => 'container',
      'name' => $this->randomMachineName(),
      'status' => 'active',
    ]);
    $asset->save();

    // Create two units terms.
    /** @var \Drupal\taxonomy\Entity\Term $unit1 */
    $unit1 = Term::create([
      'name' => 'liters',
      'vid' => 'unit',
    ]);
    $unit1->save();
    /** @var \Drupal\taxonomy\Entity\Term $unit2 */
    $unit2 = Term::create([
      'name' => 'hours',
      'vid' => 'unit',
    ]);
    $unit2->save();

    // Reset the asset's volume (liters) inventory to 1.
    $this->adjustInventory($asset, 'reset', '1', 'volume', $unit1->id());

    // Confirm that the asset has one inventory, with a measure of "volume",
    // units in "liters", and a value of 1.
    $inventory = $this->assetInventory->getInventory($asset);
    $this->assertEquals(1, count($inventory), 'Asset has a single inventory.');
    $this->assertEquals('volume', $inventory[0]['measure'], 'Asset inventory has a measure of volume.');
    $this->assertEquals('liters', $inventory[0]['units'], 'Asset inventory has units in liters.');
    $this->assertEquals('1', $inventory[0]['value'], 'Asset inventory is 1.');

    // Reset the asset's time (hours) inventory to 2.
    $this->adjustInventory($asset, 'reset', '2', 'time', $unit2->id());

    // Confirm that the asset now has two inventories.
    $inventory = $this->assetInventory->getInventory($asset);
    $this->assertEquals(2, count($inventory), 'Asset has a single inventory.');

    // Load the time (hours) inventory and confirm that it is 2.
    $inventory = $this->assetInventory->getInventory($asset, 'time', $unit2->id());
    $this->assertEquals('time', $inventory[0]['measure'], 'Asset inventory has a measure of time.');
    $this->assertEquals('hours', $inventory[0]['units'], 'Asset inventory has units in hours.');
    $this->assertEquals('2', $inventory[0]['value'], 'Asset inventory is 2.');

    // Load the volume (liters) inventory and confirm that it is still 1.
    $inventory = $this->assetInventory->getInventory($asset, 'volume', $unit1->id());
    $this->assertEquals('volume', $inventory[0]['measure'], 'Asset inventory has a measure of volume.');
    $this->assertEquals('liters', $inventory[0]['units'], 'Asset inventory has units in liters.');
    $this->assertEquals('1', $inventory[0]['value'], 'Asset inventory is 1.');

    // Load all volume inventories (without specifying units) and confirm that
    // one inventory is returned.
    $inventory = $this->assetInventory->getInventory($asset, 'volume', 0);
    $this->assertEquals(1, count($inventory), 'Asset has a single volume inventory.');

    // Load all liters inventories (without specifying measure) and confirm that
    // one inventory is returned.
    $inventory = $this->assetInventory->getInventory($asset, '', $unit2->id());
    $this->assertEquals(1, count($inventory), 'Asset has a single liters inventory.');

    // Test incrementing the volume (liters) inventory.
    $this->adjustInventory($asset, 'increment', '4', 'volume', $unit1->id());
    $inventory = $this->assetInventory->getInventory($asset, 'volume', $unit1->id());
    $this->assertEquals('5', $inventory[0]['value'], 'Asset inventory is 5.');
  }

  /**
   * Test past/future asset inventory.
   */
  public function testAssetInventoryTimestamp() {

    // Create a new asset.
    /** @var \Drupal\asset\Entity\AssetInterface $asset */
    $asset = Asset::create([
      'type' => 'container',
      'name' => $this->randomMachineName(),
      'status' => 'active',
    ]);
    $asset->save();

    // Create a series of timestamps, each 24 hours apart.
    $now = \Drupal::time()->getRequestTime();
    $timestamps = [];
    for ($i = 0; $i < 3; $i++) {
      $timestamps[$i] = $now + (86400 * $i);
    }

    // Create a series of inventory adjustment logs.
    $this->adjustInventory($asset, 'reset', 1, '', 0, $timestamps[0]);
    $this->adjustInventory($asset, 'increment', 99, '', 0, $timestamps[1]);
    $this->adjustInventory($asset, 'decrement', 10, '', 0, $timestamps[2]);

    // Confirm that the inventory is zero before all adjustments.
    $timestamp = $now - 86400;
    $inventory = $this->assetInventory->getInventory($asset, '', 0, $timestamp);
    $this->assertEquals('0', $inventory[0]['value']);

    // Confirm that the inventory is what we expect at each timestamp.
    $inventory = $this->assetInventory->getInventory($asset, '', 0, $timestamps[0]);
    $this->assertEquals('1', $inventory[0]['value']);
    $inventory = $this->assetInventory->getInventory($asset, '', 0, $timestamps[1]);
    $this->assertEquals('100', $inventory[0]['value']);
    $inventory = $this->assetInventory->getInventory($asset, '', 0, $timestamps[2]);
    $this->assertEquals('90', $inventory[0]['value']);

    // Confirm that the inventory is the same one second later.
    // This tests the <= operator.
    $inventory = $this->assetInventory->getInventory($asset, '', 0, $timestamps[2] + 1);
    $this->assertEquals('90', $inventory[0]['value']);
  }

  /**
   * Creates an inventory adjustment quantity + log for a given asset.
   *
   * @param \Drupal\asset\Entity\AssetInterface $asset
   *   The asset to adjust inventory for.
   * @param string $adjustment
   *   The type of adjustment ('reset', 'increment', or 'decrement').
   * @param string $value
   *   The value of the adjustment.
   * @param string $measure
   *   The quantity measure of the inventory. See quantity_measures().
   * @param int $units
   *   The quantity units of the inventory (term ID).
   * @param int|null $timestamp
   *   Optionally specify the timestamp when the adjustment occured.
   *   If this is NULL (default), the current time will be used.
   *
   * @return \Drupal\log\Entity\LogInterface
   *   The log entity.
   */
  protected function adjustInventory(AssetInterface $asset, string $adjustment, string $value, string $measure = '', int $units = 0, $timestamp = NULL) {
    $fraction = Fraction::createFromDecimal($value);
    /** @var \Drupal\quantity\Entity\Quantity $quantity */
    $quantity = Quantity::create([
      'type' => 'test',
      'value' => [
        'numerator' => $fraction->getNumerator(),
        'denominator' => $fraction->getDenominator(),
      ],
      'inventory_adjustment' => $adjustment,
      'inventory_asset' => ['target_id' => $asset->id()],
    ]);
    if (!empty($measure)) {
      $quantity->set('measure', $measure);
    }
    if (!empty($units)) {
      $quantity->set('units', ['target_id' => $units]);
    }
    $quantity->save();
    if (is_null($timestamp)) {
      $timestamp = \Drupal::time()->getRequestTime();
    }
    /** @var \Drupal\log\Entity\LogInterface $log */
    $log = Log::create([
      'type' => 'adjustment',
      'timestamp' => $timestamp,
      'status' => 'done',
      'quantity' => [
        'target_id' => $quantity->id(),
        'target_revision_id' => $quantity->getRevisionId(),
      ],
    ]);
    $log->save();
    return $log;
  }

}
