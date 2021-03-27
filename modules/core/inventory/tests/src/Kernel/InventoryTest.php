<?php

namespace Drupal\Tests\farm_inventory\Kernel;

use Drupal\asset\Entity\Asset;
use Drupal\asset\Entity\AssetInterface;
use Drupal\fraction\Fraction;
use Drupal\KernelTests\KernelTestBase;
use Drupal\log\Entity\Log;
use Drupal\quantity\Entity\Quantity;
use Drupal\taxonomy\Entity\Term;

/**
 * Tests for farmOS inventory logic.
 *
 * @group farm
 */
class InventoryTest extends KernelTestBase {

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
    'farm_quantity_standard',
    'farm_unit',
    'fraction',
    'geofield',
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
      'farm_quantity_standard',
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

    // When an asset has no adjustment logs, it has no inventory.
    $inventory = $this->assetInventory->getInventory($asset);
    $this->assertEmpty($inventory, 'New assets do not have inventory.');

    // Reset the asset's inventory to 1.
    $this->adjustInventory($asset, 'reset', '1');

    // Confirm that the asset now has inventory.
    $inventory = $this->assetInventory->getInventory($asset);
    $this->assertNotEmpty($inventory, 'Asset with an inventory adjustment has inventory.');
    $this->assertEquals('1', $inventory[0]['value'], 'Asset inventory is 1.');

    // Increment the inventory by 5.
    $this->adjustInventory($asset, 'increment', '5');
    $inventory = $this->assetInventory->getInventory($asset);
    $this->assertEquals('6', $inventory[0]['value'], 'Asset inventory is 6.');

    // Decrement the inventory by 1.
    $this->adjustInventory($asset, 'decrement', '1');
    $inventory = $this->assetInventory->getInventory($asset);
    $this->assertEquals('5', $inventory[0]['value'], 'Asset inventory is 5.');

    // Reset the inventory back to zero.
    $this->adjustInventory($asset, 'reset', '0');
    $inventory = $this->assetInventory->getInventory($asset);
    $this->assertEquals('0', $inventory[0]['value'], 'Asset inventory is 0.');

    // Add a pending adjustment, and confirm that it does not affect the current
    // inventory.
    $log = $this->adjustInventory($asset, 'increment', '1');
    $log->set('status', 'pending');
    $log->save();
    $inventory = $this->assetInventory->getInventory($asset);
    $this->assertEquals('0', $inventory[0]['value'], 'Pending adjustments do not affect inventory.');

    // Add an adjustment in the future, and confirm that it does not affect
    // the current inventory.
    $log = $this->adjustInventory($asset, 'increment', '1');
    $log->set('timestamp', \Drupal::time()->getRequestTime() + 86400);
    $log->save();
    $inventory = $this->assetInventory->getInventory($asset);
    $this->assertEquals('0', $inventory[0]['value'], 'Future adjustments do not affect inventory.');

    // Reset to a decimal inventory.
    $this->adjustInventory($asset, 'reset', '1.1');
    $inventory = $this->assetInventory->getInventory($asset);
    $this->assertEquals('1.1', $inventory[0]['value'], 'Asset inventory is 1.1.');

    // Increment by a decimal value.
    $this->adjustInventory($asset, 'increment', '1.4');
    $inventory = $this->assetInventory->getInventory($asset);
    $this->assertEquals('2.5', $inventory[0]['value'], 'Asset inventory is 2.5.');

    // Test floating point arithmetic precision.
    $this->adjustInventory($asset, 'reset', '0.1');
    $this->adjustInventory($asset, 'increment', '0.2');
    $inventory = $this->assetInventory->getInventory($asset);
    $this->assertEquals('0.3', $inventory[0]['value'], 'Inventory calculations handle floating point arithmetic properly.');
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
   *
   * @return \Drupal\log\Entity\LogInterface
   *   The log entity.
   */
  protected function adjustInventory(AssetInterface $asset, string $adjustment, string $value, string $measure = '', int $units = 0) {
    $fraction = Fraction::createFromDecimal($value);
    /** @var \Drupal\quantity\Entity\Quantity $quantity */
    $quantity = Quantity::create([
      'type' => 'standard',
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
    /** @var \Drupal\log\Entity\LogInterface $log */
    $log = Log::create([
      'type' => 'adjustment',
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
