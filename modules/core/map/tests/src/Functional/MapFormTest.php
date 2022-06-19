<?php

namespace Drupal\Tests\farm_map\Functional;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Tests\farm_test\Functional\FarmBrowserTestBase;

/**
 * Tests the farmOS map form element.
 *
 * @group farm
 */
class MapFormTest extends FarmBrowserTestBase {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'farm_map_test',
  ];

  /**
   * Test the farmOS map form element.
   */
  public function testMapForm() {

    // Create and login a test user with permission to create test logs.
    $user = $this->createUser(['create test log']);
    $this->drupalLogin($user);

    // Go to the test quick form and confirm that both of the geometry fields
    // are visible, and only the second field's WKT text field is visible.
    $this->drupalGet('quick/test');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains($this->t('Geometry 1'));
    $this->assertSession()->pageTextContains($this->t('Geometry 2'));
    $this->assertSession()->pageTextNotContains($this->t('Geometry 1 WKT'));
    $this->assertSession()->pageTextContains($this->t('Geometry 2 WKT'));

    // Submit the form with a value for the second geometry.
    $edit = ['geometry2[value]' => 'POINT(-45.967095060886315 32.77503850904169)'];
    $this->submitForm($edit, 'Submit');

    // Load logs.
    $logs = \Drupal::entityTypeManager()->getStorage('log')->loadMultiple();

    // Confirm that two logs were created.
    $this->assertCount(2, $logs);

    // Check that the first log's geometry was populated with the form field's
    // default value.
    $log = $logs[1];
    $this->assertEquals('POINT(-42.689862437640826 32.621823310499934)', $log->get('geometry')->value);

    // Check that the second log's geometry field was populated with the value
    // entered into the form.
    $log = $logs[2];
    $this->assertEquals('POINT(-45.967095060886315 32.77503850904169)', $log->get('geometry')->value);

    // Test that submitting an invalid geometry throws a form validation error.
    $this->drupalGet('quick/test');
    $edit = ['geometry2[value]' => 'POLYGON()'];
    $this->submitForm($edit, 'Submit');
    $this->assertSession()->pageTextContains($this->t('"POLYGON()" is not a valid geospatial content.'));
  }

}
