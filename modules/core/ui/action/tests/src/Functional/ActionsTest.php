<?php

declare(strict_types=1);

namespace Drupal\Tests\farm_ui_action\Functional;

use Drupal\Tests\farm_test\Functional\FarmBrowserTestBase;
use Drupal\asset\Entity\Asset;
use Drupal\log\Entity\Log;
use Drupal\system\Entity\Action;
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
   * @var \Drupal\user\Entity\User|bool
   */
  protected $user;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'farm_log_asset',
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

    // Create an asset to test entity action plugins.
    /** @var \Drupal\asset\Entity\AssetInterface $asset */
    $asset = Asset::create([
      'type' => 'test',
      'name' => $this->randomMachineName(),
    ]);
    $asset->save();

    // Test that none of the test actions are visible on /asset/%asset.
    $this->drupalGet('/asset/' . $asset->id());
    $this->assertSession()->statusCodeEquals(200);
    $this->assertActionLinkNotExists('Test action', '/asset/' . $asset->id() . '/action/test_action');
    $this->assertActionLinkNotExists('Test action confirm', '/asset/' . $asset->id() . '/action/test_action_confirm');
    $this->assertActionLinkNotExists('Test action alter', '/asset/' . $asset->id() . '/action/test_action_alter');

    // Install the farm_ui_action_hook_test module to expose and alter actions.
    \Drupal::service('module_installer')->install(['farm_ui_action_hook_test']);

    // Test that actions are exposed as expected.
    $this->drupalGet('/asset/' . $asset->id());
    $this->assertSession()->statusCodeEquals(200);
    $this->assertActionLinkExists('Test action', '/asset/' . $asset->id() . '/action/test_action');
    $this->assertActionLinkExists('Test action confirm', '/asset/' . $asset->id() . '/action/test_action_confirm');

    // Test that the action that was altered out is not exposed.
    $this->assertActionLinkNotExists('Test action alter', '/asset/' . $asset->id() . '/action/test_action_alter');

    // Test that executing a simple test action works as expected and redirects
    // back to the asset.
    $base_url = 'http://www/';
    $this->drupalGet('/asset/' . $asset->id() . '/action/test_action');
    $this->assertEquals($base_url . 'asset/' . $asset->id(), $this->getSession()->getCurrentUrl());
    $asset = \Drupal::entityTypeManager()->getStorage('asset')->load($asset->id());
    $this->assertEquals(TRUE, $asset->get('archived')->value);

    // Test that actions are no longer visible, because access is based on
    // archived status of the asset.
    $this->drupalGet('/asset/' . $asset->id());
    $this->assertSession()->statusCodeEquals(200);
    $this->assertActionLinkNotExists('Test action', '/asset/' . $asset->id() . '/action/test_action');
    $this->assertActionLinkNotExists('Test action confirm', '/asset/' . $asset->id() . '/action/test_action_confirm');

    // Unarchive the asset and test that actions are visible again.
    $asset->set('archived', FALSE);
    $asset->save();
    $this->drupalGet('/asset/' . $asset->id());
    $this->assertSession()->statusCodeEquals(200);
    $this->assertActionLinkExists('Test action', '/asset/' . $asset->id() . '/action/test_action');
    $this->assertActionLinkExists('Test action confirm', '/asset/' . $asset->id() . '/action/test_action_confirm');

    // Confirm that creating an action configuration entity adds an action link.
    // Note that this action ID is exposed by farm_ui_action_hook_test.
    $this->drupalGet('/asset/' . $asset->id());
    $this->assertSession()->statusCodeEquals(200);
    $this->assertActionLinkNotExists('Test action create', '/asset/' . $asset->id() . '/action/test_action_create');
    $action = Action::create([
      'id' => 'test_action_create',
      'label' => 'Test action create',
      'type' => 'asset',
      'plugin' => 'test_action',
    ]);
    $action->save();
    $this->drupalGet('/asset/' . $asset->id());
    $this->assertSession()->statusCodeEquals(200);
    $this->assertActionLinkExists('Test action create', '/asset/' . $asset->id() . '/action/test_action_create');

    // Confirm that deleting an action configuration entity removes its action
    // link.
    $action->delete();
    $this->drupalGet('/asset/' . $asset->id());
    $this->assertSession()->statusCodeEquals(200);
    $this->assertActionLinkNotExists('Test action create', '/asset/' . $asset->id() . '/action/test_action_create');

    // Test that executing an action with a confirmation form works as expected
    // and redirects back to the asset.
    $this->drupalGet('/asset/' . $asset->id() . '/action/test_action_confirm');
    $this->assertEquals($base_url . 'asset/test_action_confirm?destination=/asset/1', $this->getSession()->getCurrentUrl());
    $this->getSession()->getPage()->pressButton('Confirm');
    $this->assertEquals($base_url . 'asset/' . $asset->id(), $this->getSession()->getCurrentUrl());
    $asset = \Drupal::entityTypeManager()->getStorage('asset')->load($asset->id());
    $this->assertEquals(TRUE, $asset->get('archived')->value);

    // Create a log that references the asset so that /asset/%asset/logs and
    // /asset/%asset/logs/%log_type are available.
    /** @var \Drupal\log\Entity\LogInterface $log */
    $log = Log::create([
      'type' => 'test',
      'asset' => [$asset],
    ]);
    $log->save();

    // Test that the "Add log" action is not visible on /asset/%asset,
    // /asset/%asset/logs and /asset/%asset/logs/%log_type.
    $this->drupalGet('/asset/' . $asset->id());
    $this->assertSession()->statusCodeEquals(200);
    $this->assertActionLinkNotExists('Add log', '/asset/' . $asset->id() . '/action/asset_add_log_action');
    $this->drupalGet('/asset/' . $asset->id() . '/logs');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertActionLinkNotExists('Add log', '/asset/' . $asset->id() . '/action/asset_add_log_action');
    $this->drupalGet('/asset/' . $asset->id() . '/logs/test');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertActionLinkNotExists('Add log', '/asset/' . $asset->id() . '/action/asset_add_log_action');

    // Grant the "update any test asset" permission to the user so that they
    // can access the asset_add_log_action action.
    $permissions[] = 'update any test asset';
    $user = $this->createUser($permissions);
    $this->drupalLogin($user);

    // Test that the "Add log" action is now visible on /asset/%asset,
    // /asset/%asset/logs and /asset/%asset/logs/%log_type.
    $this->drupalGet('/asset/' . $asset->id());
    $this->assertSession()->statusCodeEquals(200);
    $this->assertActionLinkExists('Add log', '/asset/' . $asset->id() . '/action/asset_add_log_action');
    $this->drupalGet('/asset/' . $asset->id() . '/logs');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertActionLinkExists('Add log', '/asset/' . $asset->id() . '/action/asset_add_log_action');
    $this->drupalGet('/asset/' . $asset->id() . '/logs/test');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertActionLinkExists('Add log', '/asset/' . $asset->id() . '/action/asset_add_log_action');
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

  /**
   * Helper method to test that an action link does not exist.
   *
   * @param string $label
   *   The action link label.
   * @param string $href
   *   The action link href.
   */
  protected function assertActionLinkNotExists(string $label, string $href): void {
    $this->assertSession()->linkNotExists($label);
    $this->assertSession()->linkByHrefNotExists($href);
  }

}
