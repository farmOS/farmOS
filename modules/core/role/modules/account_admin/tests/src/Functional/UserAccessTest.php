<?php

namespace Drupal\Tests\farm_role_account_admin\Functional;

use Drupal\Tests\farm_test\Functional\FarmBrowserTestBase;

/**
 * Tests access to user 1.
 *
 * @group farm
 */
class UserAccessTest extends FarmBrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'farm_role_account_admin',
  ];

  /**
   * Test user 1 access.
   */
  public function testUser1Access() {

    // Create and login a user with farm_account_admin role.
    $user = $this->createUser();
    $user->addRole('farm_account_admin');
    $user->save();
    $this->drupalLogin($user);

    // Confirm that the user cannot access user 1.
    $this->drupalGet('user/1');
    $this->assertSession()->statusCodeEquals(403);
    $this->drupalGet('user/1/edit');
    $this->assertSession()->statusCodeEquals(403);
  }

}
