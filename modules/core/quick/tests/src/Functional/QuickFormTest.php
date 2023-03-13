<?php

namespace Drupal\Tests\farm_quick\Functional;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Tests\farm_test\Functional\FarmBrowserTestBase;

/**
 * Tests the quick form framework.
 *
 * @group farm
 */
class QuickFormTest extends FarmBrowserTestBase {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'farm_quick_test',
  ];

  /**
   * Test quick forms.
   */
  public function testQuickForms() {

    // Create and login a test user with no permissions.
    $user = $this->createUser();
    $this->drupalLogin($user);

    // Go to the quick form index and confirm that access is denied.
    $this->drupalGet('quick');
    $this->assertSession()->statusCodeEquals(403);

    // Create and login a test user with access to the quick form index.
    $user = $this->createUser(['view quick forms index']);
    $this->drupalLogin($user);

    // Go to the quick form index and confirm that access is granted, but no
    // quick forms are visible.
    $this->drupalGet('quick');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains($this->t('You do not have any quick forms.'));

    // Go to the test quick form and confirm that access is denied.
    $this->drupalGet('quick/test');
    $this->assertSession()->statusCodeEquals(403);

    // Create and login a test user with access to the quick form index, and
    // permission to create test logs.
    $user = $this->createUser(['view quick forms index', 'create test log']);
    $this->drupalLogin($user);

    // Go to the quick form index and confirm that access is granted, and the
    // test quick form item is visible.
    $this->drupalGet('quick');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains($this->t('Test quick form'));

    // Go to the test quick form and confirm that the test field is visible.
    $this->drupalGet('quick/test');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains($this->t('Test field'));
  }

}
