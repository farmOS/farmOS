<?php

namespace Drupal\Tests\farm_location\Functional;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\farm_geo\Traits\WktTrait;
use Drupal\FunctionalJavascriptTests\WebDriverTestBase;
use Drupal\Tests\jsonapi\Functional\JsonApiRequestTestTrait;

/**
 * Tests for farmOS location logic.
 *
 * @group farm
 */
class LocationTest extends WebDriverTestBase {

  use StringTranslationTrait;
  use WktTrait;
  use JsonApiRequestTestTrait;
  use LocationFunctionalTestTrait {
    setUp as locationSetup;
  }

  /**
   * {@inheritdoc}
   */
  protected $profile = 'farm';

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'basic_auth',
    'farm_location',
    'farm_location_test',
    'farm_api',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    $GLOBALS['farm_test'] = TRUE;
    $this->locationSetup();
  }

  /**
   * Test computed asset location.
   */
  public function testComputedAssetLocation() {

    // The computed location of the asset is the location asset.
    $location = $this->asset->get('location')->referencedEntities();
    $this->assertEquals($this->location->id(), $location[0]->id(), 'Computed asset location is the location asset.');

    // The computed geometry of the asset is the location asset geometry.
    $this->assertEquals($this->location->get('intrinsic_geometry')->value, $this->asset->get('geometry')->value, 'Computed asset geometry is the location asset geometry.');
  }

  /**
   * Test geometry and location field visibility.
   */
  public function testLocationFieldVisibility() {

    // Go to the asset edit form.
    $this->drupalGet('asset/' . $this->asset->id() . '/edit');

    // Test that current geometry and current location fields are all hidden.
    $this->assertSession()->fieldNotExists('geometry[0][value]');
    $this->assertSession()->fieldNotExists('location[0][target_id]');

    // Test that intrinsic_geometry field is hidden.
    $page = $this->getSession()->getPage();
    $intrinsic_geometry_field = $page->findById('edit-intrinsic-geometry-wrapper');
    $this->assertNotEmpty($intrinsic_geometry_field);
    $this->assertFalse($intrinsic_geometry_field->isVisible());

    // Go to the asset view page.
    $this->drupalGet('asset/' . $this->asset->id());

    // Test that current geometry and location fields are visible.
    $this->assertSession()->pageTextContains("Current geometry");
    $this->assertSession()->pageTextContains("Current location");

    // Test that the intrinsic geometry field is hidden.
    $this->assertSession()->pageTextNotContains("Intrinsic geometry");

    // Make the asset fixed.
    $this->asset->is_fixed = TRUE;
    $this->asset->save();

    // Go back to the edit form.
    $this->drupalGet('asset/' . $this->asset->id() . '/edit');

    // Test that the intrinsic geometry field is visible.
    $page = $this->getSession()->getPage();
    $intrinsic_geometry_field = $page->findById('edit-intrinsic-geometry-wrapper');
    $this->assertNotEmpty($intrinsic_geometry_field);
    $this->assertTrue($intrinsic_geometry_field->isVisible());
  }

}
