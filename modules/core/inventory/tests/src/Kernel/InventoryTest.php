<?php

namespace Drupal\Tests\farm_inventory\Kernel;

use Drupal\asset\Entity\Asset;
use Drupal\asset\Entity\AssetInterface;
use Drupal\fraction\Fraction;
use Drupal\KernelTests\KernelTestBase;
use Drupal\log\Entity\Log;
use Drupal\quantity\Entity\Quantity;

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
   * Creates an inventory adjustment quantity + log for a given asset.
   *
   * @param \Drupal\asset\Entity\AssetInterface $asset
   *   The asset to adjust inventory for.
   * @param string $adjustment
   *   The type of adjustment ('reset', 'increment', or 'decrement').
   * @param string $value
   *   The value of the adjustment.
   *
   * @return \Drupal\log\Entity\LogInterface
   *   The log entity.
   */
  protected function adjustInventory(AssetInterface $asset, string $adjustment, string $value) {
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
