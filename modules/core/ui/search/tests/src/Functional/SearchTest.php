<?php

declare(strict_types=1);

namespace Drupal\Tests\farm_ui_search\Functional;

use Drupal\Tests\farm_test\Functional\FarmBrowserTestBase;
use Drupal\asset\Entity\Asset;
use Drupal\log\Entity\Log;
use Drupal\search_api\Entity\Index;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * Tests the farm_ui_search dashboard panes.
 */
#[Group('farm')]
#[RunTestsInSeparateProcesses]
class SearchTest extends FarmBrowserTestBase {

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
   * Assets created for testing.
   *
   * @var \Drupal\asset\Entity\AssetInterface[]
   */
  protected array $assets;

  /**
   * Logs created for testing.
   *
   * @var \Drupal\log\Entity\LogInterface[]
   */
  protected array $logs;

  /**
   * Asset and log names.
   *
   * @var string[]
   */
  protected array $recordNames;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'farm_activity',
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

    // Create a role that has permission to view assets and logs.
    $this->role = $this->drupalCreateRole(['access farm dashboard', 'access asset collection', 'view any asset', 'access log collection', 'view any log']);

    // Create assets.
    $asset_names = [
      'Red Tractor',
      'Root Washer',
    ];
    foreach ($asset_names as $name) {
      $asset = Asset::create([
        'name' => $name,
        'type' => 'equipment',
      ]);
      $asset->save();
      $this->assets[] = $asset;
    }

    // Create logs.
    $log_names = [
      'Wash tractor',
      'Harvest potatoes',
    ];
    foreach ($log_names as $name) {
      $log = Log::create([
        'name' => $name,
        'type' => 'activity',
      ]);
      $log->save();
      $this->logs[] = $log;
    }

    // Make a list of all asset and log names.
    foreach ($this->assets as $asset) {
      $this->recordNames[] = $asset->label();
    }
    foreach ($this->logs as $log) {
      $this->recordNames[] = $log->label();
    }

    // Populate the index.
    $index = Index::load('default');
    $index->indexItems();
  }

  /**
   * Test the search form on the dashboard.
   */
  public function testDashboardSearch() {

    // Load the dashboard.
    $this->drupalGet('/dashboard');
    $this->assertSession()->statusCodeEquals(200);

    // Assert that the search form was not added.
    $this->assertSession()->responseNotContains('Search assets and logs');

    // Grant the user permissions to view assets and logs.
    $this->user->addRole($this->role);
    $this->user->save();

    // Assert that the search form was added.
    $this->drupalGet('/dashboard');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->responseContains('Search assets and logs');

    // Search for "tractor" and assert that expected results are shown.
    $this->assertExpectedSearchResults('tractor', ['Red Tractor', 'Wash tractor']);

    // Search for "wash" and assert that expected results are shown.
    $this->assertExpectedSearchResults('wash', ['Root Washer', 'Wash tractor']);

    // Search for "potato" and assert that expected results are shown.
    $this->assertExpectedSearchResults('potato', ['Harvest potatoes']);
  }

  /**
   * Assert that only expected results are present in a search.
   *
   * @param string $query
   *   The search query string.
   * @param array $expected_names
   *   An array of asset and log names we expect to find in the search results.
   */
  protected function assertExpectedSearchResults(string $query, array $expected_names = []) {

    // Load the dashboard and search for the query string.
    $this->drupalGet('/dashboard');
    $this->assertSession()->statusCodeEquals(200);
    $this->getSession()->getPage()->fillField('query', $query);
    $this->getSession()->getPage()->pressButton('Search');
    $this->assertSession()->addressEquals('search?query=' . $query);

    // Assert that all the expected names were found.
    foreach ($expected_names as $name) {
      $this->assertSession()->pageTextContains($name);
    }

    // Assert that no unexpected names were found.
    $unexpected_names = array_diff($this->recordNames, $expected_names);
    foreach ($unexpected_names as $name) {
      $this->assertSession()->pageTextNotContains($name);
    }
  }

}
