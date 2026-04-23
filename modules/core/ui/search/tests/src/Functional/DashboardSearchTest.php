<?php

declare(strict_types=1);

namespace Drupal\Tests\farm_ui_search\Functional;

use Drupal\Tests\farm_test\Functional\FarmBrowserTestBase;
use Drupal\asset\Entity\Asset;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * Tests the farm_ui_search dashboard panes.
 */
#[Group('farm')]
#[RunTestsInSeparateProcesses]
class DashboardSearchTest extends FarmBrowserTestBase {

  /**
   * Test user.
   *
   * @var \Drupal\user\Entity\User|bool
   */
  protected $user;

  /**
   * Test role ID.
   *
   * @var string
   */
  protected $role;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'farm_equipment',
    'farm_ui_dashboard',
    'farm_ui_search',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    // Create and login a user with permission to access the dashboard.
    $this->user = $this->createUser(['access farm dashboard']);
    $this->drupalLogin($this->user);

    // Create a role that has permission to view assets.
    $this->role = $this->drupalCreateRole(['access farm dashboard', 'access asset collection', 'view any asset']);

    // Create two equipment assets.
    $asset1 = Asset::create([
      'name' => 'Foo',
      'type' => 'equipment',
    ]);
    $asset1->save();
    $asset2 = Asset::create([
      'name' => 'Bar',
      'type' => 'equipment',
    ]);
    $asset2->save();
  }

  /**
   * Test the search form on the dashboard.
   */
  public function testDashboardSearch() {

    // Load the dashboard.
    $this->drupalGet('/dashboard');
    $this->assertSession()->statusCodeEquals(200);

    // Assert that the asset search form was not added.
    $this->assertSession()->pageTextNotContains('Search assets');

    // Grant the user permission to view any asset.
    $this->user->addRole($this->role);
    $this->user->save();

    // Assert that the asset search was added.
    $this->drupalGet('/dashboard');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains('Search assets');

    // Search for "foo".
    $this->getSession()->getPage()->fillField('asset_search', 'foo');
    $this->getSession()->getPage()->pressButton('Search');

    // Assert that we are redirected to /assets, "Foo" is listed, but "Bar" is
    // not.
    $this->assertSession()->addressEquals('assets?name=foo');
    $this->assertSession()->pageTextContains('Foo');
    $this->assertSession()->pageTextNotContains('Bar');

    // Search for "bar".
    // Assert that we are redirected to /assets, "Bar" is listed, but "Bar" is
    // not.
    $this->drupalGet('/dashboard');
    $this->assertSession()->statusCodeEquals(200);
    $this->getSession()->getPage()->fillField('asset_search', 'bar');
    $this->getSession()->getPage()->pressButton('Search');
    $this->assertSession()->addressEquals('assets?name=bar');
    $this->assertSession()->pageTextNotContains('Foo');
    $this->assertSession()->pageTextContains('Bar');
  }

}
