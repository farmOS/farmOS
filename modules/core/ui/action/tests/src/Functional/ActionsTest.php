<?php

declare(strict_types=1);

namespace Drupal\Tests\farm_ui_action\Functional;

use Drupal\Tests\farm_test\Functional\FarmBrowserTestBase;
use Drupal\asset\Entity\Asset;
use Drupal\log\Entity\Log;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * Tests the farmOS action functionality.
 */
#[Group('farm')]
#[RunTestsInSeparateProcesses]
class ActionsTest extends FarmBrowserTestBase {

  /**
   * Test user.
   *
   * @var \Drupal\user\UserInterface|bool
   */
  protected $user;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'farm_ui_action',
    'farm_ui_action_test',
    'farm_ui_dashboard',
    'farm_ui_views',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    // Add the local actions block.
    $this->drupalPlaceBlock('local_actions_block');

    // Create and login a user with necessary permissions.
    $this->user = $this->createUser([
      'access asset collection',
      'access farm dashboard',
      'access log collection',
      'access organization collection',
      'access plan collection',
      'create test asset',
      'create test log',
      'create test organization',
      'create test plan',
      'view any asset',
      'view any log',
      'view any organization',
      'view any plan',
    ]);
    $this->drupalLogin($this->user);
  }

  /**
   * Test that action buttons are added.
   */
  public function testActionButtons() {

    // Test dashboard buttons.
    $this->drupalGet('/dashboard');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertActionLinkExists('Add Asset', '/asset/add');
    $this->assertActionLinkExists('Add Log', '/log/add');
    $this->assertActionLinkExists('Add Organization', '/organization/add');
    $this->assertActionLinkExists('Add Plan', '/plan/add');

    // Test entity lists.
    $this->drupalGet('/assets');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertActionLinkExists('Add Asset', '/asset/add');
    $this->drupalGet('/logs');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertActionLinkExists('Add Log', '/log/add');
    $this->drupalGet('/organizations');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertActionLinkExists('Add Organization', '/organization/add');
    $this->drupalGet('/plans');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertActionLinkExists('Add Plan', '/plan/add');

    // Test per-bundle entity lists.
    $this->drupalGet('/assets/test');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertActionLinkExists('Add Asset: Test', '/asset/add/test');
    $this->drupalGet('/logs/test');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertActionLinkExists('Add Log: Test', '/log/add/test');
    $this->drupalGet('/organizations/test');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertActionLinkExists('Add Organization: Test', '/organization/add/test');
    $this->drupalGet('/plans/test');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertActionLinkExists('Add Plan: Test', '/plan/add/test');

    // Test /user/%uid/logs.
    $this->drupalGet('/user/' . $this->user->id() . '/logs');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertActionLinkExists('Add Log', '/log/add');

    // Test links to /log/add/[bundle]?asset=[id] on asset pages.
    /** @var \Drupal\asset\Entity\AssetInterface $asset */
    $asset = Asset::create([
      'type' => 'test',
      'name' => $this->randomMachineName(),
    ]);
    $asset->save();
    /** @var \Drupal\log\Entity\LogInterface $log */
    $log = Log::create([
      'type' => 'test',
      'asset' => [$asset],
    ]);
    $log->save();
    $this->drupalGet('/asset/' . $asset->id());
    $this->assertSession()->statusCodeEquals(200);
    $this->assertActionLinkExists('Add Log: Test', '/log/add/test?asset=' . $asset->id());
    $this->drupalGet('/asset/' . $asset->id() . '/logs');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertActionLinkExists('Add Log: Test', '/log/add/test?asset=' . $asset->id());
    $this->drupalGet('/asset/' . $asset->id() . '/logs/test');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertActionLinkExists('Add Log: Test', '/log/add/test?asset=' . $asset->id());
  }

  /**
   * Helper method to test that an action link exists.
   *
   * @param string $label
   *   The action link label.
   * @param string $href
   *   The action link href.
   */
  protected function assertActionLinkExists(string $label, string $href): void {
    $this->assertSession()->linkExists($label);
    $this->assertSession()->linkByHrefExists($href);
  }

}
