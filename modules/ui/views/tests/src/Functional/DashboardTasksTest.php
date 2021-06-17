<?php

namespace Drupal\Tests\farm_ui_views\Functional;

use Drupal\log\Entity\Log;
use Drupal\Tests\farm_test\Functional\FarmBrowserTestBase;

/**
 * Tests the farm_ui_views dashboard panes.
 *
 * @group farm
 */
class DashboardTasksTest extends FarmBrowserTestBase {

  /**
   * Test user.
   *
   * @var \Drupal\user\Entity\User
   */
  protected $user;

  /**
   * Test role ID.
   *
   * @var string
   */
  protected $role;

  /**
   * Test log.
   *
   * @var \Drupal\log\Entity\Log
   */
  protected $log;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'farm_observation',
    'farm_ui_dashboard',
    'farm_ui_views',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    // Create and login a user with permission to access dashboard.
    $this->user = $this->createUser(['access farm dashboard']);
    $this->drupalLogin($this->user);

    // Create a role that has permission to view log.
    $this->role = $this->drupalCreateRole(['access farm dashboard', 'view any log']);

    // Create a log that is done.
    $this->log = Log::create([
      'name' => 'Planned log',
      'type' => 'observation',
      'status' => 'done',
      'timestamp' => \Drupal::time()->getCurrentTime() + 86400,
    ]);
    $this->log->save();
  }

  /**
   * Test the upcoming tasks view on the dashboard.
   */
  public function testUpcomingTasks() {
    $this->drupalGet('/dashboard');
    $this->assertSession()->statusCodeEquals(200);

    // Assert that the upcoming tasks view was not added.
    $this->assertSession()->pageTextNotContains('Upcoming tasks');

    // Grant the user permission to view any log.
    $this->user->addRole($this->role);
    $this->user->save();

    // Assert that the upcoming tasks view is added.
    $this->drupalGet('/dashboard');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains('Upcoming tasks');

    // Assert that the log is not displayed.
    $this->assertSession()->pageTextContains('No logs found.');

    // Mark the log as pending in the future.
    $this->log->status = 'pending';
    $this->log->timestamp = \Drupal::time()->getCurrentTime() + 86400;
    $this->log->save();

    // Assert that the upcoming tasks view is added.
    $this->drupalGet('/dashboard');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains('Upcoming tasks');

    // Assert that the log is displayed.
    $this->assertSession()->pageTextContains($this->log->label());
  }

  /**
   * Test the late tasks view on the dashboard.
   */
  public function testLateTasks() {
    $this->drupalGet('/dashboard');
    $this->assertSession()->statusCodeEquals(200);

    // Assert that the upcoming tasks view was not added.
    $this->assertSession()->pageTextNotContains('Late tasks');

    // Grant the user permission to view any log.
    $this->user->addRole($this->role);
    $this->user->save();

    // Assert that the upcoming tasks view is added.
    $this->drupalGet('/dashboard');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains('Late tasks');

    // Assert that the log is not displayed.
    $this->assertSession()->pageTextContains('No logs found.');

    // Mark the log as pending in the past.
    $this->log->status = 'pending';
    $this->log->timestamp = \Drupal::time()->getCurrentTime() - 86400;
    $this->log->save();

    // Assert that the upcoming tasks view is added.
    $this->drupalGet('/dashboard');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains('Late tasks');

    // Assert that the log is displayed.
    $this->assertSession()->pageTextContains($this->log->label());
  }

}
