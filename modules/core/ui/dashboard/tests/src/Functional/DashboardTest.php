<?php

namespace Drupal\Tests\farm_ui_dashboard\Functional;

use Drupal\Tests\farm_test\Functional\FarmBrowserTestBase;

/**
 * Tests the farmOS dashboard functionality.
 *
 * @group farm
 */
class DashboardTest extends FarmBrowserTestBase {

  /**
   * Test user.
   *
   * @var \Drupal\user\Entity\User
   */
  protected $user;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'farm_ui_dashboard',
    'farm_ui_dashboard_test',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    // Create and login a user with necessary permissions.
    $this->user = $this->createUser(['access farm dashboard']);
    $this->drupalLogin($this->user);
  }

  /**
   * Test that custom blocks are added to the dashboard.
   */
  public function testDashboardBlock() {
    $this->drupalGet('/dashboard');
    $this->assertSession()->statusCodeEquals(200);

    // Assert that the test block was added.
    $this->assertSession()->pageTextContains('This is the dashboard test block.');
  }

  /**
   * Test that custom views are added to the dashboard.
   */
  public function testDashboardView() {
    $this->drupalGet('/dashboard');
    $this->assertSession()->statusCodeEquals(200);

    // Assert that the test view was not added to the dashboard.
    $this->assertSession()->pageTextNotContains('User list');
    $this->assertSession()->pageTextNotContains($this->user->getAccountName());

    $user = $this->createUser(['access farm dashboard', 'access user profiles']);
    $this->drupalLogin($user);

    $this->drupalGet('/dashboard');
    $this->assertSession()->statusCodeEquals(200);

    // Assert that the test view was added to the dashboard.
    $this->assertSession()->pageTextContains('User list');
    $this->assertSession()->pageTextContains($user->getAccountName());
  }

}
