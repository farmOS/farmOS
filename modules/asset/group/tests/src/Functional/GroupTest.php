<?php

namespace Drupal\Tests\farm_group\Functional;

use Drupal\FunctionalJavascriptTests\WebDriverTestBase;
use Drupal\log\Entity\Log;

/**
 * Tests for farmOS group membership logic.
 *
 * @group farm
 */
class GroupTest extends WebDriverTestBase {

  /**
   * {@inheritdoc}
   */
  protected $profile = 'farm';

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'farm_group',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $GLOBALS['farm_test'] = TRUE;

    // Create and login a user with permission to administer logs.
    $user = $this->createUser(['administer log']);
    $this->drupalLogin($user);
  }

  /**
   * Test group field visibility.
   */
  public function testGroupFieldVisibility() {

    // Create a log for testing.
    /** @var \Drupal\log\Entity\LogInterface $$log */
    $log = Log::create([
      'type' => 'test',
    ]);
    $log->save();

    // Go to the log edit form.
    $this->drupalGet('log/' . $log->id() . '/edit');
    $this->assertSession()->statusCodeEquals(200);

    // Test that the group field is hidden.
    $page = $this->getSession()->getPage();
    $group_field = $page->findField('group');
    $this->assertNotEmpty($group_field);
    $this->assertFalse($group_field->isVisible());

    // Make the log a group assignment.
    $log->is_group_assignment = TRUE;
    $log->save();

    // Go back to the edit form.
    $this->drupalGet('log/' . $log->id() . '/edit');
    $this->assertSession()->statusCodeEquals(200);

    // Test that the group field is visible.
    $page = $this->getSession()->getPage();
    $group_field = $page->findField('group');
    $this->assertNotEmpty($group_field);
    $this->assertTrue($group_field->isVisible());
  }

}
