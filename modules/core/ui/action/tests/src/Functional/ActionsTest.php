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
    'farm_log_asset',
    'farm_owner',
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
      'access user profiles',
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
    $this->assertActionLinkExists('Add Log: Test', '/log/add/test?owner=' . $this->user->id());

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
   * Test that user-page log creation assigns that user, not the creator.
   */
  public function testUserLogOwner() {
    $owner = $this->createUser();

    // Revisit both user pages to exercise route-dependent cached action links.
    foreach ([$owner, $this->user, $owner] as $account) {
      $this->drupalGet('/user/' . $account->id() . '/logs');
      $this->assertSession()->statusCodeEquals(200);
      $this->assertActionLinkExists('Add Log: Test', '/log/add/test?owner=' . $account->id());
    }

    $this->clickLink('Add Log: Test');
    $this->assertEquals([$owner->id()], $this->getSession()->getPage()->findField('owner[]')->getValue());
    $name = $this->randomMachineName();
    $this->submitForm(['name[0][value]' => $name], 'Save');
    $logs = \Drupal::entityTypeManager()->getStorage('log')->loadByProperties(['name' => $name]);
    $this->assertCount(1, $logs);
    $log = reset($logs);
    $this->assertEquals([$owner->id()], array_column($log->get('owner')->getValue(), 'target_id'));

    // Ordinary log creation still falls back to the creating user on save.
    $this->drupalGet('/log/add/test');
    $this->assertEquals(['_none'], $this->getSession()->getPage()->findField('owner[]')->getValue());
    $name = $this->randomMachineName();
    $this->submitForm(['name[0][value]' => $name], 'Save');
    $logs = \Drupal::entityTypeManager()->getStorage('log')->loadByProperties(['name' => $name]);
    $log = reset($logs);
    $this->assertEquals([$this->user->id()], array_column($log->get('owner')->getValue(), 'target_id'));

    // The existing asset query still prepopulates assets without owners.
    $asset = Asset::create(['type' => 'test', 'name' => $this->randomMachineName()]);
    $asset->save();
    $this->drupalGet('/log/add/test', ['query' => ['asset' => $asset->id()]]);
    $asset_field = $this->assertSession()->elementExists('css', 'input[name="asset[target_id]"]');
    $this->assertEquals('asset:' . $asset->id(), $asset_field->getAttribute('value'));
    $this->assertEquals(['_none'], $this->getSession()->getPage()->findField('owner[]')->getValue());
    $name = $this->randomMachineName();
    $this->submitForm(['name[0][value]' => $name], 'Save');
    $logs = \Drupal::entityTypeManager()->getStorage('log')->loadByProperties(['name' => $name]);
    $log = reset($logs);
    $this->assertEquals($asset->id(), $log->get('asset')->target_id);
    $this->assertEquals([$this->user->id()], array_column($log->get('owner')->getValue(), 'target_id'));
  }

  /**
   * Test owner query cardinality, invalid IDs, and user view access.
   */
  public function testOwnerQueryValues() {
    $owner = $this->createUser();
    $this->drupalGet('/log/add/test', ['query' => ['owner' => [$owner->id(), 'missing', ['nested']]]]);
    $this->assertSession()->statusCodeEquals(200);
    $this->assertEquals([$owner->id()], $this->getSession()->getPage()->findField('owner[]')->getValue());

    $this->drupalGet('/log/add/test', ['query' => ['owner' => '999999']]);
    $this->assertSession()->statusCodeEquals(200);
    $this->assertEquals(['_none'], $this->getSession()->getPage()->findField('owner[]')->getValue());

    // Query input must not prepopulate a user the creator cannot view.
    $creator = $this->createUser(['create test log', 'view any log']);
    $this->drupalLogin($creator);
    $this->drupalGet('/log/add/test', ['query' => ['owner' => $owner->id()]]);
    $this->assertSession()->statusCodeEquals(200);
    $this->assertEquals(['_none'], $this->getSession()->getPage()->findField('owner[]')->getValue());
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
