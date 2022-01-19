<?php

namespace Drupal\Tests\farm_ui_views\Functional;

use Drupal\asset\Entity\Asset;
use Drupal\Tests\farm_test\Functional\FarmBrowserTestBase;

/**
 * Tests the farm_ui_views Views.
 *
 * @group farm
 */
class FarmUiViewsTest extends FarmBrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'farm_equipment',
    'farm_water',
    'farm_ui_views',
  ];

  /**
   * Test Views provided by the farm_ui_views module.
   */
  public function testFarmUiViews() {

    // Create and login a user with permission to view assets.
    $user = $this->createUser(['view any asset']);
    $this->drupalLogin($user);

    // Create two assets of different types.
    $equipment = Asset::create([
      'name' => 'Equipment asset',
      'type' => 'equipment',
      'status' => 'active',
    ]);
    $equipment->save();
    $water = Asset::create([
      'name' => 'Water asset',
      'type' => 'water',
      'status' => 'active',
    ]);
    $water->save();

    // Check that both assets are visible in /assets.
    $this->drupalGet('/assets');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains($equipment->label());
    $this->assertSession()->pageTextContains($water->label());

    // Check that an "Export CSV" link appears on /assets.
    $this->assertSession()->pageTextContains('Export CSV');

    // Check that the "Export CSV" link includes exposed filters.
    $this->drupalGet('/assets', ['query' => ['status' => 'active']]);
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->linkByHrefExists('/assets.csv?status=active');

    // Check that both assets are visible in /assets.csv.
    $this->drupalGet('/assets.csv');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains($equipment->label());
    $this->assertSession()->pageTextContains($water->label());

    // Check that only water assets are visible in /assets/water.
    $this->drupalGet('/assets/water');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextNotContains($equipment->label());
    $this->assertSession()->pageTextContains($water->label());

    // Check that an "Export CSV" link appears on /assets/water, and it
    // automatically filters by asset type.
    $this->assertSession()->pageTextContains('Export CSV');
    $this->assertSession()->linkByHrefExists('/assets.csv?type%5B0%5D=water');

    // Check that only water assets are visible in /assets.csv?type[]=water.
    $this->drupalGet('/assets.csv', ['query' => ['type' => ['water']]]);
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextNotContains($equipment->label());
    $this->assertSession()->pageTextContains($water->label());

    // Check that /assets/equipment includes equipment-specific columns.
    $this->drupalGet('/assets/equipment');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains('Manufacturer');
    $this->assertSession()->pageTextContains('Model');
    $this->assertSession()->pageTextContains('Serial number');

    // Check that /assets.csv?type[]=equipment includes equipment-specific
    // columns.
    $this->drupalGet('/assets.csv', ['query' => ['type' => ['equipment']]]);
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains('Manufacturer');
    $this->assertSession()->pageTextContains('Model');
    $this->assertSession()->pageTextContains('Serial number');
  }

}
