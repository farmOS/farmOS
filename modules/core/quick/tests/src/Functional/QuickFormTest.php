<?php

namespace Drupal\Tests\farm_quick\Functional;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\farm_quick\Entity\QuickFormInstance;
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
    'help',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    // Add the help block, so we can test help text.
    $this->drupalPlaceBlock('help_block');
  }

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

    // Create and login a test user with access to view quick forms.
    $user = $this->createUser(['view quick_form']);
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
    $user = $this->createUser(['view quick_form', 'create test log']);
    $this->drupalLogin($user);

    // Go to the quick form index and confirm that:
    // 1. access is granted.
    // 2. the test quick form item is visible.
    // 3. the default configurable_test quick form item is visible.
    // 4. the second instance of configurable_test quick form item is visible.
    // 5. the requires_entity_test quick form item is NOT visible.
    $this->drupalGet('quick');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains($this->t('Test quick form'));
    $this->assertSession()->pageTextContains($this->t('Test configurable quick form'));
    $this->assertSession()->pageTextContains($this->t('Test configurable quick form 2'));
    $this->assertSession()->pageTextNotContains($this->t('Test requiresEntity quick form'));

    // Go to the test quick form and confirm that the help text and test field
    // is visible.
    $this->drupalGet('quick/test');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains($this->t('Test quick form help text.'));
    $this->assertSession()->pageTextContains($this->t('Test field'));

    // Go to the default configurable_test quick form and confirm access is
    // granted and the default value is 100.
    $this->drupalGet('quick/configurable_test');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->responseContains('value="100"');

    // Attempt to load the edit form for the unsaved configurable_test quick
    // form and confirm 404 not found.
    $this->drupalGet('setup/quick/foo/configurable_test');
    $this->assertSession()->statusCodeEquals(404);

    // Go to the configurable_test2 quick form and confirm access is granted and
    // the default value is 500.
    $this->drupalGet('quick/configurable_test2');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->responseContains('value="500"');

    // Attempt to load the edit form for saved configurable_test2 quick
    // form and confirm 403.
    $this->drupalGet('setup/quick/configurable_test2/edit');
    $this->assertSession()->statusCodeEquals(403);

    // Create and login a test user with permission to create test logs and
    // permission to update quick forms.
    $user = $this->createUser(['view quick_form', 'create test log', 'update quick_form']);
    $this->drupalLogin($user);

    // Go to the configurable_test2 quick form and confirm that the default
    // value field is visible and the default value is 500.
    $this->drupalGet('setup/quick/configurable_test2/edit');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains($this->t('Default value'));
    $this->assertSession()->responseContains('value="500"');

    // Save the configurable_test2 config entity to change the value and
    // confirm that it is updated in the quick form and configuration form.
    $config_entity = \Drupal::entityTypeManager()->getStorage('quick_form')->load('configurable_test2');
    $config_entity->set('settings', ['test_default' => 600]);
    $config_entity->save();
    $this->drupalGet('quick/configurable_test2');
    $this->assertSession()->responseContains('value="600"');
    $this->drupalGet('setup/quick/configurable_test2/edit');
    $this->assertSession()->responseContains('value="600"');

    // Attempt to load an edit form for a non-existent quick form and
    // confirm 404 not found.
    $this->drupalGet('setup/quick/foo/edit');
    $this->assertSession()->statusCodeEquals(404);

    // Go to the requires_entity_test quick form and confirm 404 not found.
    $this->drupalGet('quick/requires_entity_test');
    $this->assertSession()->statusCodeEquals(404);

    // Create a config entity for the requires_entity_test plugin.
    $config_entity = QuickFormInstance::create([
      'id' => 'requires_entity_test',
      'plugin' => 'requires_entity_test',
    ]);
    $config_entity->save();

    // Rebuild routes.
    \Drupal::service('router.builder')->rebuildIfNeeded();

    // Go to the quick form index and confirm that the requires_entity_test
    // quick form item is visible.
    $this->drupalGet('quick');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains($this->t('Test requiresEntity quick form'));

    // Go to the default requires_entity_test quick form and confirm access
    // granted and the default value is 100.
    $this->drupalGet('quick/requires_entity_test');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains($this->t('Test field'));

    // Delete the config entity and confirm that it is removed.
    $config_entity->delete();
    \Drupal::service('router.builder')->rebuildIfNeeded();
    $this->drupalGet('quick');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextNotContains($this->t('Test requiresEntity quick form'));
    $this->drupalGet('quick/requires_entity_test');
    $this->assertSession()->statusCodeEquals(404);
  }

}
